<x-app-layout>
    <div class="py-4 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Dashboard --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Dashboard <span class="text-[#2ba6c5]">Admin</span>
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">
                        Pantau booking masuk dan tugaskan groomer dengan mudah.
                    </p>
                </div>
                
                {{-- Statistik Ringkas & Filter (UPDATED) --}}
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    
                    {{-- 1. Kotak Statistik --}}
                    <div class="flex gap-4">
                        <div class="bg-white px-6 py-2 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase">Pending</span>
                            <span class="text-xl font-bold text-[#fc5205]">{{ $counts['pending'] ?? 0 }}</span>
                        </div>
                        <div class="bg-white px-6 py-2 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase">Confirmed</span>
                            <span class="text-xl font-bold text-[#2ba6c5]">{{ $counts['confirmed'] ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- 2. Dropdown Filter --}}
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <label for="status-filter" class="text-xs font-bold text-gray-500 uppercase">Filter:</label>
                        <select name="status" id="status-filter" onchange="this.form.submit()" 
                                class="rounded-xl border-gray-300 shadow-sm focus:border-[#fc5205] focus:ring focus:ring-[#fc5205]/20 py-2 text-sm font-medium bg-white">
                            <option value="all" {{ $filterStatus === 'all' || !$filterStatus ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ $filterStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $filterStatus === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $filterStatus === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $filterStatus === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="rejected" {{ $filterStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Tabel Booking --}}
            <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">ID & Waktu</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Groomer</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-5 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($orders as $order)
                            <tr class="hover:bg-[#f0f9fb]/30 transition duration-200">
                                {{-- ID & Waktu --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-lg bg-gray-50 text-gray-500 mr-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-mono text-[#2ba6c5] font-bold">#{{ substr($order->id . $order->created_at, 0, 6) }}</div>
                                            <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->time_slot }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $order->customer_phone }}
                                    </div>
                                </td>

                                {{-- Groomer --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->groomer_id && $order->groomer)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#2ba6c5]/10 text-[#2ba6c5] border border-[#2ba6c5]/20">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $order->groomer->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-200">
                                            Belum ditugaskan
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'completed' => 'bg-green-50 text-green-700 border-green-200',
                                            'cancelled', 'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-600 border-gray-200'
                                        };
                                        $statusLabel = match($order->status) {
                                            'pending' => 'Menunggu',
                                            'confirmed' => 'Diterima',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Batal',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($order->status)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Total --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.order.edit', $order->id) }}" 
                                       class="inline-flex items-center text-[#fc5205] hover:text-[#e04804] font-bold hover:bg-[#fc5205]/10 px-3 py-1.5 rounded-lg transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    Belum ada data booking.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>