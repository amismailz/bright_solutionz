<x-filament-widgets::widget class="p-6 bg-white rounded-xl shadow-md">
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-3">
                <x-filament::icon name="user-circle" class="w-5 h-5 text-primary-600" />
                <span class="text-xl font-semibold text-gray-800">{{ __('Account Overview') }}</span>
            </div>
        </x-slot>

        <div class="flex items-center gap-4 mb-6">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=0D8ABC&color=fff"
                 alt="{{ $user->name }}"
                 class="w-16 h-16 rounded-full border shadow-sm" />

            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <br>

        <x-filament::grid cols="1" sm="2" class="gap-6">
            <x-filament::card>
                <h3 class="text-sm font-medium text-gray-500 mb-2">{{ __('Role') }}</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->getRoleNames() as $role)
                        <x-filament::badge color="primary">{{ \App\Enums\RoleTypeEnum::labels()[$role] }}</x-filament::badge>
                    @empty
                        <span class="text-sm text-gray-400">{{ __('No Role') }}</span>
                    @endforelse
                </div>
            </x-filament::card>

            {{-- <x-filament::card>
                <h3 class="text-sm font-medium text-gray-500 mb-2">{{ __('Status') }}</h3>
                <p class="text-sm text-gray-800">
                    {{ \App\Enums\StatusEnum::from($user->status)->label() ?? __('Not recorded') }}
                </p>
            </x-filament::card> --}}
        </x-filament::grid>
    </x-filament::section>
</x-filament-widgets::widget>
