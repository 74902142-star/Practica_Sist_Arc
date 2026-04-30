<?php
// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$prestamo = $datosPrestamo ?? [];
$carpetas = $carpetasPrestamo ?? [];
$diasVencido = calcularDiasVencidos($prestamo['fecha_devolucion_esperada'] ?? '');
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-envelope-exclamation"></i> 
                        Enviar Nota de Devolución por Correo Electrónico
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Información del préstamo -->
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle-fill"></i> Préstamo Vencido</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="200"><strong>Número de Guía:</strong></td>
                                <td><span class="badge bg-danger p-2"><?php echo htmlspecialchars($prestamo['numero_guia']); ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Dependencia:</strong></td>
                                <td><?php echo htmlspecialchars($prestamo['dependencia_nombre']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Solicitante:</strong></td>
                                <td><?php echo htmlspecialchars($prestamo['solicitante_nombre']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de Préstamo:</strong></td>
                                <td><?php echo formatearFecha($prestamo['fecha_prestamo']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Devolución Esperada:</strong></td>
                                <td><?php echo formatearFecha($prestamo['fecha_devolucion_esperada']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Días de Vencimiento:</strong></td>
                                <td><span class="badge bg-danger p-2"><?php echo $diasVencido; ?> DÍAS</span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Carpetas pendientes -->
                    <h6><i class="bi bi-folder2-open"></i> Carpetas Pendientes de Devolución:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>
                                    <th>Imputado</th>
                                    <th>Delito</th>
                                    <th>Ubicación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carpetas as $c): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($c['numero_carpeta']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($c['imputado']); ?></td>
                                    <td><?php echo htmlspecialchars($c['delito']); ?></td>
                                    <td><small><?php echo htmlspecialchars($c['ubicacion_fisica']); ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <hr>
                    
                    <!-- Formulario para enviar correo -->
                    <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="nota">
                        <input type="hidden" name="id" value="<?php echo $prestamo['id']; ?>">
                        <input type="hidden" name="enviar" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope-fill"></i> Correo electrónico de destino:
                            </label>
                            <input type="email" name="correo" class="form-control form-control-lg" 
                                   placeholder="ejemplo@mp.gob.pe" 
                                   value="<?php echo htmlspecialchars($prestamo['solicitante_email'] ?? ''); ?>"
                                   required>
                            <small class="text-muted">
                                Ingrese el correo de la dependencia o del solicitante
                            </small>
                        </div>
                        
                        <!-- Vista previa del mensaje -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Vista previa del mensaje:</strong>
                            <p class="mb-0 small mt-2">
                                <strong>Asunto:</strong> 
                                "⚠️ URGENTE: Nota de Devolución - Préstamo Vencido - Guía <?php echo $prestamo['numero_guia']; ?>"
                            </p>
                            <p class="mb-0 small">
                                <strong>Contenido:</strong> 
                                El correo incluirá el detalle completo del préstamo, la lista de carpetas pendientes, 
                                los días de vencimiento y la nota de devolución oficial en formato HTML.
                            </p>
                            <p class="mb-0 small mt-2">
                                <strong>Documento adjunto:</strong> 
                                Se adjuntará la nota de devolución en formato HTML.
                            </p>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-shield-fill-exclamation"></i>
                            <strong>Importante:</strong>
                            <p class="mb-0 small">Asegúrese de ingresar un correo válido. El sistema registrará el envío en el historial de notificaciones.</p>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-send-fill"></i> Enviar Nota por Correo
                            </button>
                            <a href="index.php?page=nota&id=<?php echo $prestamo['id']; ?>" 
                               class="btn btn-outline-secondary btn-lg" target="_blank">
                                <i class="bi bi-file-text"></i> Ver Documento
                            </a>
                            <a href="index.php?page=alertas" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Volver a Alertas
                            </a>
                        </div>
                    </form>
                    
                </div>
            </div>
            
            <!-- Instrucciones adicionales -->
            <div class="card mt-3 shadow-sm">
                <div class="card-body">
                    <h6><i class="bi bi-question-circle"></i> Información adicional</h6>
                    <p class="small mb-0">
                        <i class="bi bi-check-circle text-success"></i> 
                        El correo se enviará desde: <strong><?php echo defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'sistema@mp.gob.pe'; ?></strong>
                    </p>
                    <p class="small mb-0">
                        <i class="bi bi-clock-history"></i> 
                        Se registrará en el historial de notificaciones del sistema.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>