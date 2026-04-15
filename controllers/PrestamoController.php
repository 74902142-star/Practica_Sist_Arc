<?php
require_once __DIR__ . '/../models/Prestamo.php';
require_once __DIR__ . '/../models/Carpeta.php';
require_once __DIR__ . '/../models/Dependencia.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../includes/functions.php';

class PrestamoController {
    
    // NUEVO PRÉSTAMO - MOSTRAR FORMULARIO
    public function nuevo() {
        verificarLogin();
        
        $dependencia = new Dependencia();
        $dependencias = $dependencia->obtenerTodas();
        
        $carpeta = new Carpeta();
        $carpetas = $carpeta->obtenerDisponibles();
        
        include __DIR__ . '/../views/prestamos/nuevo.php';
    }

    // PROCESAR CREACIÓN DE PRÉSTAMO
    public function crear() {
        verificarLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $prestamo = new Prestamo();
            
            $datos = [
                'dependencia_id' => $_POST['dependencia_id'],
                'usuario_solicitante' => $_SESSION['usuario_id'],
                'plazo' => $_POST['plazo'],
                'carpetas' => $_POST['carpetas'] ?? []
            ];
            
            if (empty($datos['carpetas'])) {
                $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => '⚠️ Debe seleccionar al menos una carpeta'];
                header('Location: index.php?page=prestamo');
                exit;
            }
            
            $resultado = $prestamo->crear($datos);
            
            if ($resultado['success']) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'success', 
                    'texto' => "✅ Préstamo registrado. Guía: {$resultado['guia']} - Plazo: {$resultado['plazo']} días"
                ];
            } else {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger', 
                    'texto' => '❌ Error: ' . $resultado['error']
                ];
            }
            
            // Redireccionar SIEMPRE al final
            header('Location: index.php?page=alertas');
            exit;
        }
        
        // Si no es POST, mostrar formulario
        $this->nuevo();
    }

    // LISTAR PRÉSTAMOS
    public function index() {
        verificarLogin();
        
        $prestamo = new Prestamo();
        $prestamos = $prestamo->obtenerTodos();
        
        include __DIR__ . '/../views/prestamos/index.php';
    }

    // CONTROL DE VENCIMIENTOS Y ALERTAS
    public function alertas() {
        verificarLogin();
        
        $prestamo = new Prestamo();
        $vencidos = $prestamo->detectarVencimientos();
        
        include __DIR__ . '/../views/prestamos/alertas.php';
    }

    // GENERAR NOTA DE DEVOLUCIÓN Y ENVIAR POR CORREO
    public function generarNota() {
        verificarLogin();
        
        $id = $_GET['id'] ?? 0;
        $enviarCorreo = isset($_GET['enviar']) ? true : false;
        $correoDestino = $_GET['correo'] ?? '';
        
        if ($id) {
            $prestamo = new Prestamo();
            
            // Generar notificación en el sistema
            $prestamo->generarNotificacion($id, 'Vencimiento');
            
            $datosPrestamo = $prestamo->obtenerPorId($id);
            $carpetasPrestamo = $prestamo->obtenerCarpetasPorPrestamo($id);
            
            // Si se solicita enviar por correo
            if ($enviarCorreo && !empty($correoDestino)) {
                if (file_exists(__DIR__ . '/../includes/mailer.php')) {
                    require_once __DIR__ . '/../includes/mailer.php';
                    $mailer = new Mailer();
                    $resultado = $mailer->enviarAlertaVencimiento($correoDestino, $datosPrestamo, $carpetasPrestamo);
                    
                    if ($resultado['success']) {
                        $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Nota de devolución enviada a: ' . $correoDestino];
                    } else {
                        $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => '❌ Error al enviar correo: ' . $resultado['error']];
                    }
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => '⚠️ Sistema de correo no configurado'];
                }
                
                header('Location: index.php?page=alertas');
                exit;
            }
            
            // Si no, mostrar formulario para elegir correo
            include __DIR__ . '/../views/prestamos/enviar_nota.php';
        } else {
            header('Location: index.php?page=alertas');
            exit;
        }
    }

    // REPORTES
    public function reportes() {
        verificarLogin();
        
        $prestamo = new Prestamo();
        
        $porDependencia = $prestamo->reportePorDependencia();
        $vencidos = $prestamo->reporteVencidos();
        $historial = $prestamo->historialPrestamos();
        
        include __DIR__ . '/../views/reportes/index.php';
    }
    
    // VER DETALLE DE PRÉSTAMO
    public function detalle() {
        verificarLogin();
        
        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $prestamo = new Prestamo();
            $datosPrestamo = $prestamo->obtenerPorId($id);
            $carpetas = $prestamo->obtenerCarpetasPorPrestamo($id);
            
            include __DIR__ . '/../views/prestamos/detalle.php';
        } else {
            header('Location: index.php?page=alertas');
            exit;
        }
    }
    
    // REGISTRAR DEVOLUCIÓN
    public function devolver() {
        verificarLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $prestamo_id = $_POST['prestamo_id'] ?? 0;
            $estado_carpetas = $_POST['estado_carpetas'] ?? 'Completo';
            $observaciones = $_POST['observaciones'] ?? '';
            
            if ($prestamo_id) {
                $prestamo = new Prestamo();
                $resultado = $prestamo->registrarDevolucion($prestamo_id, $_SESSION['usuario_id'], $estado_carpetas, $observaciones);
                
                if ($resultado['success']) {
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Devolución registrada exitosamente'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => '❌ Error: ' . $resultado['error']];
                }
            }
            
            header('Location: index.php?page=alertas');
            exit;
        }
        
        // Mostrar formulario de devolución
        $prestamo = new Prestamo();
        $prestamos_activos = $prestamo->getPrestamosActivos();
        include __DIR__ . '/../views/prestamos/devolucion.php';
    }
}
?>