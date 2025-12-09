<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Halo, <span class="text-[#2ba6c5]">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm font-medium">
                        Selamat bekerja! Berikut adalah daftar tugas grooming Anda hari ini.
                    </p>
                </div>
                <div class="text-right bg-white px-5 py-2 rounded-xl border border-gray-200 shadow-sm">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</span>
                    <span class="text-lg font-bold text-gray-800">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            {{-- STATISTIK RINGKAS (Responsive Grid) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 font-bold uppercase">Total Tugas</p>
                    <p class="text-2xl font-extrabold text-gray-900">{{ $orders->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 font-bold uppercase">Selesai</p>
                    <p class="text-2xl font-extrabold text-green-500">{{ $orders->where('status', 'completed')->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 font-bold uppercase">Sisa</p>
                    <p class="text-2xl font-extrabold text-[#fc5205]">{{ $orders->where('status', 'confirmed')->count() }}</p>
                </div>
            </div>

            {{-- GRID TUGAS (Responsive: 1 kolom di HP, 2 di Tablet, 3 di Desktop) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($orders as $order)
                    
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-100 border border-gray-100 overflow-hidden hover:-translate-y-1 transition duration-300 flex flex-col h-full relative group">
                        
                        {{-- Indikator Status (Garis Atas) --}}
                        <div class="h-1.5 w-full {{ $order->status == 'completed' ? 'bg-green-500' : 'bg-[#fc5205]' }}"></div>

                        <div class="p-6 flex-1 flex flex-col">
                            {{-- Header Kartu: Jam & ID --}}
                            <div class="flex justify-between items-start mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-gray-900 text-white shadow-md">
                                    <svg class="w-4 h-4 mr-1.5 text-[#fc5205]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $order->time_slot }}
                                </span>
                                <span class="text-xs font-mono text-gray-400 font-bold">#{{ substr($order->id . $order->created_at, 0, 6) }}</span>
                            </div>

                            {{-- Data Customer --}}
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $order->customer_name }}</h3>
                                <p class="text-sm text-gray-500 leading-relaxed flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 text-[#2ba6c5] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $order->customer_address }}
                                </p>
                            </div>

                            {{-- List Hewan (Scrollable jika banyak) --}}
                            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 flex-1">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Detail Pengerjaan</p>
                                <ul class="space-y-3">
                                    @foreach($order->pets as $pet)
                                    <li class="flex items-start text-sm text-gray-700">
                                        <span class="mr-2 text-lg">{{ $pet->pet_type == 'cat' ? '🐱' : '🐶' }}</span>
                                        <div>
                                            <span class="font-bold block">{{ $pet->service_name }}</span>
                                            @if($pet->dog_size)
                                                <span class="text-[10px] bg-white border border-gray-200 px-1.5 py-0.5 rounded text-gray-500 font-semibold">Size {{ $pet->dog_size }}</span>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                @if($order->notes)
                                    <div class="mt-3 pt-3 border-t border-gray-200 text-xs text-gray-500 italic">
                                        Catatan: "{{ $order->notes }}"
                                    </div>
                                @endif
                            </div>

                            {{-- Tombol Aksi (Navigasi & WA) --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer_phone) }}" target="_blank"
                                   class="flex items-center justify-center px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-bold hover:bg-green-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->customer_address) }}" target="_blank"
                                   class="flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-sm font-bold hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Gmaps
                                </a>
                            </div>

                            {{-- Tombol Penyelesaian --}}
                            <form action="{{ route('groomer.order.status', $order->id) }}" method="POST">
                                @csrf @method('PUT')
                                
                                @if($order->status == 'confirmed')
                                    <button name="status" value="completed" class="w-full bg-[#fc5205] text-white font-bold py-3 rounded-xl shadow-lg shadow-[#fc5205]/30 hover:bg-[#e04804] transition transform active:scale-95 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Tandai Selesai
                                    </button>
                                @elseif($order->status == 'completed')
                                    <div class="w-full bg-green-100 text-green-700 font-bold py-3 rounded-xl border border-green-200 flex items-center justify-center cursor-default">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tugas Selesai
                                    </div>
                                @else
                                    <div class="w-full bg-gray-100 text-gray-400 font-bold py-3 rounded-xl border border-gray-200 text-center text-sm cursor-not-allowed">
                                        Menunggu Konfirmasi Admin
                                    </div>
                                @endif
                            </form>

                        </div>
                    </div>

                @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                            <span class="text-4xl grayscale opacity-50">☕</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tidak ada tugas hari ini</h3>
                        <p class="text-gray-500 mt-2">Silakan istirahat atau hubungi admin.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>