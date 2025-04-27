<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome to Edit User Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('user.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        {{-- Name --}}
                        
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="mt-1 block w-full" 
                                :value="old('name', $user->name)" 
                                required 
                                autofocus 
                                autocomplete="name" 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Hanya kalau admin --}}
                        @if (Auth::user()->is_admin)
                            {{-- Email --}}
                            <div class="mb-6">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    class="mt-1 block w-full" 
                                    :value="old('email', $user->email)" 
                                    required 
                                    autocomplete="email" 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            {{-- Password --}}
                            <div class="mb-6">
                                <x-input-label for="password" :value="__('Password (leave blank if not changing)')" />
                                <x-text-input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    class="mt-1 block w-full" 
                                    autocomplete="new-password"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <x-cancel-button href="{{ route('user.index') }}" />
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
