<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

class Carpeta {
    private $conn;
    private $table = "carpeta_fiscal";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // REGISTRO DE CARPETAS (Requerimiento 1)
    public function registrar($datos) {
        $query = "INSERT INTO " . $this->table . " 
                  (numero_carpeta, imputado, delito, agravado, estado, ubicacion_fisica, observaciones, usuario_creacion)
                  VALUES (:num, :imp, :del, :agr, :est, :ubi, :obs, :user)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':num', $datos['numero_carpeta']);
        $stmt->bindParam(':imp', $datos['imputado']);
        $stmt->bindParam(':del', $datos['delito']);
        $stmt->bindParam(':agr', $datos['agravado']);
        $stmt->bindParam(':est', $datos['estado']);
        $stmt->bindParam(':ubi', $datos['ubicacion_fisica']);
        $stmt->bindParam(':obs', $datos['observaciones']);
        $stmt->bindParam(':user', $_SESSION['usuario_id']);
        
        if ($stmt->execute()) {
            registrarAuditoria($_SESSION['usuario_id'], 'INSERT', 'carpeta_fiscal', 
                              $this->conn->lastInsertId(), null, $datos);
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'error' => 'Error al registrar'];
    }

    // CONSULTA DE UBICACIÓN (Requerimiento 3)
    public function buscarPorNumero($numero) {
        $query = "SELECT c.*, 
                         CASE 
                            WHEN EXISTS(SELECT 1 FROM detalle_prestamo dp 
                                       JOIN prestamo p ON dp.prestamo_id = p.id 
                                       WHERE dp.carpeta_id = c.id AND dp.estado = 'Prestado' AND p.estado = 'Activo') 
                            THEN 'En préstamo'
                            ELSE c.estado 
                         END as estado_actual
                  FROM " . $this->table . " c
                  WHERE c.numero_carpeta = :num";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':num', $numero);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar duplicado
    public function existeNumero($numero) {
        $query = "SELECT id FROM " . $this->table . " WHERE numero_carpeta = :num";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':num', $numero);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // CARGA MASIVA DESDE EXCEL/CSV (Requerimiento 2)
    // LOS DUPLICADOS SON RECHAZADOS, NO ACTUALIZADOS
    public function cargaMasiva($registros) {
        $insertados = 0;
        $duplicados = 0;
        $errores = [];
        $duplicados_lista = [];
        
        try {
            foreach ($registros as $index => $row) {
                $fila_actual = $index + 2; // +2 por encabezados y base 0
                
                // Validar campos obligatorios
                if (empty($row['numero_carpeta']) || empty($row['imputado']) || 
                    empty($row['delito']) || empty($row['ubicacion_fisica'])) {
                    $errores[] = "❌ Fila {$fila_actual}: Faltan campos obligatorios (número, imputado, delito o ubicación)";
                    continue;
                }
                
                // VERIFICAR SI YA EXISTE LA CARPETA (DUPLICADO)
                if ($this->existeNumero($row['numero_carpeta'])) {
                    $duplicados++;
                    $duplicados_lista[] = $row['numero_carpeta'];
                    $errores[] = "⚠️ Fila {$fila_actual}: Número de carpeta DUPLICADO - '{$row['numero_carpeta']}' ya existe en el sistema. NO se importó.";
                    continue;
                }
                
                // Establecer valores por defecto
                $estado = !empty($row['estado']) ? $row['estado'] : 'Activo';
                $agravado = isset($row['agravado']) ? $row['agravado'] : '';
                $observaciones = isset($row['observaciones']) ? $row['observaciones'] : '';
                
                // INSERTAR nuevo registro
                $query = "INSERT INTO " . $this->table . " 
                          (numero_carpeta, imputado, delito, agravado, estado, ubicacion_fisica, observaciones, usuario_creacion)
                          VALUES 
                          (:numero, :imputado, :delito, :agravado, :estado, :ubicacion, :observaciones, :usuario)";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':numero', $row['numero_carpeta']);
                $stmt->bindParam(':imputado', $row['imputado']);
                $stmt->bindParam(':delito', $row['delito']);
                $stmt->bindParam(':agravado', $agravado);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':ubicacion', $row['ubicacion_fisica']);
                $stmt->bindParam(':observaciones', $observaciones);
                $stmt->bindParam(':usuario', $_SESSION['usuario_id']);
                
                if ($stmt->execute()) {
                    $insertados++;
                } else {
                    $errores[] = "❌ Fila {$fila_actual}: Error al insertar '{$row['numero_carpeta']}'";
                }
            }
            
            // Registrar en auditoría
            if (function_exists('registrarAuditoria')) {
                registrarAuditoria($_SESSION['usuario_id'], 'IMPORTAR', 'carpeta_fiscal', 0, null, [
                    'insertados' => $insertados,
                    'duplicados' => $duplicados,
                    'total_procesados' => count($registros)
                ]);
            }
            
            return [
                'success' => true,
                'insertados' => $insertados,
                'duplicados' => $duplicados,
                'duplicados_lista' => $duplicados_lista,
                'errores' => $errores,
                'total_procesados' => count($registros)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    // Obtener todas las carpetas
    public function obtenerTodas() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener disponibles para préstamo
    public function obtenerDisponibles() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE estado = 'Activo' 
                  ORDER BY numero_carpeta";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar por estado
    public function contarPorEstado() {
        $query = "SELECT estado, COUNT(*) as total FROM " . $this->table . " GROUP BY estado";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar estado
    public function actualizarEstado($id, $estado) {
        $query = "UPDATE " . $this->table . " SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>