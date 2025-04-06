<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0fbcf9 0%, #554dde 100%);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full flex">
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

        <!-- Right Side - Registration Form -->
        <div class="w-full md:w-1/2 p-8 md:p-10">
            <div class="max-w-md mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Register</h2>
                <p class="text-gray-500 mb-6">Create your account to get started.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- First Name -->
                        <div>
                            <x-input-label for="firstname" :value="__('First Name')" class="block text-gray-600 mb-1 text-sm" />
                            <x-text-input id="firstname"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                                type="text" name="firstname" :value="old('firstname')" required autofocus
                                autocomplete="firstname" placeholder="John" />
                            <x-input-error :messages="$errors->get('firstname')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <x-input-label for="middlename" :value="__('Middle')"
                                class="block text-gray-600 mb-1 text-sm" />
                            <x-text-input id="middlename"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                                type="text" name="middlename" :value="old('middlename')" autocomplete="middlename"
                                placeholder="M" />
                            <x-input-error :messages="$errors->get('middlename')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="lastname" :value="__('Last Name')" class="block text-gray-600 mb-1 text-sm" />
                            <x-text-input id="lastname"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                                type="text" name="lastname" :value="old('lastname')" required autocomplete="lastname"
                                placeholder="Doe" />
                            <x-input-error :messages="$errors->get('lastname')" class="mt-1 text-red-500 text-xs" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" class="block text-gray-600 mb-1 text-sm" />
                        <x-text-input id="email"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                            type="email" name="email" :value="old('email')" required autocomplete="username"
                            placeholder="example@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <x-input-label for="role" :value="__('Select Role')" class="block text-gray-600 mb-1 text-sm" />
                        <div class="relative">
                            <select id="role" name="role"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm appearance-none cursor-pointer">
                                <option value="" disabled selected>🔽 Choose a Role</option>
                                <option value="Vendor">📦 Vendor</option>
                                <option value="Driver">🚛 Driver</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="block text-gray-600 mb-1 text-sm" />
                            <x-text-input id="password"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                                type="password" name="password" required autocomplete="new-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm')"
                                class="block text-gray-600 mb-1 text-sm" />
                            <x-text-input id="password_confirmation"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-colors text-sm"
                                type="password" name="password_confirmation" required autocomplete="new-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                        </div>
                    </div>

                    <!-- Submit and Login Link -->
                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-blue-500">
                            {{ __('Already registered? Login here') }}
                        </a>
                        <button type="submit"
                            class="bg-[#554dde] text-white py-2 px-6 rounded-lg hover:bg-[#4437c5] transition-colors text-sm">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
