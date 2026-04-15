<?php
// Iniciar sesión SIEMPRE al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos necesarios
if (file_exists(__DIR__ . '/includes/functions.php')) {
    require_once __DIR__ . '/includes/functions.php';
}
if (file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
}

// Router
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// =============================================
// PROCESAR ACCIONES QUE REQUIEREN REDIRECCIÓN
// ANTES DE CUALQUIER SALIDA HTML
// =============================================

// LOGOUT
if ($page == 'logout') {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// PROCESAR PRÉSTAMO (POST)
if ($page == 'prestamo/crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/models/Prestamo.php';
    require_once __DIR__ . '/models/Carpeta.php';
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $prestamo = new Prestamo();
    $datos = [
        'dependencia_id' => $_POST['dependencia_id'],
        'usuario_solicitante' => $_SESSION['usuario_id'],
        'plazo' => $_POST['plazo'],
        'carpetas' => $_POST['carpetas'] ?? []
    ];
    
    if (empty($datos['carpetas'])) {
        $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => 'Debe seleccionar al menos una carpeta'];
        header('Location: index.php?page=prestamo');
        exit;
    }
    
    $resultado = $prestamo->crear($datos);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => "Préstamo registrado. Guía: {$resultado['guia']} - Plazo: {$resultado['plazo']} días"
        ];
    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'danger',
            'texto' => 'Error: ' . $resultado['error']
        ];
    }
    
    header('Location: index.php?page=alertas');
    exit;
}

// PROCESAR REGISTRO DE CARPETA (POST)
if ($page == 'registrar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/models/Carpeta.php';
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $carpeta = new Carpeta();
    $resultado = $carpeta->registrar($_POST);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Carpeta registrada exitosamente'];
    } else {
        $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $resultado['error']];
    }
    
    header('Location: index.php?page=carpetas');
    exit;
}

// PROCESAR DEVOLUCIÓN (POST)
if ($page == 'devolucion/crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/models/Prestamo.php';
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $prestamo = new Prestamo();
    $resultado = $prestamo->registrarDevolucion(
        $_POST['prestamo_id'],
        $_SESSION['usuario_id'],
        $_POST['estado_carpetas'] ?? 'Completo',
        $_POST['observaciones'] ?? ''
    );
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Devolución registrada exitosamente'];
    } else {
        $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $resultado['error']];
    }
    
    header('Location: index.php?page=alertas');
    exit;
}

// ENVÍO DE NOTA POR CORREO
if ($page == 'nota' && isset($_GET['enviar']) && isset($_GET['correo'])) {
    require_once __DIR__ . '/models/Prestamo.php';
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $id = $_GET['id'] ?? 0;
    $correoDestino = $_GET['correo'];
    
    if ($id) {
        $prestamo = new Prestamo();
        $prestamo->generarNotificacion($id, 'Vencimiento');
        
        if (file_exists(__DIR__ . '/includes/mailer.php')) {
            require_once __DIR__ . '/includes/mailer.php';
            $datosPrestamo = $prestamo->obtenerPorId($id);
            $carpetasPrestamo = $prestamo->obtenerCarpetasPorPrestamo($id);
            
            $mailer = new Mailer();
            $resultado = $mailer->enviarAlertaVencimiento($correoDestino, $datosPrestamo, $carpetasPrestamo);
            
            if ($resultado['success']) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Nota enviada a: ' . $correoDestino];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $resultado['error']];
            }
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => 'Sistema de correo no configurado'];
        }
    }
    
    header('Location: index.php?page=alertas');
    exit;
}

// IMPORTACIÓN MASIVA (POST)
if ($page == 'importar' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_excel'])) {
    require_once __DIR__ . '/models/Carpeta.php';
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $archivo = $_FILES['archivo_excel'];
    $resultado_importacion = null;
    
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (in_array($extension, ['csv', 'txt'])) {
            $file = fopen($archivo['tmp_name'], 'r');
            if ($file) {
                $datos = [];
                $fila = 0;
                while (($linea = fgetcsv($file, 0, ',')) !== false) {
                    $fila++;
                    if ($fila === 1) continue;
                    if (count($linea) >= 6 && !empty(trim($linea[0]))) {
                        $datos[] = [
                            'numero_carpeta' => trim($linea[0]),
                            'imputado' => trim($linea[1]),
                            'delito' => trim($linea[2]),
                            'agravado' => trim($linea[3] ?? ''),
                            'estado' => trim($linea[4] ?? 'Activo'),
                            'ubicacion_fisica' => trim($linea[5]),
                            'observaciones' => trim($linea[6] ?? '')
                        ];
                    }
                }
                fclose($file);
                
                if (!empty($datos)) {
                    $carpeta = new Carpeta();
                    $resultado_importacion = $carpeta->cargaMasiva($datos);
                    $_SESSION['resultado_importacion'] = $resultado_importacion;
                }
            }
        }
    }
    
    header('Location: index.php?page=importar');
    exit;
}

// Si no está logueado, redirigir a login
if (!isset($_SESSION['usuario_id']) && $page != 'login' && $page != 'api/buscar') {
    header('Location: index.php?page=login');
    exit;
}

// Para el dashboard, cargar datos
$carpetas_dashboard = [];
$estadisticas_dashboard = [];

if (($page == 'dashboard' || $page == '') && isset($_SESSION['usuario_id'])) {
    try {
        require_once __DIR__ . '/models/Carpeta.php';
        $carpetaModel = new Carpeta();
        $carpetas_dashboard = $carpetaModel->obtenerTodas();
        $estadisticas_dashboard = $carpetaModel->contarPorEstado();
    } catch (Exception $e) {
        $carpetas_dashboard = [];
        $estadisticas_dashboard = [];
    }
}

// =============================================
// AHORA SÍ, MOSTRAR HTML
// =============================================
if ($page != 'api/buscar'):
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Archivo Fiscal - Ministerio Público</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php if ($page != 'login'): ?>
        <?php include __DIR__ . '/views/layouts/header.php'; ?>
    <?php endif; ?>
    
    <div class="container mt-4">
        <?php
        // Mostrar mensajes de sesión
        if (isset($_SESSION['mensaje'])):
            $m = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        ?>
            <div class="alert alert-<?php echo $m['tipo']; ?> alert-dismissible fade show" role="alert">
                <?php echo $m['texto']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif;
        
        // Router para VISTAS (solo GET, sin redirecciones)
        try {
            switch($page) {
                case 'login':
                    require_once __DIR__ . '/controllers/AuthController.php';
                    $controller = new AuthController();
                    $controller->login();
                    break;
                    
                case 'buscar':
                    require_once __DIR__ . '/controllers/CarpetaController.php';
                    $controller = new CarpetaController();
                    $controller->buscar();
                    break;
                    
                case 'api/buscar':
                    require_once __DIR__ . '/controllers/CarpetaController.php';
                    $controller = new CarpetaController();
                    $controller->apiBuscar();
                    break;
                    
                case 'carpetas':
                    require_once __DIR__ . '/controllers/CarpetaController.php';
                    $controller = new CarpetaController();
                    $controller->index();
                    break;
                    
                case 'registrar':
                    require_once __DIR__ . '/controllers/CarpetaController.php';
                    $controller = new CarpetaController();
                    $controller->registrar();
                    break;
                    
                case 'importar':
                    require_once __DIR__ . '/controllers/CarpetaController.php';
                    $controller = new CarpetaController();
                    $controller->importar();
                    break;
                    
                case 'prestamo':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->nuevo();
                    break;
                    
                case 'alertas':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->alertas();
                    break;
                    
                case 'nota':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->generarNota();
                    break;
                    
                case 'devolucion':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->devolver();
                    break;
                    
                case 'reportes':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->reportes();
                    break;
                    
                case 'usuarios':
                    require_once __DIR__ . '/controllers/UsuarioController.php';
                    $controller = new UsuarioController();
                    $controller->index();
                    break;
                    
                case 'usuario/registrar':
                    require_once __DIR__ . '/controllers/UsuarioController.php';
                    $controller = new UsuarioController();
                    $controller->registrar();
                    break;
                    
                default:
                    // DASHBOARD
                    ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h2 style="color: #0B2B5E;">
                                <i class="bi bi-shield-fill"></i> 
                                Sistema de Archivo Fiscal - Ministerio Público
                            </h2>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value"><?php echo count($carpetas_dashboard); ?></div>
                                        <div class="stat-label">Total Carpetas</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-folder-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">
                                            <?php 
                                            $activas = 0;
                                            foreach ($estadisticas_dashboard as $est) {
                                                if ($est['estado'] == 'Activo') $activas = $est['total'];
                                            }
                                            echo $activas;
                                            ?>
                                        </div>
                                        <div class="stat-label">Activas</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">
                                            <?php 
                                            $prestadas = 0;
                                            foreach ($estadisticas_dashboard as $est) {
                                                if ($est['estado'] == 'En préstamo') $prestadas = $est['total'];
                                            }
                                            echo $prestadas;
                                            ?>
                                        </div>
                                        <div class="stat-label">En Préstamo</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">
                                            <?php 
                                            $archivadas = 0;
                                            foreach ($estadisticas_dashboard as $est) {
                                                if ($est['estado'] == 'Archivado') $archivadas = $est['total'];
                                            }
                                            echo $archivadas;
                                            ?>
                                        </div>
                                        <div class="stat-label">Archivadas</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-archive-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="bi bi-folder2-open"></i> Carpetas Recientes
                                    <a href="?page=carpetas" class="btn btn-sm btn-outline-primary">Ver todas</a>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Imputado</th>
                                                <th>Delito</th>
                                                <th>Estado</th>
                                                <th>Ubicación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($carpetas_dashboard, 0, 5) as $c): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($c['numero_carpeta']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($c['imputado']); ?></td>
                                                <td><?php echo htmlspecialchars($c['delito']); ?></td>
                                                <td>
                                                    <?php 
                                                    $clase = match($c['estado']) {
                                                        'Activo' => 'success',
                                                        'En préstamo' => 'warning',
                                                        'Archivado' => 'secondary',
                                                        default => 'info'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?php echo $clase; ?>"><?php echo $c['estado']; ?></span>
                                                </td>
                                                <td><small><?php echo htmlspecialchars($c['ubicacion_fisica']); ?></small></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<h5>Error en el sistema</h5>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
    </div>
    
    <?php if ($page != 'login'): ?>
        <?php include __DIR__ . '/views/layouts/footer.php'; ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>