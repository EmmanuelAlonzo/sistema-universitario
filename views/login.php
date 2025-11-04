<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Universitario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Universidad Nacional</h1>
            <p class="text-gray-600">Sistema de Gestión Académica</p>
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-700"><strong>MVC + PHP + MySQL</strong></p>
                <p class="text-xs text-blue-600">Estructura MVC con CRUD completo</p>
            </div>
        </div>

        <!-- Mensaje de error/éxito -->
        <div id="message" class="hidden mb-4 p-3 rounded-lg"></div>

        <!-- Formulario de Login -->
        <form id="loginForm" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" id="username" name="username" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Ingrese su usuario">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Ingrese su contraseña">
            </div>

            <button type="submit" id="loginBtn"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Iniciar Sesión
            </button>
        </form>

        <!-- Usuarios de Prueba -->
        <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 mb-3">👥 Usuarios de Prueba:</h3>
            <div class="space-y-2 text-xs text-gray-700">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <p class="font-semibold">🔧 Admin:</p>
                        <p>Usuario: admin</p>
                        <p>Contraseña: admin123</p>
                    </div>
                    <div>
                        <p class="font-semibold">👨‍💼 Coordinador:</p>
                        <p>Usuario: coord.sistemas</p>
                        <p>Contraseña: coord123</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <p class="font-semibold">👨‍🏫 Maestro:</p>
                        <p>Usuario: prof.martinez</p>
                        <p>Contraseña: prof123</p>
                    </div>
                    <div>
                        <p class="font-semibold">🎓 Estudiante:</p>
                        <p>Usuario: juan.perez</p>
                        <p>Contraseña: est123</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="register.php" class="text-sm text-indigo-600 hover:text-indigo-500">
                ¿Nuevo estudiante? Regístrate aquí
            </a>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
</body>
</html>
