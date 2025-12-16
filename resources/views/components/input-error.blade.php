@props(['messages', 'field' => null])

@php
    $hasServerErrors = $messages && count((array) $messages) > 0;
@endphp

<div 
    @if($field)
    x-data="{ 
        error: null,
        serverError: @js($hasServerErrors ? (array) $messages[0] : null),
        get displayError() {
            return this.error || this.serverError;
        }
    }"
    x-show="displayError"
    @endif
    {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}
>
    @if ($field)
        <template x-if="error">
            <p x-text="error"></p>
        </template>
        <template x-if="serverError && !error">
            <p x-text="serverError"></p>
        </template>
    @else
        @if ($messages)
            <ul>
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
