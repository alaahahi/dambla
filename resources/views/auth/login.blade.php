
    <!-- Session Status -->
    <div class="header">
        <div class="logo" style="padding: 15px;position: fixed;z-index: 24;">
            <a href="index.html"><img width="150px" src="images/logo.png" alt="#"></a>
        </div>
    </div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <link rel="stylesheet" href="./style.css">
	<div class="container" onclick="onclick">
	<div class="top"></div>
	<div class="bottom"></div>
	<div class="center">
    <form method="POST" action="{{ route('login') }}" >
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('اسم المستخدم')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
            @if($errors->get('email'))
            <div style="text-align: center;color: red;">اسم المستخدم غير صالح </div>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            @if($errors->get('password'))
            <span style="text-align: center;color: red;">كلمة المرور  غير صالح </span>
            @endif
        </div>

        <!-- Remember Me
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div> -->

        <div class="inline-flex items-center mt-4">
        

            <x-primary-button class="ml-3  banner_main">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>
    </form>
	</div>
	</div>

   

