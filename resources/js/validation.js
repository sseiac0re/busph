// Real-time validation utility for BusPH system

/**
 * Validation rules matching Laravel backend validation
 */
const validationRules = {
    // Name validation: required, string, max 255, format: Surname, First Name Middle Name
    name: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Name is required.';
            }
            if (value.length > 255) {
                return 'Name must not exceed 255 characters.';
            }
            // Check format: Surname, First Name Middle Name
            const parts = value.split(',').map(p => p.trim());
            if (parts.length < 2) {
                return 'Please follow the format: Surname, First Name Middle Name';
            }
            return null;
        },
        onInput: true
    },

    // Email validation: required, email format, specific domains
    email: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Email is required.';
            }
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$/i;
            if (!emailRegex.test(value)) {
                return 'Please enter a valid email address (gmail.com, yahoo.com, hotmail.com, outlook.com, busph.com, or email.com).';
            }
            return null;
        },
        onInput: true
    },

    // Password validation: required, min 8 chars, confirmed
    password: {
        validate: (value, formData = {}) => {
            if (!value || value.length === 0) {
                return 'Password is required.';
            }
            if (value.length < 8) {
                return 'Password must be at least 8 characters.';
            }
            // Check if passwords match (for confirmation field)
            if (formData.password_confirmation && value !== formData.password_confirmation) {
                return 'Passwords do not match.';
            }
            return null;
        },
        onInput: true
    },

    // Password confirmation
    password_confirmation: {
        validate: (value, formData = {}) => {
            if (!value || value.length === 0) {
                return 'Please confirm your password.';
            }
            if (formData.password && value !== formData.password) {
                return 'Passwords do not match.';
            }
            return null;
        },
        onInput: true
    },

    // File validation: required, jpg/png/pdf, max 2MB
    valid_id: {
        validate: (file) => {
            if (!file) {
                return 'Please upload a valid ID.';
            }
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                return 'File must be JPG, PNG, or PDF format.';
            }
            if (file.size > 2048 * 1024) {
                return 'File size must not exceed 2MB.';
            }
            return null;
        },
        onInput: true
    },

    // Bus number: BUS-XXX format
    bus_number: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Bus number is required.';
            }
            const regex = /^BUS-\d+$/;
            if (!regex.test(value)) {
                return 'Bus number must follow the format: BUS-102';
            }
            return null;
        },
        onInput: true
    },

    // Plate number: ABC-123 or ABC-1234 format
    plate_number: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Plate number is required.';
            }
            const regex = /^[A-Z]{3}-\d{3,4}$/;
            if (!regex.test(value)) {
                return 'Plate number must follow the format: ABC-123';
            }
            return null;
        },
        onInput: true
    },

    // Capacity: integer, min 10, max 80
    capacity: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Capacity is required.';
            }
            const num = parseInt(value);
            if (isNaN(num)) {
                return 'Capacity must be a number.';
            }
            if (num < 10) {
                return 'Capacity must be at least 10 seats.';
            }
            if (num > 80) {
                return 'Capacity must not exceed 80 seats.';
            }
            return null;
        },
        onInput: true
    },

    // Subject: required, string
    subject: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Subject is required.';
            }
            return null;
        },
        onInput: true
    },

    // Message: required, string
    message: {
        validate: (value) => {
            if (!value || value.trim().length === 0) {
                return 'Message is required.';
            }
            return null;
        },
        onInput: true
    }
};

/**
 * Initialize real-time validation for a form
 */
function initRealTimeValidation(formElement) {
    const inputs = formElement.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        const fieldName = input.name;
        if (!fieldName) return;

        // Get validation rule for this field
        const rule = validationRules[fieldName];
        if (!rule) return;

        // Add Alpine.js data attribute for error state
        if (!input.closest('[x-data]')) {
            const wrapper = input.closest('div') || input.parentElement;
            if (wrapper && !wrapper.hasAttribute('x-data')) {
                wrapper.setAttribute('x-data', '{ error: null }');
            }
        }

        // Add event listeners
        if (rule.onInput) {
            input.addEventListener('input', (e) => {
                validateField(input, rule, formElement);
            });

            input.addEventListener('blur', (e) => {
                validateField(input, rule, formElement);
            });
        }

        if (input.type === 'file') {
            input.addEventListener('change', (e) => {
                validateField(input, rule, formElement);
            });
        }
    });
}

/**
 * Validate a single field
 */
function validateField(input, rule, formElement) {
    const fieldName = input.name;
    let value = input.value;

    // Handle file inputs
    if (input.type === 'file') {
        value = input.files[0] || null;
    }

    // Get all form data for cross-field validation
    const formData = new FormData(formElement);
    const formDataObj = {};
    for (let [key, val] of formData.entries()) {
        formDataObj[key] = val;
    }

    // Validate
    const error = rule.validate(value, formDataObj);

    // Update error display
    updateErrorDisplay(input, error);
}

/**
 * Update error display for a field
 */
function updateErrorDisplay(input, error) {
    // Find or create error container
    let errorContainer = input.parentElement.querySelector('.validation-error');
    
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'validation-error text-sm text-red-600 dark:text-red-400 mt-2';
        input.parentElement.appendChild(errorContainer);
    }

    // Update error message
    if (error) {
        errorContainer.textContent = error;
        errorContainer.style.display = 'block';
        input.classList.add('border-red-500');
        input.classList.remove('border-green-500');
    } else {
        errorContainer.style.display = 'none';
        input.classList.remove('border-red-500');
        input.classList.add('border-green-500');
    }
}

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        initRealTimeValidation(form);
    });
});

// Export for use in other scripts
window.RealTimeValidation = {
    init: initRealTimeValidation,
    validateField: validateField,
    rules: validationRules
};

