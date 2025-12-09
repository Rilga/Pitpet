<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header & Navigasi --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Edit Order <span class="text-[#2ba6c5]">#{{ substr($order->id . $order->created_at, 0, 6) }}</span>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Ubah detail booking, status, dan penugasan groomer.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#fc5205] transition bg-white px-5 py-2.5 rounded-xl shadow-sm border border-gray-200 hover:border-[#fc5205]/30 group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-[#fc5205] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white shadow-xl shadow-gray-100 sm:rounded-3xl border border-gray-100 p-8 md:p-10 relative overflow-hidden">
                
                {{-- Dekorasi Background Halus --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#2ba6c5]/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

                <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 relative z-10">
                        
                        {{-- ========================================== --}}
                        {{-- KOLOM KIRI: DATA CUSTOMER (TEAL ACCENT) --}}
                        {{-- ========================================== --}}
                        <div class="space-y-6">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                                <div class="w-10 h-10 rounded-lg bg-[#2ba6c5]/10 flex items-center justify-center text-[#2ba6c5] mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Data Customer</h3>
                                    <p class="text-xs text-gray-400">Informasi pemesan & jadwal</p>
                                </div>
                            </div>
                            
                            {{-- Nama --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Customer</label>
                                <input type="text" name="customer_name" value="{{ $order->customer_name }}" 
                                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50/50 focus:bg-white transition font-semibold text-gray-800">
                            </div>

                            {{-- Kontak --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- 1. No. WhatsApp (INPUT) --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. WhatsApp</label>
                                    <input type="text" name="customer_phone" value="{{ $order->customer_phone }}" 
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-4 bg-gray-50/50 focus:bg-white transition">
                                </div>
                                
                                {{-- 2. Tombol Chat (MENGGANTIKAN INPUT EMAIL) --}}
                                <div>
                                    {{-- Label di buat invisible agar tombol rata dengan input di atasnya --}}
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 invisible">Tombol Chat</label>
                                    
                                    {{-- Tombol WA (Menggunakan Green/Teal accent) --}}
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer_phone) }}" target="_blank"
                                        class="flex items-center justify-center h-12 w-full bg-[#2ba6c5] text-white border border-[#2ba6c5] rounded-xl text-sm font-bold hover:bg-[#238fa8] transition shadow-md shadow-[#2ba6c5]/20">
                                        
                                        {{-- SVG PATH dari request Anda --}}
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Chat Customer
                                    </a>
                                </div>
                            </div>

                            {{-- Jadwal --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jadwal & Durasi Pengerjaan</label>
                                
                                @php
                                    // Pecah string "08:00-09:00" menjadi dua variabel
                                    // Agar bisa dimasukkan ke input type="time"
                                    $times = explode('-', $order->time_slot);
                                    $start = isset($times[0]) ? trim($times[0]) : '08:00';
                                    $end   = isset($times[1]) ? trim($times[1]) : '09:00';
                                @endphp

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    {{-- Tanggal --}}
                                    <div>
                                        <label class="text-[10px] text-gray-400 font-bold mb-1 block">Tanggal</label>
                                        <input type="date" name="date" value="{{ $order->date }}" 
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-2.5 px-3 bg-white font-bold text-gray-700">
                                    </div>

                                    {{-- Jam Mulai --}}
                                    <div>
                                        <label class="text-[10px] text-gray-400 font-bold mb-1 block">Mulai (Start)</label>
                                        <input type="time" name="start_time" value="{{ $start }}" 
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-2.5 px-3 bg-white font-bold text-gray-700 text-center">
                                    </div>

                                    {{-- Jam Selesai --}}
                                    <div>
                                        <label class="text-[10px] text-[#fc5205] font-bold mb-1 block">Selesai (End)</label>
                                        <input type="time" name="end_time" value="{{ $end }}" 
                                               class="w-full rounded-xl border-[#fc5205] shadow-sm focus:border-[#e04804] focus:ring focus:ring-[#fc5205]/20 py-2.5 px-3 bg-[#fff8f5] font-bold text-[#fc5205] text-center">
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 italic">
                                    *Admin dapat mengubah "Jam Selesai" untuk menambah/mengurangi durasi di jadwal.
                                </p>
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Penjemputan</label>
                                <textarea name="customer_address" rows="4" 
                                          class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50/50 focus:bg-white transition">{{ $order->customer_address }}</textarea>
                            </div>
                        </div>

                        {{-- ========================================== --}}
                        {{-- KOLOM KANAN: OPERASIONAL (ORANGE ACCENT) --}}
                        {{-- ========================================== --}}
                        <div class="space-y-6">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                                <div class="w-10 h-10 rounded-lg bg-[#fc5205]/10 flex items-center justify-center text-[#fc5205] mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Operasional</h3>
                                    <p class="text-xs text-gray-400">Status order & penugasan</p>
                                </div>
                            </div>

                            {{-- STATUS ORDER --}}
                            <div class="bg-[#fff8f5] p-6 rounded-2xl border border-[#fc5205]/10">
                                <label class="block text-xs font-bold text-[#fc5205] mb-2 uppercase tracking-wide">Update Status Order</label>
                                <div class="relative">
                                    <select name="status" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#fc5205] focus:ring focus:ring-[#fc5205]/20 py-3.5 px-4 font-bold text-gray-800 bg-white appearance-none cursor-pointer hover:border-[#fc5205] transition">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🟡 Pending (Menunggu)</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>🔵 Confirmed (Diterima)</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🟢 Completed (Selesai)</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>🔴 Cancelled (Batal)</option>
                                        <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>⚫ Rejected (Ditolak)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#fc5205]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- ASSIGN GROOMER (DINAMIS DARI USER) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tugaskan Groomer</label>
                                <div class="relative">
                                    {{-- Name harus 'groomer_id' karena kita pakai ID --}}
                                    <select name="groomer_id" 
                                            class="w-full rounded-xl border-[#2ba6c5] bg-[#f0f9fb] text-gray-800 font-bold shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-4 pl-12 appearance-none cursor-pointer transition">
                                        
                                        <option value="">-- Belum Ada Groomer --</option>
                                        
                                        {{-- Loop Data dari Tabel Users (Role: User) --}}
                                        @foreach($groomers as $groomer)
                                            <option value="{{ $groomer->id }}" {{ $order->groomer_id == $groomer->id ? 'selected' : '' }}>
                                                {{ $groomer->name }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    
                                    {{-- Icon User --}}
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#2ba6c5]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    {{-- Icon Chevron --}}
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-[#2ba6c5]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 ml-1 italic">
                                    *List diambil dari database User dengan role 'user'.
                                </p>
                            </div>

                            {{-- Detail Hewan (Read Only) --}}
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider border-b border-gray-200 pb-2">Rincian Pembayaran</p>
                                <ul class="text-sm text-gray-600 space-y-3">
                                    @foreach($order->pets as $pet)
                                        <li class="flex justify-between items-center pb-2 border-b border-gray-200 border-dashed last:border-0 last:pb-0">
                                            <div class="flex items-center">
                                                <span class="mr-3 text-lg">{{ $pet->pet_type == 'cat' ? '🐱' : '🐶' }}</span>
                                                <div>
                                                    <span class="font-bold text-gray-800 block">{{ $pet->service_name }}</span>
                                                    @if($pet->dog_size)
                                                        <span class="text-[10px] bg-gray-200 px-1.5 py-0.5 rounded text-gray-600 font-bold">Size {{ $pet->dog_size }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="font-mono text-gray-600 font-medium">Rp {{ number_format($pet->service_price, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                    
                                    {{-- Total Section --}}
                                    <li class="pt-4 mt-2 border-t border-gray-300">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Subtotal Jasa</span>
                                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mb-2">
                                            <span>Transport Fee</span>
                                            <span>Rp {{ number_format($order->transport_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between font-extrabold text-xl text-[#fc5205] mt-3">
                                            <span>Total Akhir</span>
                                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Sticky Action Bar --}}
                    <div class="mt-12 flex items-center justify-end gap-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 font-bold hover:text-gray-600 transition text-sm">Batal</a>
                        <button type="submit" class="bg-[#fc5205] text-white font-bold py-3.5 px-10 rounded-xl shadow-lg shadow-[#fc5205]/30 hover:bg-[#e04804] hover:-translate-y-1 transition-all transform flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>