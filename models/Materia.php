<?php
/**
 * MODEL: Materia
 * Maneja todas las operaciones CRUD para materias/cursos
 */

class Materia {
    private $conn;
    private $table_name = "materias";

    public $id;
    public $codigo;
    public $nombre;
    public $creditos;
    public $carrera;
    public $semestre;
    public $descripcion;
    public $profesor_id;
    public $horario;
    public $aula;
    public $cupo_maximo;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREATE - Crear nueva materia
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET codigo=:codigo,
                    nombre=:nombre,
                    creditos=:creditos,
                    carrera=:carrera,
                    semestre=:semestre,
                    descripcion=:descripcion,
                    profesor_id=:profesor_id,
                    horario=:horario,
                    aula=:aula,
                    cupo_maximo=:cupo_maximo,
                    estado=:estado";

        $stmt = $this->conn->prepare($query);

        $this->codigo = htmlspecialchars(strip_tags($this->codigo));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->creditos = htmlspecialchars(strip_tags($this->creditos));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->semestre = htmlspecialchars(strip_tags($this->semestre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->profesor_id = htmlspecialchars(strip_tags($this->profesor_id));
        $this->horario = htmlspecialchars(strip_tags($this->horario));
        $this->aula = htmlspecialchars(strip_tags($this->aula));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":codigo", $this->codigo);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":creditos", $this->creditos);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":profesor_id", $this->profesor_id);
        $stmt->bindParam(":horario", $this->horario);
        $stmt->bindParam(":aula", $this->aula);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * READ - Obtener todas las materias
     */
    public function read() {
        $query = "SELECT m.*, u.nombre as profesor_nombre 
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.profesor_id = u.id
                  ORDER BY m.carrera ASC, m.semestre ASC, m.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ ONE - Obtener una materia por ID
     */
    public function readOne() {
        $query = "SELECT m.*, u.nombre as profesor_nombre 
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.profesor_id = u.id
                  WHERE m.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->codigo = $row['codigo'];
            $this->nombre = $row['nombre'];
            $this->creditos = $row['creditos'];
            $this->carrera = $row['carrera'];
            $this->semestre = $row['semestre'];
            $this->descripcion = $row['descripcion'];
            $this->profesor_id = $row['profesor_id'];
            $this->horario = $row['horario'];
            $this->aula = $row['aula'];
            $this->cupo_maximo = $row['cupo_maximo'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    /**
     * UPDATE - Actualizar materia
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre=:nombre,
                    creditos=:creditos,
                    carrera=:carrera,
                    semestre=:semestre,
                    descripcion=:descripcion,
                    profesor_id=:profesor_id,
                    horario=:horario,
                    aula=:aula,
                    cupo_maximo=:cupo_maximo,
                    estado=:estado
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->creditos = htmlspecialchars(strip_tags($this->creditos));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->semestre = htmlspecialchars(strip_tags($this->semestre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->profesor_id = htmlspecialchars(strip_tags($this->profesor_id));
        $this->horario = htmlspecialchars(strip_tags($this->horario));
        $this->aula = htmlspecialchars(strip_tags($this->aula));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":creditos", $this->creditos);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":profesor_id", $this->profesor_id);
        $stmt->bindParam(":horario", $this->horario);
        $stmt->bindParam(":aula", $this->aula);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Eliminar materia
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
     * GET BY CAREER - Obtener materias por carrera y semestre
     */
    public function getByCareerAndSemester($carrera, $semestre) {
        $query = "SELECT m.*, u.nombre as profesor_nombre 
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.profesor_id = u.id
                  WHERE m.carrera = :carrera AND m.semestre = :semestre AND m.estado = 'active'
                  ORDER BY m.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":carrera", $carrera);
        $stmt->bindParam(":semestre", $semestre);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * GET BY PROFESSOR - Obtener materias de un profesor
     */
    public function getByProfessor($profesor_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE profesor_id = :profesor_id AND estado = 'active'
                  ORDER BY carrera ASC, semestre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":profesor_id", $profesor_id);
        $stmt->execute();
        
        return $stmt;
    }
}
?>
