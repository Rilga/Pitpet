<x-app-layout>
    <div class="py-4 bg-gray-50 min-h-screen print:bg-white print:py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header & Filter Tanggal (Disembunyikan saat Print) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 print:hidden">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Jadwal Harian <span class="text-[#2ba6c5]">Groomer</span>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Laporan jadwal untuk operasional lapangan.</p>
                </div>

                <div class="flex gap-3 mt-4 md:mt-0">
                    <form action="{{ route('admin.schedule') }}" method="GET" class="flex gap-2">
                        <input type="date" name="date" value="{{ $date }}" 
                               onchange="this.form.submit()"
                               class="rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-2 px-4 text-sm">
                    </form>
                    <button onclick="window.print()" class="bg-[#fc5205] text-white px-4 py-2 rounded-xl font-bold text-sm shadow-lg hover:bg-[#e04804] transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak PDF
                    </button>
                </div>
            </div>

            {{-- LOOPING PER GROOMER (Seperti Halaman di PDF) --}}
            <div class="space-y-12 print:space-y-0 print:block">
                @foreach($groomers as $groomer)
                    
                    {{-- Container Kertas --}}
                    <div class="bg-white shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-200 overflow-hidden print:shadow-none print:border-0 print:break-after-page">
                        
                        {{-- Header Kop Surat Groomer --}}
                        <div class="bg-[#2ba6c5] px-6 py-4 flex justify-between items-center print:bg-white print:border-b-2 print:border-gray-800 print:px-0">
                            <div>
                                <h3 class="text-xl font-extrabold text-white print:text-black uppercase tracking-wider">
                                    {{ $groomer->name }}
                                </h3>
                                <p class="text-blue-100 text-sm print:text-gray-600">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                            <div class="text-white font-bold text-2xl opacity-50 print:text-black print:opacity-100">
                                PITPET
                            </div>
                        </div>

                        {{-- Tabel Jadwal --}}
                        <div class="p-0">
                            <table class="min-w-full divide-y divide-gray-200 border-collapse">
                                <thead class="bg-gray-50 print:bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase border-r border-gray-200 w-16 text-center">Jam</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase border-r border-gray-200 w-1/5">Nama</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase border-r border-gray-200 w-1/3">Alamat</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase border-r border-gray-200">Jenis Grooming</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        // Array untuk melacak jam mana yang harus di-skip karena kena rowspan
                                        $skipHours = []; 
                                    @endphp

                                    @for ($hour = 8; $hour <= 16; $hour++) {{-- Ubah loop sampai jam 16 saja (karena jam 17 tutup) --}}
                                        @php
                                            // Cari order yang MULAI di jam ini (Logika penentuan $order, $rowspan, $isSkipped tetap sama)
                                            $timeString = sprintf('%02d:00', $hour);
                                            
                                            $order = $orders->filter(function($o) use ($groomer, $timeString) {
                                                $start = explode('-', $o->time_slot)[0];
                                                return $o->groomer_id == $groomer->id && trim($start) == $timeString;
                                            })->first();

                                            // Array untuk melacak jam mana yang harus di-skip karena kena rowspan
                                            $isSkipped = isset($skipHours[$hour]); 
                                            
                                            $rowspan = 1;
                                            $mainServicesList = collect();
                                            $addOnsList = collect();
                                            $notes = [];
                                            
                                            if ($order) {
                                                [$startStr, $endStr] = explode('-', $order->time_slot);
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i', trim($startStr));
                                                $endTime = \Carbon\Carbon::createFromFormat('H:i', trim($endStr));
                                                
                                                $duration = $startTime->diffInHours($endTime);
                                                $rowspan = $duration > 0 ? $duration : 1;

                                                for($h = 1; $h < $rowspan; $h++) {
                                                    $skipHours[$hour + $h] = true;
                                                }

                                                // --- LOGIKA PEMISAHAN LAYANAN ---
                                                $addOnNames = [ 
                                                    'Lion Cut', 'Styling', 'Additional Handling', 'Bulu Gimbal & Kusut', 'Cukur Bulu Perut',
                                                    'Full Shave Cut', 'PitPet Styling', 'Brushing Teeth'
                                                ];
                                                $mainServicesList = $order->pets->filter(fn($pet) => !in_array($pet->service_name, $addOnNames));
                                                $addOnsList = $order->pets->filter(fn($pet) => in_array($pet->service_name, $addOnNames));
                                                
                                                // Logika Keterangan: Hitung hanya main services
                                                $cats = $mainServicesList->where('pet_type', 'cat')->count();
                                                $dogs = $mainServicesList->where('pet_type', 'dog')->count();
                                                
                                                if($cats > 0) $notes[] = "$cats Kucing";
                                                if($dogs > 0) $notes[] = "$dogs Anjing";
                                            }
                                        @endphp

                                        <tr class="transition
                                            {{-- Warna Background --}}
                                            @if($order) bg-blue-50/80 print:bg-gray-100 @elseif($isSkipped) bg-blue-50/30 print:bg-gray-50 @else hover:bg-gray-50 @endif
                                        ">
                                            
                                            {{-- Kolom JAM (Selalu Muncul) --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold text-gray-700 border-r border-gray-200 border-b border-gray-100">
                                                {{ $timeString }}
                                            </td>

                                            {{-- Logic Tampilan Data --}}
                                            @if($order)
                                                {{-- JIKA ADA BOOKING BARU MULAI --}}
                                                <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200 font-bold align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    <div class="text-[#2ba6c5] mb-1 text-xs uppercase tracking-wider">Booked</div>
                                                    {{ $order->customer_name }}
                                                    <div class="text-xs text-gray-500 font-normal mt-1">{{ $order->customer_phone }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600 border-r border-gray-200 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    {{ $order->customer_address }}
                                                </td>
                                                
                                                {{-- KOLOM JENIS GROOMING (DIPERBAIKI) --}}
                                                <td class="px-4 py-3 text-xs text-gray-600 border-r border-gray-200 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    
                                                    {{-- TAMPILAN LAYANAN UTAMA (DENGAN TITIK LIST) --}}
                                                    {{-- Menggunakan list-disc untuk titik --}}
                                                    <ul class="list-disc list-inside space-y-1">
                                                        @foreach($mainServicesList as $mainService)
                                                            <li class="font-medium text-gray-700">
                                                                {{ $mainService->service_name }}
                                                                @if($mainService->dog_size) <span class="font-normal text-gray-400">(Size {{ $mainService->dog_size }})</span> @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    
                                                    {{-- TAMPILAN ADD-ONS (TANPA TITIK LIST) --}}
                                                    @if($addOnsList->count() > 0)
                                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                                            <span class="text-gray-500 font-bold block mb-1">Add-ons:</span>
                                                            {{-- Menggunakan span/div biasa, BUKAN <ul> atau <li> --}}
                                                            <div class="text-gray-700 italic text-[11px] leading-tight block">
                                                                {{ $addOnsList->pluck('service_name')->implode(', ') }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                
                                                {{-- KOLOM KETERANGAN (MENGGUNAKAN LOGIC BARU) --}}
                                                <td class="px-4 py-3 text-xs text-gray-600 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    <span class="inline-block bg-white border border-[#fc5205] text-[#fc5205] px-2 py-1 rounded font-bold shadow-sm">
                                                        {{ implode(', ', $notes) }}
                                                    </span>
                                                    @if($order->notes)
                                                        <div class="mt-2 text-gray-400 italic">"{{ Str::limit($order->notes, 50) }}"</div>
                                                    @endif
                                                </td>

                                            @elseif(!$isSkipped)
                                                {{-- JIKA KOSONG (AVAILABLE) --}}
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-b border-gray-100"></td>
                                            @endif
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>