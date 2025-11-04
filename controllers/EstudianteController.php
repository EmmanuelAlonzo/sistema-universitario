<?php
/**
 * CONTROLLER: EstudianteController
 * Maneja todas las operaciones CRUD de estudiantes con AJAX
 */

require_once '../config/database.php';
require_once '../models/Estudiante.php';

session_start();

class EstudianteController {
    private $db;
    private $estudiante;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->estudiante = new Estudiante($this->db);
    }

    /**
     * CREATE - Crear nuevo estudiante
     */
    public function create($data) {
        // Validar datos requeridos
        if(empty($data['nombre']) || empty($data['email']) || empty($data['carrera']) || empty($data['semestre'])) {
            return [
                'success' => false,
                'message' => 'Todos los campos son requeridos'
            ];
        }

        // Generar student_id automáticamente
        $this->estudiante->student_id = $this->estudiante->generateStudentId();
        
        // Generar username (formato: nombre.apellido)
        $nombre_parts = explode(' ', strtolower($data['nombre']));
        $username = implode('.', array_slice($nombre_parts, 0, 2));
        
        // Generar email si no se proporciona
        if(empty($data['email'])) {
            $data['email'] = $username . '@universidad.edu';
        }

        $this->estudiante->username = $username;
        $this->estudiante->password = $data['password'] ?? 'est123'; // Contraseña por defecto
        $this->estudiante->nombre = $data['nombre'];
        $this->estudiante->email = $data['email'];
        $this->estudiante->telefono = $data['telefono'] ?? '';
        $this->estudiante->carrera = $data['carrera'];
        $this->estudiante->semestre = $data['semestre'];
        $this->estudiante->estado = 'active';

        if($this->estudiante->create()) {
            return [
                'success' => true,
                'message' => 'Estudiante creado exitosamente',
                'student_id' => $this->estudiante->student_id,
                'username' => $username,
                'id' => $this->estudiante->id
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al crear estudiante'
        ];
    }

    /**
     * READ - Obtener todos los estudiantes
     */
    public function read() {
        $stmt = $this->estudiante->read();
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'data' => $estudiantes
        ];
    }

    /**
     * READ ONE - Obtener un estudiante
     */
    public function readOne($id) {
        $this->estudiante->id = $id;
        
        if($this->estudiante->readOne()) {
            return [
                'success' => true,
                'data' => [
                    'id' => $this->estudiante->id,
                    'student_id' => $this->estudiante->student_id,
                    'username' => $this->estudiante->username,
                    'nombre' => $this->estudiante->nombre,
                    'email' => $this->estudiante->email,
                    'telefono' => $this->estudiante->telefono,
                    'carrera' => $this->estudiante->carrera,
                    'semestre' => $this->estudiante->semestre,
                    'estado' => $this->estudiante->estado
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ];
    }

    /**
     * UPDATE - Actualizar estudiante
     */
    public function update($id, $data) {
        $this->estudiante->id = $id;
        $this->estudiante->nombre = $data['nombre'];
        $this->estudiante->email = $data['email'];
        $this->estudiante->telefono = $data['telefono'] ?? '';
        $this->estudiante->carrera = $data['carrera'];
        $this->estudiante->semestre = $data['semestre'];
        $this->estudiante->estado = $data['estado'] ?? 'active';

        if($this->estudiante->update()) {
            return [
                'success' => true,
                'message' => 'Estudiante actualizado exitosamente'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al actualizar estudiante'
        ];
    }

    /**
     * DELETE - Eliminar estudiante
     */
    public function delete($id) {
        $this->estudiante->id = $id;

        if($this->estudiante->delete()) {
            return [
                'success' => true,
                'message' => 'Estudiante eliminado exitosamente'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al eliminar estudiante'
        ];
    }

    /**
     * SEARCH - Buscar estudiantes
     */
    public function search($keyword) {
        $stmt = $this->estudiante->search($keyword);
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'data' => $estudiantes
        ];
    }
}

// Manejo de peticiones AJAX
if($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new EstudianteController();
    $action = $_REQUEST['action'] ?? '';

    switch($action) {
        case 'create':
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'carrera' => $_POST['carrera'] ?? '',
                'semestre' => $_POST['semestre'] ?? '',
                'password' => $_POST['password'] ?? 'est123'
            ];
            $result = $controller->create($data);
            echo json_encode($result);
            break;

        case 'read':
            $result = $controller->read();
            echo json_encode($result);
            break;

        case 'read_one':
            $id = $_REQUEST['id'] ?? 0;
            $result = $controller->readOne($id);
            echo json_encode($result);
            break;

        case 'update':
            $id = $_POST['id'] ?? 0;
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'carrera' => $_POST['carrera'] ?? '',
                'semestre' => $_POST['semestre'] ?? '',
                'estado' => $_POST['estado'] ?? 'active'
            ];
            $result = $controller->update($id, $data);
            echo json_encode($result);
            break;

        case 'delete':
            $id = $_POST['id'] ?? 0;
            $result = $controller->delete($id);
            echo json_encode($result);
            break;

        case 'search':
            $keyword = $_REQUEST['keyword'] ?? '';
            $result = $controller->search($keyword);
            echo json_encode($result);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    exit;
}
?>
