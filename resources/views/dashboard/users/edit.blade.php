<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Edit') }}
        </h2>
    </x-slot>

    @can('is-subscribed')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="font-medium leading-tight text-xl mt-0 mb-4">{{ __('User Edit:') }} </h1>
                    
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <x-form method="POST" action="{{ route('users.update', $user->id) }}" >
                        @method('patch')
                        <div class="mt-4">
                            <x-label for="name">{{ __('Fullname') }}</x-label>
                            <x-input type="text" id="name" name="name" value="{{ $user->name }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="email">{{ __('Email') }}</x-label>
                            <x-input type="email" id="email" name="email" value="{{ $user->email }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="phone">{{ __('Phone') }}</x-label>
                            +380<x-input type="text" id="phone" name="phone" value="{{ $user->phone }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="chat_id">{{ __('Telegram Chat ID') }}</x-label>
                            <x-input type="text" id="chat_id" name="chat_id" value="{{ $user->chat_id }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="role">{{ __('Role') }}</x-label>
                            <x-input type="text" id="role" name="role" value="{{ $user->role }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="active">{{ __('Active') }}</x-label>
                            <x-input type="text" id="active" name="active" value="{{ $user->active }}" />
                        </div>
                        
                            <x-input type="hidden" id="id" name="id" value="{{ $user->id }}" />
                        <div class="mt-4">
                            <x-button type="submit">
                                {{ __('Edit') }}
                            </x-button>
                        </div>

                    </x-form>
                
                </div>
            </div>
        </div>
    </div>
    @endcan
</x-app-layout>
