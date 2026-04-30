<?php
require_once __DIR__ . '/../models/Prestamo.php';
require_once __DIR__ . '/../models/Carpeta.php';
require_once __DIR__ . '/../models/Dependencia.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../includes/functions.php';

class PrestamoController {
    
    // NUEVO PRÉSTAMO (Requerimiento 4)
    public function nuevo() {
        verificarLogin();
        
        $dependencia = new Dependencia();
        $dependencias = $dependencia->obtenerTodas();
        
        $carpeta = new Carpeta();
        $carpetas = $carpeta->obtenerDisponibles();
        
        include __DIR__ . '/../views/prestamos/nuevo.php';
    }

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
                mostrarMensaje('warning', '⚠️ Debe seleccionar al menos una carpeta');
                header('Location: index.php?page=prestamo');
                exit;
            }
            
            $resultado = $prestamo->crear($datos);
            
            if ($resultado['success']) {
                mostrarMensaje('success', "✅ Préstamo registrado. Guía: {$resultado['guia']} - Plazo: {$resultado['plazo']} días");
            } else {
                mostrarMensaje('danger', '❌ Error: ' . $resultado['error']);
            }
        }
        
        header('Location: index.php?page=prestamo');
        exit;
    }

    // LISTAR PRÉSTAMOS
    public function index() {
        verificarLogin();
        
        $prestamo = new Prestamo();
        $prestamos = $prestamo->obtenerTodos();
        
        include __DIR__ . '/../views/prestamos/index.php';
    }

    // CONTROL DE VENCIMIENTOS Y ALERTAS (Requerimiento 5)
    public function alertas() {
        verificarLogin();
        
        $prestamo = new Prestamo();
        $vencidos = $prestamo->detectarVencimientos();
        
        include __DIR__ . '/../views/prestamos/alertas.php';
    }

    // GENERAR NOTA DE DEVOLUCIÓN Y ENVIAR POR CORREO (Requerimiento 6)
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
                require_once __DIR__ . '/../includes/mailer.php';
                
                $mailer = new Mailer();
                $resultado = $mailer->enviarAlertaVencimiento($correoDestino, $datosPrestamo, $carpetasPrestamo);
                
                if ($resultado['success']) {
                    mostrarMensaje('success', '✅ Nota de devolución enviada exitosamente a: ' . $correoDestino);
                } else {
                    mostrarMensaje('danger', '❌ Error al enviar correo: ' . $resultado['error']);
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

    // REPORTES (Requerimiento 7)
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
            header('Location: index.php?page=prestamos');
            exit;
        }
    }
}
?>