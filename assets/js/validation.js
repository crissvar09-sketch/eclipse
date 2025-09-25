/**
 * Validación de formularios del lado del cliente
 * Cumple con estándares UX y proporciona retroalimentación al usuario
 */

class FormValidator {
    constructor() {
        this.init();
    }

    init() {
        // Validar formulario de login
        this.setupLoginValidation();
        
        // Validar formulario de checkout
        this.setupCheckoutValidation();
        
        // Validar formulario de registro (si existe)
        this.setupRegistrationValidation();
    }

    /**
     * Configurar validación del formulario de login
     */
    setupLoginValidation() {
        const loginForm = document.querySelector('form[method="POST"]');
        if (!loginForm) return;

        const usernameInput = loginForm.querySelector('input[name="username"]');
        const passwordInput = loginForm.querySelector('input[name="password"]');

        if (usernameInput) {
            usernameInput.addEventListener('blur', () => this.validateUsername(usernameInput));
            usernameInput.addEventListener('input', () => this.clearError(usernameInput));
        }

        if (passwordInput) {
            passwordInput.addEventListener('blur', () => this.validatePassword(passwordInput));
            passwordInput.addEventListener('input', () => this.clearError(passwordInput));
        }

        loginForm.addEventListener('submit', (e) => {
            if (!this.validateLoginForm(loginForm)) {
                e.preventDefault();
            }
        });
    }

    /**
     * Configurar validación del formulario de checkout
     */
    setupCheckoutValidation() {
        const checkoutForm = document.querySelector('form[method="POST"]');
        if (!checkoutForm) return;

        const nombreInput = checkoutForm.querySelector('input[name="nombre"]');
        const direccionInput = checkoutForm.querySelector('input[name="direccion"]');
        const telefonoInput = checkoutForm.querySelector('input[name="telefono"]');
        const metodoPagoInputs = checkoutForm.querySelectorAll('input[name="metodo_pago"]');

        if (nombreInput) {
            nombreInput.addEventListener('blur', () => this.validateNombre(nombreInput));
            nombreInput.addEventListener('input', () => this.clearError(nombreInput));
        }

        if (direccionInput) {
            direccionInput.addEventListener('blur', () => this.validateDireccion(direccionInput));
            direccionInput.addEventListener('input', () => this.clearError(direccionInput));
        }

        if (telefonoInput) {
            telefonoInput.addEventListener('blur', () => this.validateTelefono(telefonoInput));
            telefonoInput.addEventListener('input', () => this.clearError(telefonoInput));
        }

        metodoPagoInputs.forEach(input => {
            input.addEventListener('change', () => this.validateMetodoPago(metodoPagoInputs));
        });

        checkoutForm.addEventListener('submit', (e) => {
            if (!this.validateCheckoutForm(checkoutForm)) {
                e.preventDefault();
            }
        });
    }

    /**
     * Configurar validación del formulario de registro
     */
    setupRegistrationValidation() {
        const regForm = document.querySelector('form[action*="register"]');
        if (!regForm) return;

        // Implementar validación de registro si es necesario
    }

    /**
     * Validar nombre de usuario
     */
    validateUsername(input) {
        const value = input.value.trim();
        if (value.length < 3) {
            this.showError(input, 'El nombre de usuario debe tener al menos 3 caracteres');
            return false;
        }
        if (!/^[a-zA-Z0-9_]+$/.test(value)) {
            this.showError(input, 'El nombre de usuario solo puede contener letras, números y guiones bajos');
            return false;
        }
        this.showSuccess(input);
        return true;
    }

    /**
     * Validar contraseña
     */
    validatePassword(input) {
        const value = input.value;
        if (value.length < 6) {
            this.showError(input, 'La contraseña debe tener al menos 6 caracteres');
            return false;
        }
        this.showSuccess(input);
        return true;
    }

    /**
     * Validar nombre
     */
    validateNombre(input) {
        const value = input.value.trim();
        if (value.length < 2) {
            this.showError(input, 'El nombre debe tener al menos 2 caracteres');
            return false;
        }
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) {
            this.showError(input, 'El nombre solo puede contener letras y espacios');
            return false;
        }
        this.showSuccess(input);
        return true;
    }

    /**
     * Validar dirección
     */
    validateDireccion(input) {
        const value = input.value.trim();
        if (value.length < 10) {
            this.showError(input, 'La dirección debe tener al menos 10 caracteres');
            return false;
        }
        this.showSuccess(input);
        return true;
    }

    /**
     * Validar teléfono
     */
    validateTelefono(input) {
        const value = input.value.trim();
        if (!/^[0-9+\-\s()]+$/.test(value)) {
            this.showError(input, 'El teléfono solo puede contener números, espacios, guiones y paréntesis');
            return false;
        }
        if (value.replace(/[^0-9]/g, '').length < 7) {
            this.showError(input, 'El teléfono debe tener al menos 7 dígitos');
            return false;
        }
        this.showSuccess(input);
        return true;
    }

    /**
     * Validar método de pago
     */
    validateMetodoPago(inputs) {
        const selected = Array.from(inputs).find(input => input.checked);
        if (!selected) {
            this.showError(inputs[0].closest('.payment-options'), 'Selecciona un método de pago');
            return false;
        }
        this.clearError(inputs[0].closest('.payment-options'));
        return true;
    }

    /**
     * Validar formulario de login completo
     */
    validateLoginForm(form) {
        const usernameInput = form.querySelector('input[name="username"]');
        const passwordInput = form.querySelector('input[name="password"]');
        
        let isValid = true;
        
        if (usernameInput && !this.validateUsername(usernameInput)) {
            isValid = false;
        }
        
        if (passwordInput && !this.validatePassword(passwordInput)) {
            isValid = false;
        }
        
        return isValid;
    }

    /**
     * Validar formulario de checkout completo
     */
    validateCheckoutForm(form) {
        const nombreInput = form.querySelector('input[name="nombre"]');
        const direccionInput = form.querySelector('input[name="direccion"]');
        const telefonoInput = form.querySelector('input[name="telefono"]');
        const metodoPagoInputs = form.querySelectorAll('input[name="metodo_pago"]');
        
        let isValid = true;
        
        if (nombreInput && !this.validateNombre(nombreInput)) {
            isValid = false;
        }
        
        if (direccionInput && !this.validateDireccion(direccionInput)) {
            isValid = false;
        }
        
        if (telefonoInput && !this.validateTelefono(telefonoInput)) {
            isValid = false;
        }
        
        if (metodoPagoInputs.length > 0 && !this.validateMetodoPago(metodoPagoInputs)) {
            isValid = false;
        }
        
        return isValid;
    }

    /**
     * Mostrar error en un campo
     */
    showError(input, message) {
        this.clearError(input);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            color: #ff4d4d;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        `;
        
        input.parentNode.appendChild(errorDiv);
        input.style.borderColor = '#ff4d4d';
    }

    /**
     * Mostrar éxito en un campo
     */
    showSuccess(input) {
        this.clearError(input);
        input.style.borderColor = '#4CAF50';
    }

    /**
     * Limpiar errores de un campo
     */
    clearError(input) {
        const errorDiv = input.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '';
    }
}

// Inicializar validador cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new FormValidator();
});
