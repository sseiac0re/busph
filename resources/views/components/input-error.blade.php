@props(['messages', 'field' => null])

@php
    $hasServerErrors = $messages && count((array) $messages) > 0;
    $serverError = $hasServerErrors ? (array) $messages[0] : null;
@endphp

@if($field)
    {{-- Real-time validation component - uses parent x-data scope --}}
    <div 
        x-show="error || @js($serverError)"
        {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 mt-2']) }}
    >
        <p x-show="error" x-text="error"></p>
        <p x-show="@js($serverError) && !error" x-text="@js($serverError)"></p>
    </div>
@else
    {{-- Standard error display for non-real-time fields --}}
    @if ($messages)
        <div {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1 mt-2']) }}>
            <ul>
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
