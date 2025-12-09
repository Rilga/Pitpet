<x-app-layout>
    {{-- Container Utama (Full White Background with subtle pattern if needed) --}}
    <div class="min-h-screen bg-gray-50/50 py-12"
         x-data="bookingForm(
            {{ json_encode($catServices) }},
            {{ json_encode($catDescriptions) }},
            {{ json_encode($catAddons) }},
            {{ json_encode($catAddonDescriptions) }},
            {{ json_encode($dogServices) }},
            {{ json_encode($dogDescriptions) }},
            {{ json_encode($dogAddons) }},
            {{ json_encode($dogAddonDescriptions) }}
        )">
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Judul (Minimalist Center) --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-3">
                    Booking <span class="text-[#2ba6c5]">Grooming</span>
                </h1>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                    Lengkapi data diri dan pilih layanan terbaik untuk anabul kesayangan Anda.
                </p>
            </div>

            {{-- Progress Steps (Clean Line Style) --}}
            <div class="mb-12">
                <div class="flex items-center justify-center w-full max-w-3xl mx-auto">
                    <template x-for="(label, idx) in ['Data Customer', 'Pilih Layanan', 'Jadwal & Bayar']">
                        <div class="flex items-center relative w-full last:w-auto">
                            {{-- Step Circle --}}
                            <div class="relative z-10 flex flex-col items-center cursor-pointer group"
                                 @click="step > idx + 1 ? step = idx + 1 : null">
                                <div class="rounded-full transition-all duration-300 ease-in-out h-10 w-10 flex items-center justify-center text-sm font-bold border-2"
                                     :class="step === idx + 1 
                                        ? 'bg-[#fc5205] border-[#fc5205] text-white shadow-lg shadow-[#fc5205]/30 scale-110' 
                                        : (step > idx + 1 ? 'bg-[#2ba6c5] border-[#2ba6c5] text-white' : 'bg-white border-gray-300 text-gray-400')">
                                    <span x-show="step <= idx + 1" x-text="idx + 1"></span>
                                    <span x-show="step > idx + 1">✓</span> {{-- Checkmark if passed --}}
                                </div>
                                <div class="absolute top-12 w-32 text-center text-xs font-bold uppercase tracking-wider transition-colors duration-300 hidden sm:block"
                                     :class="step === idx + 1 ? 'text-[#fc5205]' : (step > idx + 1 ? 'text-[#2ba6c5]' : 'text-gray-400')"
                                     x-text="label">
                                </div>
                            </div>
                            
                            {{-- Line Connector --}}
                            <div class="flex-auto border-t-2 transition-all duration-500 ease-in-out mx-2 h-1"
                                 :class="step > idx + 1 ? 'border-[#2ba6c5]' : 'border-gray-200'"
                                 x-show="idx !== 2"></div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Form Container (Card Style) --}}
            <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-3xl border border-gray-100 relative">
                
                {{-- Decorative Blob (Optional) --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-[#2ba6c5]/5 rounded-full blur-3xl pointer-events-none"></div>

                <form method="POST" action="{{ route('order.store') }}" class="p-8 md:p-12 relative z-10">
                    @csrf

                    {{-- ================================================= --}}
                    {{-- STEP 1: CUSTOMER INFO (WITH MAP) --}}
                    {{-- ================================================= --}}
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 slide-in-bottom">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-[#fc5205] pl-4">Informasi Pemilik</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="customer_name" required 
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-5 text-gray-700 bg-gray-50/50 transition" 
                                           placeholder="Contoh: Budi Santoso">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">No. WhatsApp</label>
                                    <input type="text" name="customer_phone" required 
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-5 text-gray-700 bg-gray-50/50 transition" 
                                           placeholder="081234567890">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Cari Alamat / Area</label>
                                    <div class="flex gap-2">
                                        <input type="text" id="addressInput" x-model="customerAddress" 
                                               @keydown.enter.prevent="searchLocation()"
                                               class="flex-1 rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-5 text-gray-700 bg-gray-50/50" 
                                               placeholder="Ketik kelurahan/jalan...">
                                        <button type="button" @click="searchLocation()" class="bg-[#2ba6c5] text-white px-5 rounded-xl font-bold hover:bg-[#228ea9] transition shadow-md shadow-[#2ba6c5]/20">
                                            Cari
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-[#fc5205]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Geser <span class="font-bold text-[#fc5205] mx-1">PIN MERAH</span> di peta ke lokasi tepat rumah Anda.
                                    </p>
                                </div>
                            </div>
                            
                            {{-- PETA --}}
                            <div class="rounded-2xl overflow-hidden border-2 border-gray-200 shadow-inner h-[320px] relative">
                                <div id="map" class="w-full h-full z-0"></div>
                                <input type="hidden" name="customer_address" x-model="customerAddress">
                                <input type="hidden" name="distance_km" x-model="distance_km">
                                <input type="hidden" name="transport_fee" x-model="transport_fee">
                            </div>
                        </div>

                        {{-- Estimasi Jarak Box --}}
                        <div class="mt-10 bg-[#f0f9fb] border border-[#2ba6c5]/20 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div class="bg-white p-3 rounded-full shadow-sm text-[#2ba6c5]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#2ba6c5] uppercase tracking-wide">Jarak Tempuh</p>
                                    <p class="text-3xl font-extrabold text-gray-900" x-text="distance_km + ' km'"></p>
                                </div>
                            </div>
                            <div class="text-right border-t sm:border-t-0 sm:border-l border-gray-200 pt-4 sm:pt-0 sm:pl-6 w-full sm:w-auto">
                                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Biaya Transport</p>
                                <p class="text-3xl font-extrabold text-[#fc5205]" x-text="'Rp ' + transport_fee.toLocaleString()"></p>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-end">
                            <button type="button" @click="step = 2" class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-xl shadow-lg text-white bg-[#fc5205] hover:bg-[#e04804] hover:-translate-y-1 transition-all">
                                Lanjut Pilih Layanan →
                            </button>
                        </div>
                    </div>


                    {{-- ================================================= --}}
                    {{-- STEP 2: PETS & SERVICES (CLEAN CARDS) --}}
                    {{-- ================================================= --}}
                    <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300 slide-in-bottom">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-[#fc5205] pl-4">Detail Hewan</h2>

                        {{-- KUCING SECTION --}}
                        <div class="mb-10">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center"><span class="bg-gray-100 p-2 rounded-lg mr-3 text-2xl">🐱</span> Kucing</h3>
                                <button type="button" @click="addCat()" class="text-sm bg-[#eaf6f9] text-[#2ba6c5] px-5 py-2.5 rounded-xl font-bold hover:bg-[#d5eef4] transition border border-transparent hover:border-[#2ba6c5]">+ Tambah Kucing</button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="(cat, i) in cats" :key="cat.id">
                                    <div class="bg-white rounded-2xl border-2 border-gray-100 p-6 relative hover:border-[#2ba6c5]/50 hover:shadow-lg transition group">
                                        <button type="button" @click="removeCat(i)" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Kucing #<span x-text="i+1"></span></h4>
                                        
                                        <div class="relative mb-4">
                                            <select :name="`cats[${i}][service]`" x-model="cat.service" @change="updateCost()" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50 text-sm font-medium">
                                                <option value="">-- Pilih Layanan --</option>
                                                <template x-for="(price, name) in catServices">
                                                    <option :value="name" x-text="`${name} - Rp ${price.toLocaleString()}`"></option>
                                                </template>
                                            </select>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">Add-ons</p>
                                            <div class="space-y-2">
                                                <template x-for="(price, name) in catAddons">
                                                    <label class="flex items-center text-sm cursor-pointer group-addon">
                                                        <input type="checkbox" :name="`cats[${i}][addons][]`" :value="name" @change="toggleCatAddon(i, name)" class="rounded text-[#2ba6c5] border-gray-300 focus:ring-[#2ba6c5] mr-3 w-4 h-4">
                                                        <span class="text-gray-600 group-addon-hover:text-gray-900 transition" x-text="name"></span> 
                                                        <span class="text-[#fc5205] ml-auto font-bold text-xs bg-[#fc5205]/10 px-2 py-0.5 rounded" x-text="'+ Rp '+price.toLocaleString()"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="cats.length === 0" class="col-span-full py-10 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center text-gray-400">
                                    <span class="text-4xl mb-2 grayscale opacity-30">🐱</span>
                                    <span>Belum ada data kucing.</span>
                                </div>
                            </div>
                        </div>

                        {{-- ANJING SECTION --}}
                        <div class="mb-10 pt-10 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center"><span class="bg-gray-100 p-2 rounded-lg mr-3 text-2xl">🐶</span> Anjing</h3>
                                <button type="button" @click="addDog()" class="text-sm bg-[#eaf6f9] text-[#2ba6c5] px-5 py-2.5 rounded-xl font-bold hover:bg-[#d5eef4] transition border border-transparent hover:border-[#2ba6c5]">+ Tambah Anjing</button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="(dog, i) in dogs" :key="dog.id">
                                    <div class="bg-white rounded-2xl border-2 border-gray-100 p-6 relative hover:border-[#2ba6c5]/50 hover:shadow-lg transition group">
                                        <button type="button" @click="removeDog(i)" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <h4 class="font-bold text-gray-900 mb-4 text-lg">Anjing #<span x-text="i+1"></span></h4>

                                        <select :name="`dogs[${i}][service]`" x-model="dog.service" @change="updateCost()" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50 text-sm font-medium mb-3">
                                            <option value="">-- Pilih Layanan --</option>
                                            <template x-for="(sizes, name) in dogServices">
                                                <option :value="name" x-text="name"></option>
                                            </template>
                                        </select>
                                        
                                        <div x-show="dog.service" class="mb-4">
                                            <select :name="`dogs[${i}][size]`" x-model="dog.size" @change="updateCost()" class="block w-full rounded-xl border-[#2ba6c5] bg-[#f0f9fb] text-[#2ba6c5] font-bold shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 text-sm">
                                                <option value="">-- Pilih Ukuran --</option>
                                                <template x-if="dogServices[dog.service]">
                                                    <template x-for="(price, size) in dogServices[dog.service]">
                                                        <option :value="size" x-text="`Size ${size} - Rp ${price.toLocaleString()}`"></option>
                                                    </template>
                                                </template>
                                            </select>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <p class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">Add-ons</p>
                                            <div class="space-y-2">
                                                <template x-for="(val, name) in dogAddons">
                                                    <label class="flex items-center text-sm cursor-pointer group-addon">
                                                        <input type="checkbox" :name="`dogs[${i}][addons][]`" :value="name" @change="toggleDogAddon(i, name)" class="rounded text-[#2ba6c5] border-gray-300 focus:ring-[#2ba6c5] mr-3 w-4 h-4">
                                                        <span class="text-gray-600 group-addon-hover:text-gray-900 transition" x-text="name"></span> 
                                                        <span class="text-[#fc5205] ml-auto font-bold text-xs bg-[#fc5205]/10 px-2 py-0.5 rounded" x-text="typeof val === 'object' ? '(Variatif)' : '+ Rp '+val.toLocaleString()"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="dogs.length === 0" class="col-span-full py-10 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center text-gray-400">
                                    <span class="text-4xl mb-2 grayscale opacity-30">🐶</span>
                                    <span>Belum ada data anjing.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-between items-center">
                            <button type="button" @click="step = 1" class="text-gray-400 hover:text-gray-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-100 transition">← Kembali</button>
                            <button type="button" @click="step = 3" class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-xl shadow-lg text-white bg-[#fc5205] hover:bg-[#e04804] hover:-translate-y-1 transition-all">
                                Lanjut Jadwal →
                            </button>
                        </div>
                    </div>


                    {{-- ================================================= --}}
                    {{-- STEP 3: JADWAL & SUMMARY (CLEAN GRID) --}}
                    {{-- ================================================= --}}
                    <div x-show="step === 3" style="display: none;" x-transition:enter="transition ease-out duration-300 slide-in-bottom">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-[#fc5205] pl-4">Jadwal & Konfirmasi</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            <div class="lg:col-span-2 space-y-8">
                                {{-- Date Picker --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Tanggal</label>
                                    <input type="date" name="date" x-model="selectedDate" @change="fetchSlots()" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-5 text-gray-700 bg-gray-50/50">
                                </div>

                                {{-- Time Grid --}}
                                <div x-show="selectedDate">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Jam Mulai</label>
                                    
                                    <div x-show="isLoadingSlots" class="text-sm text-[#2ba6c5] flex items-center mb-4 bg-[#f0f9fb] p-3 rounded-lg border border-[#2ba6c5]/20">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Sedang mengecek ketersediaan...
                                    </div>

                                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3" x-show="!isLoadingSlots">
                                        <template x-for="slot in slots" :key="slot.time">
                                            <button type="button" @click="selectTime(slot)" :disabled="slot.is_full"
                                                class="border-2 rounded-xl p-3 text-center transition relative overflow-hidden focus:outline-none h-20 flex flex-col items-center justify-center group"
                                                :class="{
                                                    'bg-red-50 border-red-100 cursor-not-allowed opacity-50 grayscale': slot.is_full,
                                                    'bg-[#2ba6c5] border-[#2ba6c5] text-white shadow-md scale-[1.02]': selectedTime === slot.time,
                                                    'bg-white border-gray-200 hover:border-[#2ba6c5] hover:text-[#2ba6c5]': !slot.is_full && selectedTime !== slot.time
                                                }">
                                                <span class="block text-lg font-extrabold" x-text="slot.time"></span>
                                                <span class="text-[10px] uppercase font-bold mt-1 tracking-wider" 
                                                      :class="slot.is_full ? 'text-red-500' : (selectedTime === slot.time ? 'text-white/80' : 'text-gray-400')" 
                                                      x-text="slot.label"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <input type="hidden" name="start_time" x-model="selectedTime" required>
                                    <p x-show="!selectedTime && !isLoadingSlots" class="text-xs text-red-500 mt-2 font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Wajib pilih salah satu jam.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3.5 px-5 text-gray-700 bg-gray-50/50" placeholder="Misal: Anjing saya takut suara hair dryer..."></textarea>
                                </div>
                            </div>

                            {{-- Sidebar Summary (Floating Style) --}}
                            <div class="lg:col-span-1">
                                <div class="bg-gray-900 rounded-3xl shadow-2xl p-8 text-white sticky top-24 border border-gray-800 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#2ba6c5] rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
                                    
                                    <h3 class="text-xl font-bold mb-6 border-b border-gray-700 pb-4">Ringkasan Biaya</h3>
                                    
                                    <div class="space-y-4 mb-8 text-sm text-gray-300">
                                        <div class="flex justify-between items-center">
                                            <span>Jasa Grooming</span>
                                            <span class="font-mono text-white text-base" x-text="'Rp ' + (total - transport_fee).toLocaleString()"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span>Transport (<span x-text="distance_km"></span> km)</span>
                                            <span class="font-mono text-white text-base" x-text="'Rp ' + transport_fee.toLocaleString()"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-gray-700 pt-6">
                                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Total Estimasi</p>
                                        <p class="text-4xl font-extrabold text-[#2ba6c5] mt-2" x-text="'Rp ' + total.toLocaleString()"></p>
                                    </div>
                                    
                                    <button type="submit" :disabled="!selectedTime" 
                                            :class="!selectedTime ? 'opacity-50 cursor-not-allowed bg-gray-700' : 'bg-[#fc5205] hover:bg-[#e04804] hover:-translate-y-1 shadow-lg shadow-[#fc5205]/40'" 
                                            class="mt-8 w-full block text-center text-white font-bold py-4 rounded-xl transform transition-all duration-300">
                                        Booking Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10">
                            <button type="button" @click="step = 2" class="text-gray-400 hover:text-gray-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-100 transition">← Kembali ke Layanan</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Script AlpineJS & Leaflet (Sama seperti sebelumnya) --}}
    {{-- PASTIKAN SCRIPT INI ADA DI BAGIAN BAWAH SEPERTI SEBELUMNYA --}}
    <script>
        // ... (Copy Paste Script AlpineJS dan Leaflet dari jawaban sebelumnya) ...
        // Agar tidak kepanjangan, saya asumsikan Anda menggunakan script logic yang sama.
        // HANYA BAGIAN HTML YANG DIUBAH DI ATAS.
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingForm', (
                catServices, catDescriptions, catAddons, catAddonDescriptions,
                dogServices, dogDescriptions, dogAddons, dogAddonDescriptions
            ) => ({
                step: 1,
                // Data Master
                catServices, catDescriptions, catAddons, catAddonDescriptions,
                dogServices, dogDescriptions, dogAddons, dogAddonDescriptions,

                // User Data
                customerAddress: '',
                distance_km: 0,
                transport_fee: 0,
                
                // KOORDINAT TOKO
                clinicLat: -6.302146902500242, 
                clinicLng: 106.78158629264237,

                // Peta & Marker
                map: null,
                marker: null,

                // Data Lain
                cats: [], dogs: [], total: 0,
                selectedDate: '', selectedTime: '', slots: [], isLoadingSlots: false,

                init() {
                    this.$nextTick(() => { this.initMap(); });
                },

                initMap() {
                    // 1. Render Peta (Default ke Toko)
                    this.map = L.map('map').setView([this.clinicLat, this.clinicLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(this.map);
                    
                    L.marker([this.clinicLat, this.clinicLng]).addTo(this.map).bindPopup("<b>PitPet Clinic</b><br>Lokasi Kami").openPopup();

                    let startLat = this.clinicLat + 0.005; 
                    let startLng = this.clinicLng + 0.005;

                    var redIcon = new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                    });

                    this.marker = L.marker([startLat, startLng], { draggable: true, icon: redIcon }).addTo(this.map).bindPopup("Geser saya ke rumah Anda!");

                    // Event saat marker digeser (dragend)
                    this.marker.on('dragend', (e) => {
                        let position = this.marker.getLatLng();
                        this.reverseGeocode(position.lat, position.lng); // <--- BARU: Cari Alamat
                        this.calculateRouteDistance(position.lat, position.lng);
                    });
                },

                async reverseGeocode(lat, lng) {
                    try {
                        let url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
                        let res = await fetch(url);
                        let data = await res.json();
                        
                        if (data && data.display_name) {
                            // Update x-model="customerAddress" dengan alamat yang ditemukan
                            this.customerAddress = data.display_name; 
                        } else {
                            this.customerAddress = `Lokasi tidak terdeteksi (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                        }
                    } catch(e) {
                        this.customerAddress = "Gagal mengambil alamat. Cek koneksi.";
                    }
                },

                async searchLocation() {
                    if(this.customerAddress.length < 3) return;
                    
                    try {
                        let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.customerAddress)}&limit=1`;
                        let res = await fetch(url);
                        let data = await res.json();
                        
                        if(data && data.length > 0) {
                            let lat = parseFloat(data[0].lat);
                            let lon = parseFloat(data[0].lon);
                            
                            this.map.setView([lat, lon], 16);
                            this.marker.setLatLng([lat, lon]);
                            
                            // Ambil alamat yang lebih rapi dari Nominatim setelah pencarian berhasil
                            this.reverseGeocode(lat, lon); 
                            this.calculateRouteDistance(lat, lon);
                        } else {
                            Swal.fire('Lokasi tidak ditemukan', 'Coba nama jalan atau kelurahan yang lebih umum.', 'warning');
                        }
                    } catch(e) { console.error(e); }
                },

                async calculateRouteDistance(userLat, userLng) {
                    try {
                        let url = `https://router.project-osrm.org/route/v1/driving/${this.clinicLng},${this.clinicLat};${userLng},${userLat}?overview=false`;
                        let res = await fetch(url);
                        let data = await res.json();
                        if(data.code === 'Ok') {
                            let meters = data.routes[0].distance;
                            this.distance_km = parseFloat((meters / 1000).toFixed(1));
                            this.calculateTransportFee();
                        } else { this.distance_km = 0; }
                    } catch(e) { console.error('OSRM Error', e); } finally { this.updateCost(); }
                },

                calculateTransportFee() {
                    let d = this.distance_km;
                    if (d <= 5) this.transport_fee = 0;
                    else if (d <= 10) this.transport_fee = 15000;
                    else if (d <= 20) this.transport_fee = 30000;
                    else if (d <= 30) this.transport_fee = 50000;
                    else this.transport_fee = 80000;
                },

                addCat() { this.cats.push({ id: Date.now(), service: '', addons: [] }); this.updateCost(); },
                removeCat(i) { this.cats.splice(i, 1); this.updateCost(); },
                toggleCatAddon(i, val) { let idx = this.cats[i].addons.indexOf(val); if(idx>-1)this.cats[i].addons.splice(idx,1);else this.cats[i].addons.push(val); this.updateCost(); },
                
                addDog() { this.dogs.push({ id: Date.now(), service: '', size: '', addons: [] }); this.updateCost(); },
                removeDog(i) { this.dogs.splice(i, 1); this.updateCost(); },
                toggleDogAddon(i, val) { let idx = this.dogs[i].addons.indexOf(val); if(idx>-1)this.dogs[i].addons.splice(idx,1);else this.dogs[i].addons.push(val); this.updateCost(); },

                updateCost() {
                    let sub = 0;
                    this.cats.forEach(c => { if(c.service) sub += (this.catServices[c.service]||0); c.addons.forEach(a=>sub+=(this.catAddons[a]||0)); });
                    this.dogs.forEach(d => { if(d.service && d.size) sub += (this.dogServices[d.service][d.size]||0); d.addons.forEach(a=>{ let p=this.dogAddons[a]; if(typeof p==='object'&&d.size)sub+=p[d.size];else if(typeof p==='number')sub+=p; }); });
                    this.total = sub + this.transport_fee;
                },

                async fetchSlots() {
                    if (!this.selectedDate) return;
                    this.isLoadingSlots = true; this.slots = []; this.selectedTime = '';
                    try { let r = await fetch(`{{ route('order.slots') }}?date=${this.selectedDate}`); this.slots = await r.json(); } catch(e){} finally{ this.isLoadingSlots=false; }
                },
                selectTime(slot) { if(!slot.is_full) this.selectedTime = slot.time; }
            }));
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Booking Berhasil!', text: "Data booking Anda telah kami terima.", icon: 'success',
                    confirmButtonText: 'Oke', confirmButtonColor: '#10B981', allowOutsideClick: false,
                }).then((r) => { if(r.isConfirmed) window.location.href = "{{ route('welcome') }}"; });
            @endif
            @if($errors->any())
                Swal.fire({ title: 'Gagal Submit', text: 'Cek inputan Anda', icon: 'error' });
            @endif
        });
    </script>
</x-app-layout>