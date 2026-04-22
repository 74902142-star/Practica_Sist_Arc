<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

class Prestamo {
    private $conn;
    private $table = "prestamo";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // PRÉSTAMO DE CARPETAS (Requerimiento 4)
    public function crear($datos) {
        $this->conn->beginTransaction();
        
        try {
            $numero_guia = generarNumeroGuia();
            
            // Insertar préstamo
            $query = "INSERT INTO " . $this->table . " 
                      (numero_guia, dependencia_id, usuario_solicitante, fecha_prestamo, 
                       fecha_devolucion_esperada, estado, usuario_registro)
                      VALUES (:guia, :dep, :sol, CURDATE(), DATE_ADD(CURDATE(), INTERVAL :plazo DAY), 'Activo', :reg)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':guia', $numero_guia);
            $stmt->bindParam(':dep', $datos['dependencia_id']);
            $stmt->bindParam(':sol', $datos['usuario_solicitante']);
            $stmt->bindParam(':plazo', $datos['plazo']);
            $stmt->bindParam(':reg', $_SESSION['usuario_id']);
            $stmt->execute();
            
            $prestamo_id = $this->conn->lastInsertId();
            
            // Insertar detalles y actualizar carpetas
            $query_det = "INSERT INTO detalle_prestamo (prestamo_id, carpeta_id) VALUES (:pid, :cid)";
            $stmt_det = $this->conn->prepare($query_det);
            
            $query_upd = "UPDATE carpeta_fiscal SET estado = 'En préstamo' WHERE id = :cid";
            $stmt_upd = $this->conn->prepare($query_upd);
            
            foreach ($datos['carpetas'] as $carpeta_id) {
                $stmt_det->bindParam(':pid', $prestamo_id);
                $stmt_det->bindParam(':cid', $carpeta_id);
                $stmt_det->execute();
                
                $stmt_upd->bindParam(':cid', $carpeta_id);
                $stmt_upd->execute();
            }
            
            $this->conn->commit();
            
            registrarAuditoria($_SESSION['usuario_id'], 'INSERT', 'prestamo', $prestamo_id, null, $datos);
            
            return [
                'success' => true, 
                'guia' => $numero_guia, 
                'id' => $prestamo_id,
                'plazo' => $datos['plazo']
            ];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // CONTROL DE VENCIMIENTO (Requerimiento 5)
    public function detectarVencimientos() {
        // Actualizar estados vencidos
        $update = "UPDATE " . $this->table . " 
                   SET estado = 'Vencido' 
                   WHERE estado = 'Activo' 
                     AND fecha_devolucion_esperada < CURDATE()";
        $this->conn->exec($update);
        
        // Obtener vencidos para mostrar
        $query = "SELECT p.*, d.nombre as dependencia_nombre,
                         u.nombre as solicitante_nombre,
                         (SELECT COUNT(*) FROM detalle_prestamo WHERE prestamo_id = p.id AND estado = 'Prestado') as total_carpetas,
                         DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_vencido
                  FROM " . $this->table . " p
                  JOIN dependencia d ON p.dependencia_id = d.id
                  JOIN usuario u ON p.usuario_solicitante = u.id
                  WHERE p.estado = 'Vencido'
                  ORDER BY p.fecha_devolucion_esperada";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // GENERAR NOTA DE DEVOLUCIÓN (Requerimiento 6)
    public function generarNotificacion($prestamo_id, $tipo = 'Vencimiento') {
        $query = "INSERT INTO notificacion (prestamo_id, tipo, fecha_generacion, contenido, usuario_generacion)
                  VALUES (:pid, :tipo, CURDATE(), :contenido, :user)";
        
        $contenido = "Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $prestamo_id);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':user', $_SESSION['usuario_id']);
        
        return $stmt->execute();
    }

    // REPORTE: Carpetas prestadas por dependencia (Requerimiento 7)
    public function reportePorDependencia() {
        $query = "SELECT d.nombre as dependencia,
                         COUNT(p.id) as total_prestamos,
                         SUM(CASE WHEN p.estado = 'Activo' THEN 1 ELSE 0 END) as activos,
                         SUM(CASE WHEN p.estado = 'Vencido' THEN 1 ELSE 0 END) as vencidos,
                         SUM(CASE WHEN p.estado = 'Devuelto' THEN 1 ELSE 0 END) as devueltos
                  FROM dependencia d
                  LEFT JOIN " . $this->table . " p ON d.id = p.dependencia_id
                  GROUP BY d.id, d.nombre
                  ORDER BY total_prestamos DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // REPORTE: Carpetas vencidas
    public function reporteVencidos() {
        return $this->detectarVencimientos();
    }

    // REPORTE: Historial de préstamos
    public function historialPrestamos() {
        $query = "SELECT p.*, d.nombre as dependencia_nombre,
                         u.nombre as solicitante_nombre,
                         (SELECT COUNT(*) FROM detalle_prestamo WHERE prestamo_id = p.id) as total_carpetas
                  FROM " . $this->table . " p
                  JOIN dependencia d ON p.dependencia_id = d.id
                  JOIN usuario u ON p.usuario_solicitante = u.id
                  ORDER BY p.fecha_prestamo DESC
                  LIMIT 100";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener préstamo por ID
    public function obtenerPorId($id) {
        $query = "SELECT p.*, d.nombre as dependencia_nombre,
                         u.nombre as solicitante_nombre
                  FROM " . $this->table . " p
                  JOIN dependencia d ON p.dependencia_id = d.id
                  JOIN usuario u ON p.usuario_solicitante = u.id
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>