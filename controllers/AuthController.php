<?php
/**
 * CONTROLLER: AuthController
 * Maneja la autenticación y seguridad del sistema
 */

require_once '../config/database.php';
require_once '../models/Usuario.php';
require_once '../models/Estudiante.php';

class AuthController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * LOGIN - Autenticar usuario o estudiante
     */
    public function login($username, $password) {
        if(empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Por favor ingrese usuario y contraseña'
            ];
        }

        // Intentar login como usuario (empleado)
        $usuario = new Usuario($this->db);
        $usuario->username = $username;
        $usuario->password = $password;
        $userData = $usuario->login();

        if($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_type'] = 'usuario';
            $_SESSION['username'] = $userData['username'];
            $_SESSION['nombre'] = $userData['nombre'];
            $_SESSION['rol'] = $userData['rol'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['permisos'] = json_decode($userData['permisos'], true);
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'user_type' => 'usuario',
                'rol' => $userData['rol'],
                'data' => $userData
            ];
        }

        // Intentar login como estudiante
        $estudiante = new Estudiante($this->db);
        $estudiante->username = $username;
        $estudiante->password = $password;
        $studentData = $estudiante->login();

        if($studentData) {
            $_SESSION['user_id'] = $studentData['id'];
            $_SESSION['user_type'] = 'estudiante';
            $_SESSION['username'] = $studentData['username'];
            $_SESSION['nombre'] = $studentData['nombre'];
            $_SESSION['rol'] = 'estudiante';
            $_SESSION['email'] = $studentData['email'];
            $_SESSION['carrera'] = $studentData['carrera'];
            $_SESSION['semestre'] = $studentData['semestre'];
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'user_type' => 'estudiante',
                'rol' => 'estudiante',
                'data' => $studentData
            ];
        }

        return [
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }

    /**
     * LOGOUT - Cerrar sesión
     */
    public function logout() {
        session_unset();
        session_destroy();
        return [
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ];
    }

    /**
     * CHECK LOGIN - Verificar si el usuario está autenticado
     */
    public function checkLogin() {
        if(isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            return true;
        }
        return false;
    }

    /**
     * CHECK PERMISSION - Verificar si el usuario tiene un permiso específico
     */
    public function checkPermission($permission) {
        if(!$this->checkLogin()) {
            return false;
        }

        // El administrador tiene todos los permisos
        if($_SESSION['rol'] === 'administrador') {
            return true;
        }

        // Verificar permisos específicos
        if(isset($_SESSION['permisos']) && is_array($_SESSION['permisos'])) {
            return in_array($permission, $_SESSION['permisos']) || in_array('all', $_SESSION['permisos']);
        }

        return false;
    }

    /**
     * GET CURRENT USER - Obtener datos del usuario actual
     */
    public function getCurrentUser() {
        if(!$this->checkLogin()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nombre' => $_SESSION['nombre'],
            'rol' => $_SESSION['rol'],
            'email' => $_SESSION['email'],
            'user_type' => $_SESSION['user_type']
        ];
    }

    /**
     * CHANGE PASSWORD - Cambiar contraseña del usuario
     */
    public function changePassword($current_password, $new_password) {
        if(!$this->checkLogin()) {
            return [
                'success' => false,
                'message' => 'Debe estar autenticado'
            ];
        }

        if(empty($current_password) || empty($new_password)) {
            return [
                'success' => false,
                'message' => 'Debe proporcionar la contraseña actual y la nueva'
            ];
        }

        if(strlen($new_password) < 6) {
            return [
                'success' => false,
                'message' => 'La nueva contraseña debe tener al menos 6 caracteres'
            ];
        }

        $user_type = $_SESSION['user_type'];
        $user_id = $_SESSION['user_id'];

        if($user_type === 'usuario') {
            $usuario = new Usuario($this->db);
            $usuario->id = $user_id;
            $usuario->readOne();
            
            // Verificar contraseña actual
            $query = "SELECT password FROM usuarios WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $user_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!password_verify($current_password, $row['password'])) {
                return [
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ];
            }

            // Actualizar contraseña
            $query = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(1, $hashed);
            $stmt->bindParam(2, $user_id);
            
            if($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Contraseña actualizada correctamente'
                ];
            }
        } else {
            $estudiante = new Estudiante($this->db);
            $estudiante->id = $user_id;
            $estudiante->readOne();
            
            // Verificar contraseña actual
            $query = "SELECT password FROM estudiantes WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $user_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!password_verify($current_password, $row['password'])) {
                return [
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ];
            }

            // Actualizar contraseña
            $query = "UPDATE estudiantes SET password = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(1, $hashed);
            $stmt->bindParam(2, $user_id);
            
            if($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Contraseña actualizada correctamente'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Error al actualizar la contraseña'
        ];
    }
}

// Manejo de peticiones AJAX
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $action = $_POST['action'] ?? '';

    switch($action) {
        case 'login':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $result = $auth->login($username, $password);
            echo json_encode($result);
            break;

        case 'logout':
            $result = $auth->logout();
            echo json_encode($result);
            break;

        case 'check_login':
            $isLoggedIn = $auth->checkLogin();
            echo json_encode(['logged_in' => $isLoggedIn]);
            break;

        case 'get_current_user':
            $user = $auth->getCurrentUser();
            echo json_encode($user);
            break;

        case 'change_password':
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $result = $auth->changePassword($current, $new);
            echo json_encode($result);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    exit;
}
?>
