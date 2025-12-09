<nav class="bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        {{-- LOGO --}}
        <a href="/">
            {{-- Ganti 'logo.png' dengan nama file logo Anda yang sebenarnya --}}
            <img src="{{ asset('asset/logo_pitpet.png') }}" alt="PitPet Logo" class="h-12 w-auto hover:opacity-50 transition">
        </a>

        {{-- MENU KANAN --}}
        <div class="flex items-center space-x-6">
            
            {{-- Link Biasa --}}
            <a href="/order/create" class="text-sm font-bold text-gray-500 hover:text-[#2ba6c5] transition duration-300">
                Pesan Grooming
            </a>

            {{-- Tombol Login (Aksen Orange) --}}
            <a href="/login" class="px-6 py-2.5 bg-[#fc5205] text-white text-sm font-bold rounded-full shadow-lg shadow-[#fc5205]/20 hover:bg-[#e04804] hover:-translate-y-0.5 transition duration-300">
                Login
            </a>
            
        </div>
    </div>
</nav>