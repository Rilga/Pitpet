@php use Carbon\Carbon; use Illuminate\Support\Str; @endphp
<x-app-layout>
    <div class="py-4 bg-gray-50 min-h-screen print:bg-white print:py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header & Filter Tanggal --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 print:hidden">
                {{-- (Bagian Header Anda yang berfungsi) --}}
            </div>
            
            {{-- LOOPING PER GROOMER (MENGGUNAKAN DATA YANG SUDAH DIPROSES) --}}
            <div class="space-y-12 print:space-y-0 print:block">
                {{-- UBAH: Gunakan scheduleData yang sudah diproses di Controller --}}
                @foreach($scheduleData as $data)
                    @php 
                        $groomer = $data['groomer'];
                        $schedule = $data['schedule'];
                    @endphp
                    
                    {{-- Container Kertas --}}
                    <div class="bg-white shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-200 overflow-hidden print:shadow-none print:border-0 print:break-after-page">
                        
                        {{-- Header Kop Surat Groomer --}}
                        <div class="bg-[#2ba6c5] px-6 py-4 flex justify-between items-center print:bg-white print:border-b-2 print:border-gray-800 print:px-0">
                            {{-- ... (Header code) ... --}}
                        </div>

                        {{-- Tabel Jadwal --}}
                        <div class="p-0">
                            <table class="min-w-full divide-y divide-gray-200 border-collapse">
                                {{-- THEAD UNCHANGED --}}
                                <tbody class="bg-white divide-y divide-gray-200">
                                    
                                    @foreach($schedule as $slot)
                                        @php
                                            $order = $slot['order'];
                                            $rowspan = $slot['rowspan'] ?? 1;
                                            $isBooked = $slot['type'] === 'booked';
                                            $isSkipped = $slot['type'] === 'skipped';
                                        @endphp

                                        @continue($isSkipped)

                                        <tr class="transition
                                            @if($isBooked) bg-blue-50/80 print:bg-gray-100 @else hover:bg-gray-50 @endif
                                        ">
                                            
                                            {{-- Kolom JAM (Selalu Muncul) --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold text-gray-700 border-r border-gray-200 border-b border-gray-100">
                                                {{ $slot['time'] }}
                                            </td>

                                            {{-- Logic Tampilan Data --}}
                                            @if($isBooked)
                                                {{-- JIKA ADA BOOKING BARU MULAI --}}
                                                <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200 font-bold align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    <div class="text-[#2ba6c5] mb-1 text-xs uppercase tracking-wider">Booked</div>
                                                    {{ $order['customer_name'] }}
                                                    <div class="text-xs text-gray-500 font-normal mt-1">{{ $order['customer_phone'] }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600 border-r border-gray-200 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    {{ $order['customer_address'] }}
                                                </td>
                                                
                                                {{-- KOLOM JENIS GROOMING (MENGGUNAKAN DATA ARRAY BARU) --}}
                                                <td class="px-4 py-3 text-xs text-gray-600 border-r border-gray-200 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    <ul class="list-disc list-inside space-y-1">
                                                        @foreach($slot['services']['main'] as $mainService)
                                                            <li class="font-medium text-gray-700">
                                                                {{ $mainService['service_name'] }}
                                                                @if(isset($mainService['dog_size'])) <span class="font-normal text-gray-400">(Size {{ $mainService['dog_size'] }})</span> @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    
                                                    @if($slot['services']['addons'])
                                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                                            <span class="text-gray-500 font-bold block mb-1">Add-ons:</span>
                                                            <div class="text-gray-700 italic text-[11px] leading-tight block">
                                                                {{ $slot['services']['addons'] }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                
                                                {{-- KOLOM KETERANGAN --}}
                                                <td class="px-4 py-3 text-xs text-gray-600 align-top border-b border-gray-200" rowspan="{{ $rowspan }}">
                                                    <span class="inline-block bg-white border border-[#fc5205] text-[#fc5205] px-2 py-1 rounded font-bold shadow-sm">
                                                        {{ $slot['services']['notes'] }}
                                                    </span>
                                                    @if(isset($order['notes']))
                                                        <div class="mt-2 text-gray-400 italic">"{{ Str::limit($order['notes'], 50) }}"</div>
                                                    @endif
                                                </td>

                                            @else
                                                {{-- JIKA KOSONG (AVAILABLE) --}}
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-r border-gray-200 border-b border-gray-100"></td>
                                                <td class="border-b border-gray-100"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
