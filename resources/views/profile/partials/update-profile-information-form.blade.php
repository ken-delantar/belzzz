<section class="p-6 bg-white rounded-lg">
    <!-- Verification Form -->
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="mb-4">
        @csrf
        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
            Resend Verification Email
        </button>
    </form>

    <!-- Profile Update Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-5">
                <div>
                    <label for="firstname" class="block text-sm font-medium text-gray-700">First Name<span
                            class="text-red-500">*</span></label>
                    <input id="firstname" name="firstname" type="text"
                        value="{{ old('firstname', Auth::user()->vendor->firstname) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('firstname') border-red-500 @enderror"
                        required>
                    @error('firstname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                    <input id="middlename" name="middlename" type="text"
                        value="{{ old('middlename', Auth::user()->vendor->middlename) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('middlename') border-red-500 @enderror">
                    @error('middlename')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name<span
                            class="text-red-500">*</span></label>
                    <input id="lastname" name="lastname" type="text"
                        value="{{ old('lastname', Auth::user()->vendor->lastname) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('lastname') border-red-500 @enderror"
                        required>
                    @error('lastname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email<span
                            class="text-red-500">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input id="contact_info" name="contact_info" type="tel"
                        value="{{ old('contact_info', Auth::user()->vendor->contact_info) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('contact_info') border-red-500 @enderror">
                    @error('contact_info')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input id="address" name="address" type="text"
                        value="{{ old('address', Auth::user()->vendor->address) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Hidden Role Input and Submit Button -->
        <div class="mt-6 flex justify-end space-x-4">
            <input type="hidden" name="role" value="{{ Auth::user()->role }}">
            <button type="submit"
                class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                Save Changes
            </button>
        </div>
    </form>
</section>
