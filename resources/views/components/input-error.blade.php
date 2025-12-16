@props(['messages', 'field' => null])

@php
    $hasServerErrors = $messages && count((array) $messages) > 0;
    $serverError = $hasServerErrors ? (array) $messages[0] : null;
@endphp

@if($field)
    {{-- Real-time validation component - accesses parent x-data scope --}}
    <div 
        x-data="{
            get parentError() {
                // Find parent element with x-data
                let parent = $el.parentElement;
                while (parent && parent !== document.body) {
                    if (parent._x_dataStack && parent._x_dataStack[0] && parent._x_dataStack[0].error !== undefined) {
                        return parent._x_dataStack[0].error;
                    }
                    parent = parent.parentElement;
                }
                return null;
            },
            serverError: @js($serverError),
            get displayError() {
                return this.parentError || this.serverError;
            }
        }"
        x-show="displayError"
        {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 mt-2']) }}
    >
        <p x-show="parentError" x-text="parentError"></p>
        <p x-show="serverError && !parentError" x-text="serverError"></p>
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
