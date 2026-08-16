<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 font-mono text-sm font-bold text-brutalist-green" :status="session('status')" />

    <div class="bg-brutalist-yellow border-b-4 border-brutalist-primary -mx-8 -mt-8 px-8 py-3 rounded-t-[6px] mb-6 flex justify-between items-center font-mono font-bold text-xs">
        <span>WELCOME BACK</span>
        <span>🔑 USER_LOGIN</span>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5 font-mono text-sm">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold mb-1 uppercase">EMAIL ADDRESS</label>
            <input id="email" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-bold mb-1 uppercase">PASSWORD</label>
            <input id="password" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded border-4 border-brutalist-primary text-brutalist-primary focus:ring-0" name="remember">
                <span class="ms-2 text-xs font-bold">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-500 hover:text-brutalist-primary font-bold" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full py-3.5 btn-brutal-primary text-sm shadow-brutal">
                🔓 LOGIN ACCOUNT
            </button>
        </div>
        
        <div class="text-center pt-2 border-t-2 border-dashed border-brutalist-primary">
            <span class="text-xs text-gray-500">Need an account?</span>
            <a href="{{ route('register') }}" class="underline text-xs font-bold ml-1 hover:text-brutalist-yellow">REGISTER HERE</a>
        </div>
    </form>
</x-guest-layout>
