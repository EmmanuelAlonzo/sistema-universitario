/**
 * JAVASCRIPT: Autenticación con AJAX
 * Maneja el login, logout y cambio de contraseña
 */

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if(loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            login();
        });
    }
});

/**
 * LOGIN - Autenticar usuario
 */
function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const loginBtn = document.getElementById('loginBtn');
    
    // Validación básica
    if(!username || !password) {
        showMessage('Por favor complete todos los campos', 'error');
        return;
    }

    // Deshabilitar botón durante la petición
    loginBtn.disabled = true;
    loginBtn.textContent = 'Iniciando sesión...';

    // Petición AJAX
    fetch('../controllers/AuthController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=login&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showMessage(data.message, 'success');
            // Redirigir al dashboard después de 1 segundo
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1000);
        } else {
            showMessage(data.message, 'error');
            loginBtn.disabled = false;
            loginBtn.textContent = 'Iniciar Sesión';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error de conexión. Por favor intente nuevamente.', 'error');
        loginBtn.disabled = false;
        loginBtn.textContent = 'Iniciar Sesión';
    });
}

/**
 * LOGOUT - Cerrar sesión
 */
function logout() {
    if(!confirm('¿Está seguro que desea cerrar sesión?')) {
        return;
    }

    fetch('../controllers/AuthController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=logout'
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = 'login.php';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

/**
 * CHANGE PASSWORD - Cambiar contraseña
 */
function changePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if(!currentPassword || !newPassword || !confirmPassword) {
        showMessage('Complete todos los campos', 'error');
        return;
    }

    if(newPassword !== confirmPassword) {
        showMessage('Las contraseñas no coinciden', 'error');
        return;
    }

    if(newPassword.length < 6) {
        showMessage('La contraseña debe tener al menos 6 caracteres', 'error');
        return;
    }

    fetch('../controllers/AuthController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=change_password&current_password=${encodeURIComponent(currentPassword)}&new_password=${encodeURIComponent(newPassword)}`
    })
    .then(response => response.json())
    .then(data => {
        showMessage(data.message, data.success ? 'success' : 'error');
        if(data.success) {
            // Limpiar formulario
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
            
            // Cerrar modal si existe
            const modal = document.getElementById('changePasswordModal');
            if(modal) {
                modal.classList.add('hidden');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error al cambiar contraseña', 'error');
    });
}

/**
 * SHOW MESSAGE - Mostrar mensajes de éxito/error
 */
function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    if(!messageDiv) return;

    messageDiv.textContent = message;
    messageDiv.className = 'mb-4 p-3 rounded-lg';
    
    if(type === 'success') {
        messageDiv.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
    } else {
        messageDiv.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
    }
    
    messageDiv.classList.remove('hidden');

    // Ocultar después de 5 segundos
    setTimeout(() => {
        messageDiv.classList.add('hidden');
    }, 5000);
}
