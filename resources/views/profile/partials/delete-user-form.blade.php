<section class="space-y-6">
    {{-- <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header> --}}

    <div class="flex flex-col gap-2" style="margin-top: 20px">
        <x-primary-button id="toggle-profile-update" class="text-sm px-4 py-2 w-full flex items-center justify-center">
            {{ __('Update Profile') }}
        </x-primary-button>

        <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-password')"
            class="text-sm px-4 py-2 w-full flex items-center justify-center">
            {{ __('Update Password') }}
        </x-secondary-button>

        <!-- Delete Account Button -->
        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="text-sm px-4 py-2 w-full flex items-center justify-center">
            {{ __('Delete Account') }}
        </x-danger-button>
    </div>



    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>


    <x-modal name="update-password" :show="$errors->updatePassword->isNotEmpty()" focusable>
        <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('put')

            <!-- Modal Header -->
            <header class="mb-4">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Update Password') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>

            <!-- Current Password -->
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                    class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <!-- Confirm New Password -->
            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button>
                    {{ __('Save') }}
                </x-primary-button>
            </div>

            <!-- Success Message -->
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 text-right mt-2">
                    {{ __('Saved.') }}
                </p>
            @endif
        </form>
    </x-modal>



</section>
