<?php
require_once __DIR__ . '/../config/database.php';

class Dependencia {
    private $conn;
    private $table = "dependencia";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerTodas() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 1 ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>