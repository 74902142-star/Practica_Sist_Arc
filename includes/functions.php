<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =============================================
// FUNCIONES DE AUTENTICACIÓN
// =============================================

function verificarLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
}

// =============================================
// FUNCIONES DE PRÉSTAMO
// =============================================

function generarNumeroGuia() {
    require_once __DIR__ . '/../config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT MAX(CAST(SUBSTRING(numero_guia, 7) AS UNSIGNED)) as max_num 
              FROM prestamo WHERE numero_guia LIKE 'PREST-%'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $num = $row['max_num'] ? $row['max_num'] + 1 : 1;
    return 'PREST-' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

// =============================================
// FUNCIONES DE FECHA
// =============================================

function formatearFecha($fecha, $formato = 'd/m/Y') {
    if (empty($fecha)) return '';
    return date($formato, strtotime($fecha));
}

function calcularDiasVencidos($fecha_esperada) {
    if (empty($fecha_esperada)) return 0;
    $fecha1 = new DateTime($fecha_esperada);
    $fecha2 = new DateTime();
    if ($fecha2 > $fecha1) {
        return $fecha1->diff($fecha2)->days;
    }
    return 0;
}

// =============================================
// FUNCIONES DE MENSAJES
// =============================================

function mostrarMensaje($tipo, $texto) {
    $_SESSION['mensaje'] = ['tipo' => $tipo, 'texto' => $texto];
}

function getMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $m = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        return $m;
    }
    return null;
}

// =============================================
// FUNCIÓN DE AUDITORÍA
// =============================================

function registrarAuditoria($usuario_id, $accion, $tabla, $registro_id, $datos_anteriores = null, $datos_nuevos = null) {
    require_once __DIR__ . '/../config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "INSERT INTO auditoria (usuario_id, accion, tabla_afectada, registro_id, datos_anteriores, datos_nuevos, ip)
              VALUES (:uid, :accion, :tabla, :rid, :dant, :dnue, :ip)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':uid', $usuario_id);
    $stmt->bindValue(':accion', $accion);
    $stmt->bindValue(':tabla', $tabla);
    $stmt->bindValue(':rid', $registro_id);
    $stmt->bindValue(':dant', json_encode($datos_anteriores));
    $stmt->bindValue(':dnue', json_encode($datos_nuevos));
    $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
    
    return $stmt->execute();
}

// =============================================
// FUNCIONES DE VALIDACIÓN
// =============================================

function validarNumeroCarpeta($numero) {
    return preg_match('/^[A-Z0-9\-]+$/i', $numero);
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// =============================================
// FUNCIONES DE REDIRECCIÓN
// =============================================

function redirect($url) {
    header("Location: $url");
    exit;
}

function redirectWithMessage($url, $tipo, $mensaje) {
    mostrarMensaje($tipo, $mensaje);
    redirect($url);
}
?>