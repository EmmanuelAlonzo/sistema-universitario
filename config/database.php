<?php
/**
 * Configuración de la Base de Datos
 * Archivo de configuración central para la conexión a MySQL
 */

class Database {
    private $host = "localhost";
    private $db_name = "sistema_universitario";
    private $username = "root";
    private $password = "";
    public $conn;

    /**
     * Obtener conexión a la base de datos
     * @return PDO|null Objeto de conexión PDO o null en caso de error
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
