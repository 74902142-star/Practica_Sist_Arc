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

// Si no está logueado, redirigir a login (excepto página login y api)
if (!isset($_SESSION['usuario_id']) && $page != 'login' && $page != 'api/buscar') {
    header('Location: index.php?page=login');
    exit;
}

// Para el dashboard, cargar datos de carpetas
$carpetas_dashboard = [];
$estadisticas_dashboard = [];

if ($page == 'dashboard' && isset($_SESSION['usuario_id'])) {
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
        $mensaje = function_exists('getMensaje') ? getMensaje() : null;
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
                    // DASHBOARD PRINCIPAL CON LISTA DE CARPETAS
                    ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h2 class="mb-3">
                                <i class="bi bi-folder-fill" style="color: #1a237e;"></i> 
                                Sistema de Archivo Fiscal
                            </h2>
                            <p class="text-muted">Ministerio Público - Panel de Control</p>
                        </div>
                    </div>
                    
                    <!-- TARJETAS DE ESTADÍSTICAS -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50">TOTAL CARPETAS</h6>
                                            <h2 class="mb-0"><?php echo count($carpetas_dashboard); ?></h2>
                                        </div>
                                        <i class="bi bi-folder" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        $activas = 0;
                        $prestadas = 0;
                        $archivadas = 0;
                        $vencidas = 0;
                        
                        foreach ($estadisticas_dashboard as $est) {
                            if ($est['estado'] == 'Activo') $activas = $est['total'];
                            if ($est['estado'] == 'En préstamo') $prestadas = $est['total'];
                            if ($est['estado'] == 'Archivado') $archivadas = $est['total'];
                            if ($est['estado'] == 'Vencido') $vencidas = $est['total'];
                        }
                        ?>
                        
                        <div class="col-md-3">
                            <div class="card bg-success text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50">ACTIVAS</h6>
                                            <h2 class="mb-0"><?php echo $activas; ?></h2>
                                        </div>
                                        <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-warning text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50">EN PRÉSTAMO</h6>
                                            <h2 class="mb-0"><?php echo $prestadas; ?></h2>
                                        </div>
                                        <i class="bi bi-journal-bookmark" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50">ARCHIVADAS</h6>
                                            <h2 class="mb-0"><?php echo $archivadas; ?></h2>
                                        </div>
                                        <i class="bi bi-archive" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- BOTONES DE ACCIÓN RÁPIDA -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="?page=buscar" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Buscar Carpeta
                                        </a>
                                        <a href="?page=registrar" class="btn btn-success">
                                            <i class="bi bi-plus-circle"></i> Nueva Carpeta
                                        </a>
                                        <a href="?page=importar" class="btn btn-info text-white">
                                            <i class="bi bi-file-excel"></i> Carga Masiva Excel
                                        </a>
                                        <a href="?page=prestamo" class="btn btn-warning">
                                            <i class="bi bi-journal-plus"></i> Nuevo Préstamo
                                        </a>
                                        <a href="?page=alertas" class="btn btn-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Ver Alertas
                                        </a>
                                        <a href="?page=reportes" class="btn btn-secondary">
                                            <i class="bi bi-bar-chart"></i> Reportes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- LISTA DE CARPETAS -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-list-ul"></i> Listado de Carpetas Fiscales
                                    </h5>
                                    <span class="badge bg-light text-dark">
                                        <?php echo count($carpetas_dashboard); ?> registros
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (empty($carpetas_dashboard)): ?>
                                        <div class="text-center py-5">
                                            <i class="bi bi-folder-x" style="font-size: 4rem; color: #ccc;"></i>
                                            <h5 class="mt-3 text-muted">No hay carpetas registradas</h5>
                                            <p class="text-muted">Comience registrando una nueva carpeta o importando desde Excel.</p>
                                            <a href="?page=registrar" class="btn btn-primary">
                                                <i class="bi bi-plus-circle"></i> Registrar Primera Carpeta
                                            </a>
                                            <a href="?page=importar" class="btn btn-success">
                                                <i class="bi bi-file-excel"></i> Importar desde Excel
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Número de Carpeta</th>
                                                        <th>Imputado</th>
                                                        <th>Delito</th>
                                                        <th>Agravado</th>
                                                        <th>Estado</th>
                                                        <th>Ubicación Física</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $contador = 1;
                                                    foreach ($carpetas_dashboard as $c): 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $contador++; ?></td>
                                                        <td><strong><?php echo htmlspecialchars($c['numero_carpeta']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($c['imputado']); ?></td>
                                                        <td><?php echo htmlspecialchars($c['delito']); ?></td>
                                                        <td>
                                                            <?php if ($c['agravado'] == 'Si'): ?>
                                                                <span class="badge bg-danger">Sí</span>
                                                            <?php elseif ($c['agravado'] == 'No'): ?>
                                                                <span class="badge bg-secondary">No</span>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $clase = match($c['estado']) {
                                                                'Activo' => 'success',
                                                                'En préstamo' => 'warning',
                                                                'Vencido' => 'danger',
                                                                'Archivado' => 'secondary',
                                                                default => 'info'
                                                            };
                                                            ?>
                                                            <span class="badge bg-<?php echo $clase; ?>">
                                                                <?php echo htmlspecialchars($c['estado']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($c['ubicacion_fisica']); ?></small>
                                                        </td>
                                                        <td>
                                                            <a href="?page=buscar&numero=<?php echo urlencode($c['numero_carpeta']); ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="Ver detalles">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="bi bi-info-circle"></i> 
                                        Mostrando todas las carpetas registradas en el sistema.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<h5><i class="bi bi-exclamation-triangle"></i> Error en el sistema</h5>';
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