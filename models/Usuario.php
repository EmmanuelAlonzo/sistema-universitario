<?php
/**
 * MODEL: Usuario
 * Maneja todas las operaciones CRUD para usuarios del sistema
 * (Administradores, Coordinadores, Maestros, Secretarias, Soporte IT)
 */

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto
    public $id;
    public $employee_id;
    public $username;
    public $password;
    public $nombre;
    public $email;
    public $telefono;
    public $rol;
    public $permisos;
    public $materias_asignadas;
    public $estado;

    /**
     * Constructor - Recibe conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREATE - Crear nuevo usuario
     * @return bool True si se creó exitosamente
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET employee_id=:employee_id,
                    username=:username,
                    password=:password,
                    nombre=:nombre,
                    email=:email,
                    telefono=:telefono,
                    rol=:rol,
                    permisos=:permisos,
                    materias_asignadas=:materias_asignadas,
                    estado=:estado";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->employee_id = htmlspecialchars(strip_tags($this->employee_id));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->permisos = htmlspecialchars(strip_tags($this->permisos));
        $this->materias_asignadas = htmlspecialchars(strip_tags($this->materias_asignadas));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Vincular valores
        $stmt->bindParam(":employee_id", $this->employee_id);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":permisos", $this->permisos);
        $stmt->bindParam(":materias_asignadas", $this->materias_asignadas);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * READ - Obtener todos los usuarios
     * @return PDOStatement
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ ONE - Obtener un usuario por ID
     * @return bool
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->employee_id = $row['employee_id'];
            $this->username = $row['username'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->rol = $row['rol'];
            $this->permisos = $row['permisos'];
            $this->materias_asignadas = $row['materias_asignadas'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    /**
     * UPDATE - Actualizar usuario
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre=:nombre,
                    email=:email,
                    telefono=:telefono,
                    rol=:rol,
                    permisos=:permisos,
                    materias_asignadas=:materias_asignadas,
                    estado=:estado
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->permisos = htmlspecialchars(strip_tags($this->permisos));
        $this->materias_asignadas = htmlspecialchars(strip_tags($this->materias_asignadas));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":permisos", $this->permisos);
        $stmt->bindParam(":materias_asignadas", $this->materias_asignadas);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Eliminar usuario
     * @return bool
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
     * LOGIN - Autenticar usuario
     * @return array|false Datos del usuario o false
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
     * SEARCH - Buscar usuarios por diferentes criterios
     * @param string $keyword Palabra clave
     * @return PDOStatement
     */
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nombre LIKE ? OR email LIKE ? OR employee_id LIKE ?
                  ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";
        
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * GET BY ROLE - Obtener usuarios por rol
     * @param string $rol Rol del usuario
     * @return PDOStatement
     */
    public function getByRole($rol) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE rol = :rol AND estado = 'active'
                  ORDER BY nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":rol", $rol);
        $stmt->execute();
        
        return $stmt;
    }
}
?>
