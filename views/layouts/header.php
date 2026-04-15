<?php
$current_page = $_GET['page'] ?? 'dashboard';

// Contar alertas de vencimiento
$total_vencidos_nav = 0;
if (isset($_SESSION['usuario_id']) && file_exists(__DIR__ . '/../../models/Prestamo.php')) {
    require_once __DIR__ . '/../../models/Prestamo.php';
    try {
        $prestamoNav = new Prestamo();
        $vencidosNav = $prestamoNav->detectarVencimientos();
        $total_vencidos_nav = count($vencidosNav);
    } catch (Exception $e) {
        $total_vencidos_nav = 0;
    }
}
?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-shield-fill"></i> 
            MINISTERIO PÚBLICO
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <i class="bi bi-list" style="color: white; font-size: 1.5rem;"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Menú principal izquierda -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'dashboard' || $current_page == '') ? 'active' : ''; ?>" 
                       href="index.php">
                        <i class="bi bi-grid"></i> Dashboard
                    </a>
                </li>
                
                <!-- Dropdown Carpetas -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['buscar', 'registrar', 'importar', 'carpetas']) ? 'active' : ''; ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-folder"></i> Carpetas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=carpetas"><i class="bi bi-list-ul"></i> Ver Todas</a></li>
                        <li><a class="dropdown-item" href="?page=buscar"><i class="bi bi-search"></i> Buscar Carpeta</a></li>
                        <li><a class="dropdown-item" href="?page=registrar"><i class="bi bi-plus-circle"></i> Nueva Carpeta</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?page=importar"><i class="bi bi-file-excel"></i> Carga Masiva</a></li>
                    </ul>
                </li>
                
                <!-- Dropdown Préstamos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['prestamo', 'devolucion']) ? 'active' : ''; ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-journal"></i> Préstamos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=prestamo"><i class="bi bi-journal-plus"></i> Nuevo Préstamo</a></li>
                        <li><a class="dropdown-item" href="?page=devolucion"><i class="bi bi-arrow-return-left"></i> Devoluciones</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'alertas' ? 'active' : ''; ?> position-relative" 
                       href="?page=alertas">
                        <i class="bi bi-bell"></i> Alertas
                        <?php if ($total_vencidos_nav > 0): ?>
                            <span class="position-absolute top-25 start-75 translate-middle badge">
                                <?php echo $total_vencidos_nav; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'reportes' ? 'active' : ''; ?>" 
                       href="?page=reportes">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                </li>
                
                <!-- Admin: Usuarios -->
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['usuarios', 'usuario/registrar']) ? 'active' : ''; ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=usuarios"><i class="bi bi-list-ul"></i> Lista de Usuarios</a></li>
                        <li><a class="dropdown-item" href="?page=usuario/registrar"><i class="bi bi-person-plus"></i> Nuevo Usuario</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            
            <!-- Usuario logueado -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?>
                        <span class="badge rol-badge">
                            <?php echo ucfirst($_SESSION['usuario_rol'] ?? 'usuario'); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="?page=logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>