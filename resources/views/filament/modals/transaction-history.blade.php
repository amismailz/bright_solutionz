@if($history->isEmpty())
    <p class="text-gray-500">{{ __('No history found for this transaction.') }}</p>
@else
    <ul class="divide-y divide-gray-200">
        @foreach($history as $log)
            <li class="py-2">
                <p><strong>{{ __('By') }}:</strong> {{ $log->user->name ?? 'System' }}</p>
                <p><strong>{{ __('Action') }}:</strong> {{ __($log->action) }}</p>
                <p><strong>{{ __('Date') }}:</strong> {{ $log->created_at->format('Y-m-d H:i') }}</p>
                @if($log->action == 'created')
                    <p><strong>{{ __('Price') }}:</strong> {{json_decode($log['changes'])->amount }}</p>
                @else
                    <p><strong>{{ __('Price') }}:</strong> {{json_decode($log['changes'])->after->amount }}</p>
                @endif
            </li>
        @endforeach
    </ul>
@endif
