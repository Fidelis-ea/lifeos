<x-guest-layout>
    <div class="bg-brutalist-pink border-b-4 border-brutalist-primary -mx-8 -mt-8 px-8 py-3 rounded-t-[6px] mb-6 flex justify-between items-center font-mono font-bold text-xs">
        <span>CREATE CHARACTER</span>
        <span>🛡️ NEW_USER</span>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5 font-mono text-sm">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-bold mb-1 uppercase">FULL NAME</label>
            <input id="name" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="text" name="name" :value="old('name')" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold mb-1 uppercase">EMAIL ADDRESS</label>
            <input id="email" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="email" name="email" :value="old('email')" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-bold mb-1 uppercase">PASSWORD</label>
            <input id="password" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-bold mb-1 uppercase">CONFIRM PASSWORD</label>
            <input id="password_confirmation" class="block w-full border-4 border-brutalist-primary rounded-[8px] p-2 focus:ring-0 focus:outline-none" type="password" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-bold text-brutalist-red" />
        </div>

        <div>
            <button type="submit" class="w-full py-3.5 btn-brutal-primary text-sm shadow-brutal bg-brutalist-green hover:bg-green-400">
                🛡️ REGISTER ACCOUNT
            </button>
        </div>

        <div class="text-center pt-2 border-t-2 border-dashed border-brutalist-primary">
            <span class="text-xs text-gray-500">Already registered?</span>
            <a href="{{ route('login') }}" class="underline text-xs font-bold ml-1 hover:text-brutalist-yellow">LOGIN HERE</a>
        </div>
    </form>
</x-guest-layout>
