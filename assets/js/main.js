/**
 * Pizza Lovers Club - Main JavaScript
 * Handles client-side form interactions and validation feedback
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transitions to form inputs
    const inputs = document.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transition = 'all 200ms ease-in-out';
        });

        input.addEventListener('blur', function() {
            // Remove error styling if user corrects the input
            if (this.classList.contains('error') && this.value.trim() !== '') {
                this.style.borderColor = '';
            }
        });
    });

    // Add form submission feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
                
                // Show loading state
                const originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                // Re-enable after 2 seconds if form is still submitting
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtn.textContent = originalText;
                }, 2000);
            }
        });
    });

    // Add password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const strengthIndicator = document.createElement('div');
        strengthIndicator.style.marginTop = '8px';
        strengthIndicator.style.fontSize = '12px';
        strengthIndicator.style.fontWeight = '600';
        passwordInput.parentNode.appendChild(strengthIndicator);

        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updateStrengthIndicator(strengthIndicator, strength);
        });
    }

    // Add checkbox styling
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.parentElement.classList.contains('checkbox-group')) {
                this.style.accentColor = this.checked ? '#d97706' : '#e5e7eb';
            }
        });
    });
});

/**
 * Check password strength
 */
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    return strength;
}

/**
 * Update password strength indicator
 */
function updateStrengthIndicator(element, strength) {
    const messages = [
        { text: 'Very Weak', color: '#dc2626' },
        { text: 'Weak', color: '#f97316' },
        { text: 'Fair', color: '#fbbf24' },
        { text: 'Good', color: '#84cc16' },
        { text: 'Strong', color: '#16a34a' }
    ];

    if (strength === 0) {
        element.textContent = '';
        return;
    }

    const message = messages[strength - 1];
    element.textContent = `Password Strength: ${message.text}`;
    element.style.color = message.color;
}

/**
 * Format phone number as user types
 */
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value.length <= 3) {
            value = value;
        } else if (value.length <= 6) {
            value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
        } else {
            value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
        }
    }
    
    input.value = value;
}

// Apply phone formatting to phone inputs
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    });
});
