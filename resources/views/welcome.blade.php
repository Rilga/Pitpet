<x-app-layout>

    {{-- ========================================== --}}
    {{-- 1. HERO SECTION (CLEAN & MINIMALIST) --}}
    {{-- ========================================== --}}
    <section class="bg-white relative overflow-hidden pt-16 pb-20 lg:pt-24 lg:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-3xl mx-auto">
                {{-- Slogan Kecil --}}
                <p class="font-bold text-[#2ba6c5] tracking-widest uppercase text-sm mb-4">
                    Animals Are More Than Just Pets
                </p>
                
                {{-- Headline Besar --}}
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                    Grooming Premium <br>
                    <span class="relative inline-block">
                        <span class="relative z-10">Tanpa Keluar Rumah</span>
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#2ba6c5]/20 -z-0"></span> {{-- Aksen Garis Bawah --}}
                    </span>
                </h1>

                {{-- Sub-headline --}}
                <p class="mt-4 text-xl text-gray-500 mb-10 leading-relaxed">
                    Layanan profesional langsung ke pintu Anda di Jabodetabek. 
                    Kami menjamin kebersihan, kesehatan, dan kenyamanan anabul Anda.
                </p>

                {{-- Tombol CTA (Orange untuk Aksi) --}}
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('order.create') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-[#fc5205] hover:bg-[#e04804] transition duration-300 shadow-lg shadow-[#fc5205]/30">
                        Booking Sekarang
                    </a>
                    <a href="#pricelist" 
                       class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-200 text-lg font-bold rounded-full text-gray-600 hover:border-[#2ba6c5] hover:text-[#2ba6c5] bg-white transition duration-300">
                        Lihat Daftar Harga
                    </a>
                </div>
                
                {{-- Trust Badge --}}
                <div class="mt-10 flex justify-center items-center space-x-6 text-sm text-gray-400">
                    <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-[#2ba6c5] mr-2"></span>Profesional</span>
                    <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-[#2ba6c5] mr-2"></span>Higenis</span>
                    <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-[#2ba6c5] mr-2"></span>Home Service</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- 2. SERVICES & PRICING (GRID CLEAN) --}}
    {{-- ========================================== --}}
    <section id="pricelist" class="py-20 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Pilihan Layanan</h2>
                <div class="w-16 h-1 bg-[#fc5205] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- CARD 1: CAT GROOMING --}}
                {{-- Added: flex flex-col h-full --}}
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#2ba6c5]/30 transition duration-300 relative group flex flex-col h-full">
                    <div class="absolute top-0 right-0 bg-[#2ba6c5]/10 text-[#2ba6c5] font-bold text-xs px-3 py-1 rounded-bl-xl">KUCING</div>
                    
                    <div class="w-14 h-14 bg-[#2ba6c5]/10 rounded-xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition">🐱</div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Cat Grooming</h3>
                    <p class="text-gray-500 text-sm mb-6">Perawatan lengkap agar kucing bebas jamur & kutu.</p>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Dry Grooming</span>
                            <span class="text-[#fc5205] font-bold">50k</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Daily Grooming</span>
                            <span class="text-[#fc5205] font-bold">100k</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Mandi Kutu/Jamur</span>
                            <span class="text-[#fc5205] font-bold">110k</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Full Package</span>
                            <span class="text-[#fc5205] font-bold">150k</span>
                        </div>
                    </div>

                    {{-- Added: mt-auto (Agar tombol didorong ke paling bawah) --}}
                    <a href="{{ route('order.create') }}" class="mt-auto block w-full py-3 rounded-lg border-2 border-[#2ba6c5] text-[#2ba6c5] font-bold text-center hover:bg-[#2ba6c5] hover:text-white transition">
                        Pesan Kucing
                    </a>
                </div>

                {{-- CARD 2: DOG GROOMING --}}
                {{-- Added: flex flex-col h-full --}}
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#fc5205]/30 transition duration-300 relative group flex flex-col h-full">
                    <div class="absolute top-0 right-0 bg-[#fc5205]/10 text-[#fc5205] font-bold text-xs px-3 py-1 rounded-bl-xl">ANJING</div>
                    
                    <div class="w-14 h-14 bg-[#fc5205]/10 rounded-xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition">🐶</div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Dog Grooming</h3>
                    <p class="text-gray-500 text-sm mb-6">Harga disesuaikan berat badan (Size S - XL).</p>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Dry Grooming</span>
                            <span class="text-[#fc5205] font-bold">75k+</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Mandi Kutu/Jamur</span>
                            <span class="text-[#fc5205] font-bold">139k+</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Full Package</span>
                            <span class="text-[#fc5205] font-bold">179k+</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">
                            <span class="text-gray-600 font-medium">Styling / Cukur</span>
                            <span class="text-[#fc5205] font-bold">Start 99k</span>
                        </div>
                    </div>

                    {{-- Added: mt-auto --}}
                    <a href="{{ route('order.create') }}" class="mt-auto block w-full py-3 rounded-lg border-2 border-[#fc5205] text-[#fc5205] font-bold text-center hover:bg-[#fc5205] hover:text-white transition">
                        Pesan Anjing
                    </a>
                </div>

                {{-- CARD 3: HOME SERVICE --}}
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-gray-300 transition duration-300 relative group flex flex-col h-full">
                    <div class="absolute top-0 right-0 bg-gray-100 text-gray-500 font-bold text-xs px-3 py-1 rounded-bl-xl">TRANSPORT</div>
                    
                    <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition">🛵</div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Home Service Fee</h3>
                    <p class="text-gray-500 text-sm mb-6">Dihitung otomatis via sistem peta dari PitPet Clinic.</p>

                    <div class="space-y-4 mb-8 bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">0 - 10 km</span>
                            <span class="bg-[#2ba6c5] text-white text-xs font-bold px-2 py-0.5 rounded">GRATIS</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">11 - 20 km</span>
                            <span class="text-gray-800 font-bold text-sm">25k</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">21 - 30 km</span>
                            <span class="text-gray-800 font-bold text-sm">35k</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">31 - 40 km</span>
                            <span class="text-gray-800 font-bold text-sm">60k</span>
                        </div>
                    </div>

                    <a href="{{ route('order.create') }}" class="mt-auto block w-full py-3 rounded-lg bg-gray-800 text-white font-bold text-center hover:bg-gray-700 transition">
                        Cek Lokasi
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- 3. SIZING GUIDE (MINIMALIST) --}}
    {{-- ========================================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/3">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Panduan Ukuran</h2>
                    <p class="text-gray-500 leading-relaxed">
                        Kami mengkategorikan ukuran anjing berdasarkan berat badan untuk menentukan harga layanan yang adil dan akurat.
                    </p>
                </div>
                
                <div class="md:w-2/3 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    {{-- Size S --}}
                    <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-[#2ba6c5]/5 transition duration-300">
                        <span class="text-3xl block mb-2 grayscale opacity-70">🐕</span>
                        <h4 class="text-2xl font-bold text-[#2ba6c5]">S</h4>
                        <span class="text-xs text-gray-500 font-medium">2-10 kg</span>
                    </div>
                    {{-- Size M --}}
                    <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-[#2ba6c5]/5 transition duration-300">
                        <span class="text-3xl block mb-2 grayscale opacity-70">🐕‍🦺</span>
                        <h4 class="text-2xl font-bold text-[#2ba6c5]">M</h4>
                        <span class="text-xs text-gray-500 font-medium">11-25 kg</span>
                    </div>
                    {{-- Size L --}}
                    <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-[#2ba6c5]/5 transition duration-300">
                        <span class="text-3xl block mb-2 grayscale opacity-70">🦮</span>
                        <h4 class="text-2xl font-bold text-[#2ba6c5]">L</h4>
                        <span class="text-xs text-gray-500 font-medium">26-45 kg</span>
                    </div>
                    {{-- Size XL --}}
                    <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-[#2ba6c5]/5 transition duration-300">
                        <span class="text-3xl block mb-2 grayscale opacity-70">🐺</span>
                        <h4 class="text-2xl font-bold text-[#2ba6c5]">XL</h4>
                        <span class="text-xs text-gray-500 font-medium">> 45 kg</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- 4. FOOTER / FINAL CTA (CLEAN WHITE) --}}
    {{-- ========================================== --}}
    <section class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            
            <h2 class="text-4xl font-extrabold text-gray-900 mb-6">
                Siap Memanjakan Anabul?
            </h2>
            <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto">
                Slot harian kami terbatas untuk menjaga kualitas layanan. 
                <span class="text-[#fc5205] font-semibold">Max 3 Groomer</span> bekerja dalam satu waktu.
            </p>

            <a href="{{ route('order.create') }}" class="inline-block bg-[#fc5205] text-white font-bold text-lg px-12 py-5 rounded-full shadow-xl shadow-[#fc5205]/30 hover:bg-[#e04804] hover:-translate-y-1 transform transition duration-300">
                Booking Jadwal Sekarang
            </a>

            <div class="mt-16 flex justify-center space-x-8 border-t border-gray-100 pt-8">
                <div class="text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">WhatsApp</p>
                    <p class="text-gray-900 font-medium">0821-9999-2481</p>
                </div>
                <div class="text-center border-l border-gray-200 pl-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Website</p>
                    <p class="text-gray-900 font-medium">pitpet.id</p>
                </div>
            </div>

            <p class="text-gray-400 text-xs mt-12">
                &copy; {{ date('Y') }} PitPet Grooming. Made with <span class="text-[#fc5205]">♥</span> for Pets.
            </p>

        </div>
    </section>

</x-app-layout>