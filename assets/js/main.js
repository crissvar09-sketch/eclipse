/**
 * JavaScript principal para la aplicación Eclipse
 * Maneja la funcionalidad del carrito y navegación
 */

// Variables globales
let cart = JSON.parse(localStorage.getItem('cart')) || [];

/**
 * Agregar producto al carrito
 */
function addToCart(productId, price, name, image) {
    const quantity = parseInt(document.querySelector(`[data-product-id="${productId}"] .quantity`).textContent);
    
    // Crear FormData para enviar al servidor
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    // Enviar petición al servidor
    fetch('api/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${name} (x${quantity}) ha sido agregado al carrito.`);
            // Actualizar contador del carrito si existe
            updateCartCounter();
        } else {
            showNotification(`Error: ${data.message}`, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar producto al carrito', 'error');
    });
}

/**
 * Actualizar cantidad en el carrito
 */
function updateCartQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (newQuantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = newQuantity;
            saveCart();
            location.reload(); // Recargar para mostrar cambios
        }
    }
}

/**
 * Eliminar producto del carrito
 */
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    location.reload(); // Recargar para mostrar cambios
}

/**
 * Guardar carrito en localStorage
 */
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

/**
 * Actualizar contador del carrito
 */
function updateCartCounter() {
    // Obtener cantidad de items en el carrito desde el servidor
    fetch('api/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const counter = document.querySelector('.cart-counter');
            if (counter) {
                counter.textContent = data.count;
                counter.style.display = data.count > 0 ? 'block' : 'none';
            }
        }
    })
    .catch(error => console.error('Error al actualizar contador:', error));
}

/**
 * Marcar página activa en la navegación
 */
function markActivePage() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
}

/**
 * Mostrar notificación
 */
function showNotification(message, type = 'success') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type === 'error' ? 'error' : ''}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Manejar botones de cantidad en el menú
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar contador del carrito
    updateCartCounter();
    
    // Marcar página activa en la navegación
    markActivePage();
    
    // Manejar botones de cantidad en productos
    document.querySelectorAll('.product-card').forEach(card => {
        const decreaseBtn = card.querySelector('.decrease-btn');
        const increaseBtn = card.querySelector('.increase-btn');
        const quantitySpan = card.querySelector('.quantity');

        if (decreaseBtn && increaseBtn && quantitySpan) {
            decreaseBtn.addEventListener('click', () => {
                let quantity = parseInt(quantitySpan.textContent);
                if (quantity > 1) {
                    quantity--;
                    quantitySpan.textContent = quantity;
                }
            });

            increaseBtn.addEventListener('click', () => {
                let quantity = parseInt(quantitySpan.textContent);
                quantity++;
                quantitySpan.textContent = quantity;
            });
        }
    });
});

// Agregar estilos CSS para las animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
