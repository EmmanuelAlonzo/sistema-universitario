<?php
/**
 * MODEL: Estudiante
 * Maneja todas las operaciones CRUD para estudiantes
 */

class Estudiante {
    private $conn;
    private $table_name = "estudiantes";

    // Propiedades
    public $id;
    public $student_id;
    public $username;
    public $password;
    public $nombre;
    public $email;
    public $telefono;
    public $carrera;
    public $semestre;
    public $cursos;
    public $calificaciones;
    public $pagos;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREATE - Crear nuevo estudiante
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET student_id=:student_id,
                    username=:username,
                    password=:password,
                    nombre=:nombre,
                    email=:email,
                    telefono=:telefono,
                    carrera=:carrera,
                    semestre=:semestre,
                    estado=:estado";

        $stmt = $this->conn->prepare($query);

        // Limpiar y hashear
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->semestre = htmlspecialchars(strip_tags($this->semestre));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * READ - Obtener todos los estudiantes
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ ONE - Obtener un estudiante por ID
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->student_id = $row['student_id'];
            $this->username = $row['username'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->carrera = $row['carrera'];
            $this->semestre = $row['semestre'];
            $this->cursos = $row['cursos'];
            $this->calificaciones = $row['calificaciones'];
            $this->pagos = $row['pagos'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    /**
     * UPDATE - Actualizar estudiante
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre=:nombre,
                    email=:email,
                    telefono=:telefono,
                    carrera=:carrera,
                    semestre=:semestre,
                    estado=:estado
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->semestre = htmlspecialchars(strip_tags($this->semestre));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Eliminar estudiante
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * LOGIN - Autenticar estudiante
     */
    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE username = :username AND estado = 'active' 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($this->password, $row['password'])) {
            return $row;
        }
        return false;
    }

    /**
     * SEARCH - Buscar estudiantes
     */
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nombre LIKE ? OR email LIKE ? OR student_id LIKE ? OR carrera LIKE ?
                  ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";
        
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->bindParam(4, $keyword);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * GET BY CAREER - Obtener estudiantes por carrera
     */
    public function getByCareer($carrera) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE carrera = :carrera AND estado = 'active'
                  ORDER BY semestre ASC, nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":carrera", $carrera);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * GENERATE STUDENT ID - Generar ID único de estudiante
     */
    public function generateStudentId() {
        $query = "SELECT MAX(CAST(SUBSTRING(student_id, 4) AS UNSIGNED)) as max_id 
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $next_id = ($row['max_id'] ?? 1000) + 1;
        return 'STU' . $next_id;
    }
}
?>
