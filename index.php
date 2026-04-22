<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos necesarios - CORREGIR RUTA
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Router
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Si no está logueado, redirigir a login (excepto página login)
if (!isset($_SESSION['usuario_id']) && $page != 'login' && $page != 'api/buscar') {
    header('Location: index.php?page=login');
    exit;
}
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
    <?php if ($page != 'login' && $page != 'api/buscar'): ?>
        <?php include __DIR__ . '/views/layouts/header.php'; ?>
    <?php endif; ?>
    
    <div class="container mt-4">
        <?php
        $mensaje = getMensaje();
        if ($mensaje): ?>
            <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensaje['texto']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php
        // Router
        try {
            switch($page) {
                case 'login':
                    require_once __DIR__ . '/controllers/AuthController.php';
                    $controller = new AuthController();
                    $controller->login();
                    break;
                    
                case 'logout':
                    require_once __DIR__ . '/controllers/AuthController.php';
                    $controller = new AuthController();
                    $controller->logout();
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
                    
                case 'prestamo/crear':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->crear();
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
                    
                case 'reportes':
                    require_once __DIR__ . '/controllers/PrestamoController.php';
                    $controller = new PrestamoController();
                    $controller->reportes();
                    break;
                    
                default:
                    // Dashboard principal
                    echo '<div class="text-center mt-5">';
                    echo '<h1><i class="bi bi-folder-fill" style="color: #1a237e;"></i></h1>';
                    echo '<h1 class="mt-3">Sistema de Archivo Fiscal</h1>';
                    echo '<p class="lead text-muted">Ministerio Público</p>';
                    echo '<hr class="my-4">';
                    echo '<div class="row mt-4 justify-content-center">';
                    echo '<div class="col-md-3 mb-3"><a href="?page=buscar" class="btn btn-primary btn-lg w-100 py-3"><i class="bi bi-search"></i><br>Buscar Carpeta</a></div>';
                    echo '<div class="col-md-3 mb-3"><a href="?page=registrar" class="btn btn-success btn-lg w-100 py-3"><i class="bi bi-plus-circle"></i><br>Registrar</a></div>';
                    echo '<div class="col-md-3 mb-3"><a href="?page=prestamo" class="btn btn-warning btn-lg w-100 py-3"><i class="bi bi-journal-plus"></i><br>Préstamo</a></div>';
                    echo '<div class="col-md-3 mb-3"><a href="?page=alertas" class="btn btn-danger btn-lg w-100 py-3"><i class="bi bi-exclamation-triangle"></i><br>Alertas</a></div>';
                    echo '</div>';
                    echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<h5>Error:</h5>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<p><strong>Archivo:</strong> ' . $e->getFile() . ' (línea ' . $e->getLine() . ')</p>';
            echo '</div>';
        }
        ?>
    </div>
    
    <?php if ($page != 'login' && $page != 'api/buscar'): ?>
        <?php include __DIR__ . '/views/layouts/footer.php'; ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>