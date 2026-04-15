<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

class Usuario {
    private $conn;
    private $table = "usuario";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function login($email, $password) {
        // Credenciales por defecto
        if ($email == 'admin@mp.gob.pe' && $password == 'admin123') {
            return [
                'success' => true,
                'usuario' => [
                    'id' => 1,
                    'nombre' => 'Administrador del Sistema',
                    'email' => 'admin@mp.gob.pe',
                    'rol' => 'admin'
                ]
            ];
        }
        
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            $update = "UPDATE " . $this->table . " SET ultimo_acceso = NOW() WHERE id = :id";
            $stmt_upd = $this->conn->prepare($update);
            $stmt_upd->bindParam(':id', $usuario['id']);
            $stmt_upd->execute();
            
            return ['success' => true, 'usuario' => $usuario];
        }
        
        return ['success' => false, 'error' => 'Credenciales incorrectas'];
    }

    // NUEVO MÉTODO: REGISTRAR USUARIO
    public function registrar($datos) {
        // Validar email único
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'error' => 'El correo electrónico ya está registrado'];
        }
        
        // Hash de contraseña
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        // Insertar usuario
        $query = "INSERT INTO " . $this->table . " (nombre, email, password, rol) 
                  VALUES (:nombre, :email, :password, :rol)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':rol', $datos['rol']);
        
        if ($stmt->execute()) {
            registrarAuditoria($_SESSION['usuario_id'], 'INSERT', 'usuario', 
                              $this->conn->lastInsertId(), null, $datos);
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }
        
        return ['success' => false, 'error' => 'Error al registrar usuario'];
    }

    public function obtenerTodos() {
        $query = "SELECT id, nombre, email, rol, estado, ultimo_acceso, fecha_creacion 
                  FROM " . $this->table . " 
                  WHERE estado = 1 
                  ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT id, nombre, email, rol, estado FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>