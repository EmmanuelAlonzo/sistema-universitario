<?php
/**
 * VIEW: Dashboard Principal
 * Panel de control según el rol del usuario
 */

session_start();

// Verificar autenticación
if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$user_name = $_SESSION['nombre'];
$user_role = $_SESSION['rol'];
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Universitario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Panel de Control</h1>
                    <span class="ml-4 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                        <?php echo ucfirst($user_role); ?>
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Período: 2024-1</span>
                    <span class="text-sm text-gray-700 font-medium">
                        Bienvenido, <?php echo htmlspecialchars($user_name); ?>
                    </span>
                    <button onclick="logout()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                <a href="dashboard.php" class="border-b-2 border-indigo-500 text-indigo-600 py-4 px-1 text-sm font-medium">
                    Dashboard
                </a>
                <?php if($user_role === 'administrador' || $user_role === 'coordinador' || $user_role === 'secretaria'): ?>
                <a href="estudiantes.php" class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                    Estudiantes
                </a>
                <?php endif; ?>
                <?php if($user_role === 'administrador' || $user_role === 'coordinador'): ?>
                <a href="materias.php" class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                    Materias
                </a>
                <?php endif; ?>
                <a href="perfil.php" class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                    Mi Perfil
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                Bienvenido, <?php echo htmlspecialchars($user_name); ?>
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Sistema de Gestión Académica - Arquitectura MVC
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php if($user_role === 'administrador' || $user_role === 'coordinador'): ?>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Estudiantes</dt>
                                <dd class="text-lg font-medium text-gray-900" id="totalStudents">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Materias</dt>
                                <dd class="text-lg font-medium text-gray-900" id="totalSubjects">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mensajes</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Eventos</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Arquitectura MVC</h4>
                    <p class="text-sm text-gray-600">Modelo - Vista - Controlador</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <h4 class="font-semibold text-gray-900">CRUD Completo</h4>
                    <p class="text-sm text-gray-600">Create, Read, Update, Delete</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Seguridad</h4>
                    <p class="text-sm text-gray-600">Autenticación con sesiones y passwords hasheados</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <h4 class="font-semibold text-gray-900">AJAX</h4>
                    <p class="text-sm text-gray-600">Operaciones dinámicas sin recargar la página</p>
                </div>
            </div>
        </div>

    </main>

    <script src="../assets/js/auth.js"></script>
    <script>
        // Cargar estadísticas
        document.addEventListener('DOMContentLoaded', function() {
            <?php if($user_role === 'administrador' || $user_role === 'coordinador'): ?>
            // Cargar total de estudiantes
            fetch('../controllers/EstudianteController.php?action=read')
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('totalStudents').textContent = data.data.length;
                    }
                });
            
            // Cargar total de materias (esto requeriría un MateriaController)
            document.getElementById('totalSubjects').textContent = '6';
            <?php endif; ?>
        });
    </script>
</body>
</html>
