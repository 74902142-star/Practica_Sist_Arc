<?php
require_once __DIR__ . '/../../models/Carpeta.php';
$carpeta = new Carpeta();
$carpetas = $carpeta->obtenerTodas();

// Estadísticas
$total = count($carpetas);
$activas = count(array_filter($carpetas, fn($c) => $c['estado'] == 'Activo'));
$prestadas = count(array_filter($carpetas, fn($c) => $c['estado'] == 'En préstamo'));
$archivadas = count(array_filter($carpetas, fn($c) => $c['estado'] == 'Archivado'));
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-folder" style="font-size: 2rem;"></i>
                <h5 class="mt-2">Total Carpetas</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                <h5 class="mt-2">Activas</h5>
                <h2><?php echo $activas; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-journal-bookmark" style="font-size: 2rem;"></i>
                <h5 class="mt-2">En Préstamo</h5>
                <h2><?php echo $prestadas; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="bi bi-archive" style="font-size: 2rem;"></i>
                <h5 class="mt-2">Archivadas</h5>
                <h2><?php echo $archivadas; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Listado de Carpetas Fiscales</h5>
        <div>
            <a href="?page=registrar" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Nueva Carpeta
            </a>
            <a href="?page=importar" class="btn btn-light btn-sm">
                <i class="bi bi-file-excel"></i> Importar Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Imputado</th>
                        <th>Delito</th>
                        <th>Agravado</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carpetas as $c): ?>
                    <tr>
                        <td><strong><?php echo $c['numero_carpeta']; ?></strong></td>
                        <td><?php echo $c['imputado']; ?></td>
                        <td><?php echo $c['delito']; ?></td>
                        <td>
                            <?php if ($c['agravado'] == 'Si'): ?>
                                <span class="badge bg-danger">Sí</span>
                            <?php elseif ($c['agravado'] == 'No'): ?>
                                <span class="badge bg-secondary">No</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $clase = match($c['estado']) {
                                'Activo' => 'success',
                                'En préstamo' => 'warning',
                                'Vencido' => 'danger',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?php echo $clase; ?>"><?php echo $c['estado']; ?></span>
                        </td>
                        <td><small><?php echo $c['ubicacion_fisica']; ?></small></td>
                        <td>
                            <a href="?page=buscar&numero=<?php echo $c['numero_carpeta']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>