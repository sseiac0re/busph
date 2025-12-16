@props(['field', 'messages' => null])

@php
    // Get validation rules
    $rules = [
        'name' => [
            'required' => 'Name is required.',
            'format' => 'Please follow the format: Surname, First Name Middle Name',
            'max' => 'Name must not exceed 255 characters.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'email' => 'Please enter a valid email address (gmail.com, yahoo.com, hotmail.com, outlook.com, busph.com, or email.com).'
        ],
        'password' => [
            'required' => 'Password is required.',
            'min' => 'Password must be at least 8 characters.'
        ],
        'password_confirmation' => [
            'required' => 'Please confirm your password.',
            'confirmed' => 'Passwords do not match.'
        ],
        'valid_id' => [
            'required' => 'Please upload a valid ID.',
            'mimes' => 'File must be JPG, PNG, or PDF format.',
            'max' => 'File size must not exceed 2MB.'
        ],
        'subject' => [
            'required' => 'Subject is required.'
        ],
        'message' => [
            'required' => 'Message is required.'
        ],
        'bus_number' => [
            'required' => 'Bus number is required.',
            'regex' => 'Bus number must follow the format: BUS-102'
        ],
        'plate_number' => [
            'required' => 'Plate number is required.',
            'regex' => 'Plate number must follow the format: ABC-123'
        ],
        'capacity' => [
            'required' => 'Capacity is required.',
            'min' => 'Capacity must be at least 10 seats.',
            'max' => 'Capacity must not exceed 80 seats.'
        ]
    ];
    
    $fieldRules = $rules[$field] ?? [];
@endphp

<div 
    x-data="{ 
        error: null,
        validate() {
            const input = $el.previousElementSibling?.querySelector('input, textarea, select') || 
                         $el.previousElementSibling?.querySelector('input[type=file]') ||
                         document.querySelector('[name={{ $field }}]');
            
            if (!input) return;
            
            const value = input.type === 'file' ? (input.files[0] || null) : input.value;
            const fieldName = input.name || '{{ $field }}';
            
            // Validation logic
            @if($field === 'name')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Name is required.' }}';
                } else if (value.length > 255) {
                    this.error = '{{ $fieldRules['max'] ?? 'Name must not exceed 255 characters.' }}';
                } else {
                    const parts = value.split(',').map(p => p.trim());
                    if (parts.length < 2) {
                        this.error = '{{ $fieldRules['format'] ?? 'Please follow the format: Surname, First Name Middle Name' }}';
                    } else {
                        this.error = null;
                    }
                }
            @elseif($field === 'email')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Email is required.' }}';
                } else {
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$/i;
                    if (!emailRegex.test(value)) {
                        this.error = '{{ $fieldRules['email'] ?? 'Please enter a valid email address.' }}';
                    } else {
                        this.error = null;
                    }
                }
            @elseif($field === 'password')
                if (!value || value.length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Password is required.' }}';
                } else if (value.length < 8) {
                    this.error = '{{ $fieldRules['min'] ?? 'Password must be at least 8 characters.' }}';
                } else {
                    this.error = null;
                }
            @elseif($field === 'password_confirmation')
                const passwordInput = document.querySelector('[name=password]');
                const passwordValue = passwordInput?.value || '';
                if (!value || value.length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Please confirm your password.' }}';
                } else if (passwordValue && value !== passwordValue) {
                    this.error = '{{ $fieldRules['confirmed'] ?? 'Passwords do not match.' }}';
                } else {
                    this.error = null;
                }
            @elseif($field === 'valid_id')
                if (!value) {
                    this.error = '{{ $fieldRules['required'] ?? 'Please upload a valid ID.' }}';
                } else {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!allowedTypes.includes(value.type)) {
                        this.error = '{{ $fieldRules['mimes'] ?? 'File must be JPG, PNG, or PDF format.' }}';
                    } else if (value.size > 2048 * 1024) {
                        this.error = '{{ $fieldRules['max'] ?? 'File size must not exceed 2MB.' }}';
                    } else {
                        this.error = null;
                    }
                }
            @elseif($field === 'subject' || $field === 'message')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'This field is required.' }}';
                } else {
                    this.error = null;
                }
            @elseif($field === 'bus_number')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Bus number is required.' }}';
                } else {
                    const regex = /^BUS-\d+$/;
                    if (!regex.test(value)) {
                        this.error = '{{ $fieldRules['regex'] ?? 'Bus number must follow the format: BUS-102' }}';
                    } else {
                        this.error = null;
                    }
                }
            @elseif($field === 'plate_number')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Plate number is required.' }}';
                } else {
                    const regex = /^[A-Z]{3}-\d{3,4}$/;
                    if (!regex.test(value)) {
                        this.error = '{{ $fieldRules['regex'] ?? 'Plate number must follow the format: ABC-123' }}';
                    } else {
                        this.error = null;
                    }
                }
            @elseif($field === 'capacity')
                if (!value || value.trim().length === 0) {
                    this.error = '{{ $fieldRules['required'] ?? 'Capacity is required.' }}';
                } else {
                    const num = parseInt(value);
                    if (isNaN(num)) {
                        this.error = 'Capacity must be a number.';
                    } else if (num < 10) {
                        this.error = '{{ $fieldRules['min'] ?? 'Capacity must be at least 10 seats.' }}';
                    } else if (num > 80) {
                        this.error = '{{ $fieldRules['max'] ?? 'Capacity must not exceed 80 seats.' }}';
                    } else {
                        this.error = null;
                    }
                }
            @endif
            
            // Update input styling
            if (input) {
                if (this.error) {
                    input.classList.add('border-red-500');
                    input.classList.remove('border-green-500');
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-green-500');
                }
            }
        }
    }"
    x-init="
        const input = $el.previousElementSibling?.querySelector('input, textarea, select') || 
                     $el.previousElementSibling?.querySelector('input[type=file]') ||
                     document.querySelector('[name={{ $field }}]');
        if (input) {
            input.addEventListener('input', () => validate());
            input.addEventListener('blur', () => validate());
            if (input.type === 'file') {
                input.addEventListener('change', () => validate());
            }
        }
    "
    {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 mt-2']) }}
>
    <template x-if="error">
        <p x-text="error"></p>
    </template>
    
    @if ($messages)
        @foreach ((array) $messages as $message)
            <p>{{ $message }}</p>
        @endforeach
    @endif
</div>

