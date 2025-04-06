<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0fbcf9 0%, #554dde 100%);
        }

        body {
            background-image: url('{{ asset('assets/images/bus-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh; /* Ensure the background covers the entire height of the page */
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="bg-white bg-opacity-65 backdrop-blur-lg  rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full flex">
        <!-- Left Side - Welcome Section -->
        <div class="gradient-bg w-1/2 p-12 hidden md:block relative">
            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <div class="mt-32">
                <h1 class="text-white text-5xl font-bold leading-tight">NexFleet Dynamics</h1>
            </div>
            <div class="absolute bottom-0 right-0 w-full h-full z-0 opacity-20">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="white"
                        d="M45.3,-59.1C58.9,-51.1,70.3,-37.4,75.2,-21.3C80.1,-5.2,78.5,13.2,70.8,27.7C63.1,42.2,49.3,52.7,34.3,58.4C19.3,64.1,3.2,64.9,-13.4,62.5C-30,60.1,-47.1,54.5,-58.9,42.8C-70.7,31.1,-77.2,13.3,-76.1,-3.8C-75,-20.9,-66.3,-37.3,-53.3,-45.4C-40.3,-53.6,-23,-53.5,-6.9,-51.8C9.2,-50.1,31.7,-67.1,45.3,-59.1Z"
                        transform="translate(100 100)" />
                </svg>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <div class="max-w-md mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Login</h2>
                <p class="text-gray-800 mb-8">Welcome back! Please login to your account.</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-gray-600" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')" class="block text-gray-600 mb-2" />
                        <x-text-input id="email"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="username@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="block text-gray-600 mb-2" />
                        <x-text-input id="password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        {{-- <label class="flex items-center gap-2 text-sm">
                            <input id="remember_me" type="checkbox"
                                class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                name="remember">
                            <span class="text-gray-600">{{ __('Remember me') }}</span>
                        </label> --}}
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-gray-900 hover:text-blue-500">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-[#554dde] text-white py-3 rounded-lg hover:bg-[#4437c5] transition-colors">
                        {{ __('Log in') }}
                    </button>

                    <!-- Sign Up Link -->
                    <p class="text-center mt-8 text-gray-900">
                        New User?
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:text-blue-700 font-medium">Signup</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
