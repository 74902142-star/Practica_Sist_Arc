<?php
$porDependencia = $porDependencia ?? [];
$vencidos = $vencidos ?? [];
$historial = $historial ?? [];
?>

<div class="row">
    <div class="col-md-12">
        <h3><i class="bi bi-bar-chart-fill"></i> Reportes del Sistema</h3>
        <hr>
    </div>
</div>

<!-- Reporte 1: Carpetas prestadas por dependencia -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-building"></i> Carpetas Prestadas por Dependencia
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Dependencia</th>
                                <th class="text-center">Total Préstamos</th>
                                <th class="text-center">Activos</th>
                                <th class="text-center">Vencidos</th>
                                <th class="text-center">Devueltos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($porDependencia as $d): ?>
                            <tr>
                                <td><strong><?php echo $d['dependencia']; ?></strong></td>
                                <td class="text-center"><?php echo $d['total_prestamos'] ?? 0; ?></td>
                                <td class="text-center">
                                    <span class="badge bg-warning"><?php echo $d['activos'] ?? 0; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger"><?php echo $d['vencidos'] ?? 0; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success"><?php echo $d['devueltos'] ?? 0; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reporte 2: Carpetas vencidas -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i> Carpetas Vencidas
                </h5>
            </div>
            <div class="card-body">
                <?php if (count($vencidos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Guía</th>
                                <th>Dependencia</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Esperada</th>
                                <th>Días Vencido</th>
                                <th>Carpetas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vencidos as $v): ?>
                            <tr class="table-danger">
                                <td><strong><?php echo $v['numero_guia']; ?></strong></td>
                                <td><?php echo $v['dependencia_nombre']; ?></td>
                                <td><?php echo formatearFecha($v['fecha_prestamo']); ?></td>
                                <td><?php echo formatearFecha($v['fecha_devolucion_esperada']); ?></td>
                                <td class="fw-bold"><?php echo $v['dias_vencido']; ?> días</td>
                                <td><?php echo $v['total_carpetas']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p class="text-success"><i class="bi bi-check-circle"></i> No hay carpetas vencidas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reporte 3: Historial de préstamos -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i> Historial de Préstamos
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Guía</th>
                                <th>Dependencia</th>
                                <th>Solicitante</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Esperada</th>
                                <th>Devolución Real</th>
                                <th>Estado</th>
                                <th>Carpetas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $h): ?>
                            <tr>
                                <td><strong><?php echo $h['numero_guia']; ?></strong></td>
                                <td><?php echo $h['dependencia_nombre']; ?></td>
                                <td><?php echo $h['solicitante_nombre']; ?></td>
                                <td><?php echo formatearFecha($h['fecha_prestamo']); ?></td>
                                <td><?php echo formatearFecha($h['fecha_devolucion_esperada']); ?></td>
                                <td>
                                    <?php echo $h['fecha_devolucion_real'] ? formatearFecha($h['fecha_devolucion_real']) : '-'; ?>
                                </td>
                                <td>
                                    <?php 
                                    $clase = match($h['estado']) {
                                        'Activo' => 'warning',
                                        'Devuelto' => 'success',
                                        'Vencido' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $clase; ?>"><?php echo $h['estado']; ?></span>
                                </td>
                                <td class="text-center"><?php echo $h['total_carpetas']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>