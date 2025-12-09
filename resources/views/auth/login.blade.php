<x-guest-layout>
    {{-- Header Visual (Hanya Tampilan, Tidak Mengubah Logika) --}}
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-wider">
            Administrator <span class="text-[#2ba6c5]">Portal</span>
        </h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">
            Silakan masuk untuk mengelola sistem.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            {{-- Design Update: Label Bold & Uppercase --}}
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-bold text-xs uppercase tracking-wide" />
            
            {{-- Design Update: Input Rounded, Teal Focus Ring, Background Gray-50 --}}
            <x-text-input id="email" 
                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50 focus:bg-white transition" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required autofocus autocomplete="username" 
                          placeholder="admin@pitpet.id" />
            
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-bold text-xs uppercase tracking-wide" />

            <x-text-input id="password" 
                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#2ba6c5] focus:ring focus:ring-[#2ba6c5]/20 py-3 px-4 bg-gray-50 focus:bg-white transition"
                          type="password"
                          name="password"
                          required autocomplete="current-password" 
                          placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-6">
            <label for="remember_me" class="inline-flex items-center">
                {{-- Design Update: Checkbox Teal Color --}}
                <input id="remember_me" type="checkbox" 
                       class="rounded border-gray-300 text-[#2ba6c5] shadow-sm focus:ring-[#2ba6c5]" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-8">
            @if (Route::has('password.request'))
                {{-- Design Update: Link Hover Orange --}}
                <a class="underline text-sm text-gray-500 hover:text-[#fc5205] transition duration-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#fc5205]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            {{-- Design Update: Button Orange, Shadow, Rounded --}}
            <x-primary-button class="ms-3 bg-[#fc5205] hover:bg-[#e04804] focus:bg-[#e04804] active:bg-[#c94003] border-transparent rounded-xl py-3 px-6 shadow-lg shadow-[#fc5205]/30 transition-all transform hover:-translate-y-0.5">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>