<x-app-layout>
    <div class="flex justify-center items-center min-h-screen bg-gray-100 p-4">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden w-full max-w-4xl">
            <div class="p-8 md:p-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center md:text-left">User Management</h2>

                <form method="POST" action="{{ route('usermanagement.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- First Name -->
                        <div class="flex flex-col">
                            <x-input-label for="firstname" :value="__('First Name')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="firstname"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="text" name="firstname" :value="old('firstname')" required autofocus
                                autocomplete="given-name" placeholder="John" />
                            <x-input-error :messages="$errors->get('firstname')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Middle Name -->
                        <div class="flex flex-col">
                            <x-input-label for="middlename" :value="__('Middle')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="middlename"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="text" name="middlename" :value="old('middlename')" autocomplete="additional-name"
                                placeholder="M" />
                            <x-input-error :messages="$errors->get('middlename')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Last Name -->
                        <div class="flex flex-col">
                            <x-input-label for="lastname" :value="__('Last Name')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="lastname"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="text" name="lastname" :value="old('lastname')" required autocomplete="family-name"
                                placeholder="Doe" />
                            <x-input-error :messages="$errors->get('lastname')" class="mt-1 text-red-500 text-xs" />
                        </div>
                    </div>

                    <!-- Email and Role Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="flex flex-col">
                            <x-input-label for="email" :value="__('Email')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="email"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="email" name="email" :value="old('email')" required autocomplete="username"
                                placeholder="example@gmail.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Role Selection -->
                        <div class="flex flex-col">
                            <x-input-label for="role" :value="__('Select Role')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <div class="relative">
                                <select id="role" name="role" required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm appearance-none cursor-pointer">
                                    <option value="" disabled selected>🔽 Choose a Role</option>
                                    <option value="Admin">👑 Admin</option>
                                    <option value="Vendor">📦 Vendor</option>
                                    <option value="Driver">🚛 Driver</option>
                                    <option value="Staff">👤 Secretary</option>
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
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="flex flex-col">
                            <x-input-label for="password" :value="__('Password')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="password"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="password" name="password" required autocomplete="new-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="flex flex-col">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                                class="text-gray-600 mb-2 text-sm font-medium" />
                            <x-text-input id="password_confirmation"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all duration-200 text-sm"
                                type="password" name="password_confirmation" required autocomplete="new-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div
                        class="flex flex-col md:flex-row items-center justify-end pt-4 space-y-4 md:space-y-0 md:space-x-4">
                        <a href="{{ route('usermanagement.index') }}"
                            class="w-full md:w-auto bg-gray-200 text-gray-700 py-2.5 px-8 rounded-lg hover:bg-gray-300 transition-all duration-200 text-sm font-medium text-center">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="w-full md:w-auto bg-[#554dde] text-white py-2.5 px-8 rounded-lg hover:bg-[#4437c5] transition-all duration-200 text-sm font-medium">
                            {{ __('Add New User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
