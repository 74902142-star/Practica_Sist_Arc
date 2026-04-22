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
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-house"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=buscar">
                        <i class="bi bi-search"></i> Buscar Carpeta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=registrar">
                        <i class="bi bi-plus-circle"></i> Registrar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=importar">
                        <i class="bi bi-file-excel"></i> Carga Masiva
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=prestamo">
                        <i class="bi bi-journal-plus"></i> Préstamo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="?page=alertas">
                        <i class="bi bi-exclamation-triangle"></i> Alertas
                        <?php
                        // Contar vencidos para badge
                        require_once __DIR__ . '/../../models/Prestamo.php';
                        $prestamo = new Prestamo();
                        $vencidos = $prestamo->detectarVencimientos();
                        $total_vencidos = count($vencidos);
                        if ($total_vencidos > 0): ?>
                            <span class="badge bg-danger"><?php echo $total_vencidos; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=reportes">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?>
                        <span class="badge bg-secondary"><?php echo $_SESSION['usuario_rol'] ?? ''; ?></span>
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