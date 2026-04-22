<?php
$vencidos = $vencidos ?? [];
$total_vencidos = count($vencidos);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">
                    <i class="bi bi-exclamation-triangle-fill"></i> 
                    Control de Vencimientos - Alertas Automáticas
                </h4>
            </div>
            <div class="card-body">
                <?php if ($total_vencidos > 0): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-bell-fill"></i>
                        <strong>¡Atención!</strong> Se han detectado <strong><?php echo $total_vencidos; ?></strong> 
                        préstamo(s) vencido(s) que requieren atención inmediata.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Guía</th>
                                    <th>Dependencia</th>
                                    <th>Solicitante</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Devolución Esperada</th>
                                    <th>Días Vencido</th>
                                    <th>Carpetas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vencidos as $v): ?>
                                <tr class="table-danger">
                                    <td><strong><?php echo $v['numero_guia']; ?></strong></td>
                                    <td><?php echo $v['dependencia_nombre']; ?></td>
                                    <td><?php echo $v['solicitante_nombre']; ?></td>
                                    <td><?php echo formatearFecha($v['fecha_prestamo']); ?></td>
                                    <td class="fw-bold"><?php echo formatearFecha($v['fecha_devolucion_esperada']); ?></td>
                                    <td>
                                        <span class="badge bg-danger p-2">
                                            <?php echo $v['dias_vencido']; ?> días
                                        </span>
                                    </td>
                                    <td><?php echo $v['total_carpetas']; ?></td>
                                    <td>
                                        <a href="?page=nota&id=<?php echo $v['id']; ?>" 
                                           class="btn btn-warning btn-sm" target="_blank">
                                            <i class="bi bi-file-text"></i> Generar Nota de Devolución
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Ejemplo del caso práctico -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="bi bi-lightbulb"></i> Ejemplo según práctica:</h6>
                        <p>
                            "Día 8 → sistema alerta vencimiento. Se genera nota de devolución."<br>
                            <strong>Acción:</strong> El sistema identifica automáticamente los préstamos que han excedido el plazo.
                        </p>
                    </div>
                    
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>No hay préstamos vencidos.</strong> Todos los préstamos están dentro del plazo establecido.
                    </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="bi bi-clock-history"></i> Verificación Automática</h6>
                                <p class="mb-0 small">El sistema verifica diariamente los vencimientos y actualiza los estados automáticamente.</p>
                                <p class="mb-0 small mt-2">
                                    <strong>Última verificación:</strong> <?php echo date('d/m/Y H:i:s'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="bi bi-file-pdf"></i> Nota de Devolución</h6>
                                <p class="mb-0 small">Si un préstamo excede el plazo, el sistema permite generar un documento de notificación para solicitar la devolución inmediata.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>