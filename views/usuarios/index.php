<?php
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: index.php');
    exit;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #8B0000; border-bottom: 2px solid #DAA520; padding-bottom: 10px;">
            <i class="bi bi-people-fill"></i> Gestión de Usuarios
        </h2>
        <a href="index.php?page=usuario/registrar" class="btn" style="background: linear-gradient(135deg, #8B0000 0%, #5C0000 100%); color: white; border: 1px solid #DAA520;">
            <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
        </a>
    </div>
    
    <div class="card shadow">
        <div class="card-header" style="background: linear-gradient(90deg, #8B0000 0%, #A52A2A 100%); color: white; border-bottom: 2px solid #DAA520;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Usuarios Registrados</h5>
        </div>
        <div class="card-body" style="background-color: #FFF8DC;">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: linear-gradient(90deg, #8B0000 0%, #A52A2A 100%); color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Último Acceso</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <?php 
                                $rol_class = match($u['rol']) {
                                    'admin' => 'danger',
                                    'fiscal' => 'warning',
                                    'archivo' => 'info',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $rol_class; ?>">
                                    <?php echo ucfirst($u['rol']); ?>
                                </span>
                            </td>
                            <td><?php echo $u['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($u['ultimo_acceso'])) : 'Nunca'; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($u['fecha_creacion'])); ?></td>
                            <td>
                                <span class="badge bg-success">Activo</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>