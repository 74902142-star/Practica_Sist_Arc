<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1a237e;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-folder-fill"></i> Archivo Fiscal MP
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'dashboard' || ($_GET['page'] ?? '') == '' ? 'active' : ''; ?>" href="index.php">
                        <i class="bi bi-house"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'buscar' ? 'active' : ''; ?>" href="?page=buscar">
                        <i class="bi bi-search"></i> Buscar Carpeta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'registrar' ? 'active' : ''; ?>" href="?page=registrar">
                        <i class="bi bi-plus-circle"></i> Registrar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'importar' ? 'active' : ''; ?>" href="?page=importar">
                        <i class="bi bi-file-excel"></i> Carga Masiva
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'prestamo' ? 'active' : ''; ?>" href="?page=prestamo">
                        <i class="bi bi-journal-plus"></i> Préstamo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'alertas' ? 'active' : ''; ?>" href="?page=alertas">
                        <i class="bi bi-exclamation-triangle"></i> Alertas
                        <?php
                        // Contar vencidos para badge
                        if (file_exists(__DIR__ . '/../../models/Prestamo.php')) {
                            require_once __DIR__ . '/../../models/Prestamo.php';
                            $prestamoNav = new Prestamo();
                            $vencidosNav = $prestamoNav->detectarVencimientos();
                            $total_vencidos_nav = count($vencidosNav);
                            if ($total_vencidos_nav > 0): ?>
                                <span class="badge bg-danger"><?php echo $total_vencidos_nav; ?></span>
                            <?php endif;
                        }
                        ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') == 'reportes' ? 'active' : ''; ?>" href="?page=reportes">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($_SESSION['usuario_rol'] ?? ''); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="?page=logout">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>