<x-app-layout>
    {{-- Load Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="h-[calc(100vh-65px)] relative bg-gray-100">
        
        <div id="adminMap" class="w-full h-full z-0"></div>

        {{-- Loading Indicator --}}
        <div id="loadingMap" class="absolute top-4 left-4 z-[400] bg-white px-4 py-2 rounded-lg shadow-lg border border-gray-200 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-[#fc5205]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div>
                <p class="text-xs font-bold text-gray-700">Memuat Lokasi Pending...</p>
                <p class="text-[10px] text-gray-400" id="progressText">0/0 selesai</p>
            </div>
        </div>

        {{-- Sidebar Legenda (UPDATED: HANYA PENDING) --}}
        <div class="absolute top-4 right-4 z-[400] w-64 bg-white rounded-xl shadow-2xl p-4 border border-gray-100 bg-opacity-95 backdrop-blur-sm">
            <h3 class="font-bold text-gray-900 text-sm mb-1">Peta Order Baru</h3>
            <p class="text-[10px] text-gray-500 mb-3 leading-relaxed">
                Menampilkan lokasi customer yang statusnya masih <strong>Pending</strong>.
            </p>
            
            <div class="space-y-2 text-xs">
                <div class="flex items-center">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png" class="w-3 h-5 mr-2">
                    <span>Pending Order (Menunggu)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-[#fc5205] border border-white ring-1 ring-gray-300 mr-2"></div>
                    <span>Toko (PitPet)</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi Peta
            const storeLat = -6.302075258990813; 
            const storeLng = 106.78154042561847;

            var map = L.map('adminMap').setView([storeLat, storeLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; PitPet Management'
            }).addTo(map);

            // Marker Toko
            var storeIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#fc5205; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);'></div>",
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
            L.marker([storeLat, storeLng], {icon: storeIcon}).addTo(map).bindPopup("<b>🏠 PitPet Clinic</b>");

            // 2. Data Orders
            const orders = @json($orders);
            const totalOrders = orders.length;
            let processedCount = 0;
            let drawnRoutes = []; // Menyimpan semua layer rute yang ditarik

            if(totalOrders === 0) {
                document.getElementById('loadingMap').style.display = 'none';
            } else {
                document.getElementById('progressText').innerText = `0/${totalOrders} alamat`;
            }

            // ==========================================
            // HELPER: MENGGAMBAR GARIS RUTE
            // ==========================================
            function drawRoute(geometry) {
                if (geometry && geometry.coordinates) {
                    const routeLine = L.geoJSON(geometry, {
                        style: {
                            color: '#2ba6c5', // Warna Teal untuk garis rute
                            weight: 4,
                            opacity: 0.75
                        }
                    }).addTo(map);
                    
                    // Simpan layer agar bisa dihapus nanti jika dibutuhkan
                    drawnRoutes.push(routeLine); 
                }
            }
            
            // HELPER: HITUNG JARAK & AMBIL GEOMETRI (OSRM)
            async function getRoadData(endLon, endLat) {
                try {
                    // Mengubah overview=full dan geometries=geojson untuk mendapatkan data path
                    let url = `https://router.project-osrm.org/route/v1/driving/${storeLng},${storeLat};${endLon},${endLat}?overview=full&geometries=geojson`; 
                    let res = await fetch(url);
                    let data = await res.json();
                    
                    if (data.routes && data.routes.length > 0) {
                        let distance = (data.routes[0].distance / 1000).toFixed(1);
                        let geometry = data.routes[0].geometry; // Ambil data garis rute
                        return { distance, geometry };
                    }
                    return { distance: 'N/A', geometry: null };
                } catch(e) {
                    return { distance: 'Error', geometry: null };
                }
            }


            // 3. Geocoding Loop (Auto Draw)
            orders.forEach((order, index) => {
                setTimeout(() => {
                    geocodeAndPin(order);
                }, index * 1200); // Delay 1.2 detik per customer (Anti-ban)
            });

            async function geocodeAndPin(order) {
                if (!order.customer_address || order.customer_address.length < 5) {
                    updateProgress();
                    return;
                }

                try {
                    // 3a. STEP 1: Cari Koordinat (Nominatim)
                    let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(order.customer_address)}&limit=1`;
                    let response = await fetch(url);
                    let data = await response.json();

                    if (data && data.length > 0) {
                        let lat = parseFloat(data[0].lat);
                        let lon = parseFloat(data[0].lon);
                        
                        // 3b. STEP 2: Hitung Jarak Tempuh & Ambil Geometri
                        const roadData = await getRoadData(lon, lat);

                        // --- MARKER DIBUAT MERAH SESUAI PERMINTAAN ---
                        var icon = new L.Icon({
                            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png`, // <-- MERAH
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                        });

                        // Isi Popup (dengan Jarak KM)
                        let popupContent = `
                            <div class="text-sm p-1 min-w-[200px]">
                                <strong class="text-gray-900 block mb-1">${order.customer_name}</strong>
                                <span class="text-xs text-gray-500 block mb-2">${order.customer_address}</span>
                                
                                <div class="text-xs mt-1 font-bold text-gray-700 border-t pt-2">
                                    Jarak Tempuh: <span class="text-[#2ba6c5]">${roadData.distance} KM</span>
                                </div>
                                
                                <div class="text-xs font-bold mb-2">
                                    📅 ${order.time_slot} (Pending)
                                </div>
                                
                                <a href="/order/${order.id}/edit" 
                                    class="block mt-2 text-center bg-[#fc5205] py-2 rounded-lg text-xs font-bold text-white hover:bg-[#e04804] transition"
                                    style="color: white !important;">Proses Order</a>
                            </div>
                        `;

                        L.marker([lat, lon], {icon: icon}).addTo(map).bindPopup(popupContent);
                        
                        // --- FUNGSI BARU: GAMBAR GARIS RUTE OTOMATIS ---
                        if (roadData.geometry) {
                            drawRoute(roadData.geometry);
                        }
                    }
                } catch (error) {
                    console.error("Gagal load lokasi:", order.customer_name);
                } finally {
                    updateProgress();
                }
            }

            function updateProgress() {
                processedCount++;
                document.getElementById('progressText').innerText = `${processedCount}/${totalOrders} selesai`;
                if(processedCount === totalOrders) {
                    setTimeout(() => { document.getElementById('loadingMap').style.display = 'none'; }, 1000);
                }
            }
        });
    </script>
</x-app-layout>