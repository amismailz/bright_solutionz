@foreach ($groups as $groupName => $factors)
    <x-filament::section heading="{{ $groupName }}" class="mb-6 w-full">
        <div class="w-full overflow-x-auto rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-800 text-xs font-medium text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-3 py-3 text-start">{{ __('Factor') }}</th>
                    <th class="px-3 py-3 text-start">{{ __('Value') }}</th>
                    <th class="px-3 py-3 text-start">{{ __('Description') }}</th>
                    <th class="px-3 py-3 text-start">{{ __('Created at') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                @foreach ($factors as $factor)
                    <tr>
                        <td class="px-3 py-4 text-start align-top">
                            {{ $factor->factor?->name }}
                        </td>
                        <td class="px-3 py-4 text-start align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $factor->value === 'yes'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ __($factor->value) }}
                                </span>
                        </td>
                        <td class="px-3 py-4 text-start align-top">
                            {{ $factor->description }}
                        </td>
                        <td class="px-3 py-4 text-start align-top whitespace-nowrap">
                            {{ $factor->created_at?->format('d M Y H:i:s') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
@endforeach
