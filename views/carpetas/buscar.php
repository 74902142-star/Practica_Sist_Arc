<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-search"></i> Consulta de Ubicación de Carpeta Fiscal
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="text" name="numero_carpeta" id="numero_carpeta" 
                               class="form-control" placeholder="Ingrese número de carpeta fiscal" 
                               value="<?php echo $_POST['numero_carpeta'] ?? ''; ?>" required>
                        <button type="submit" name="buscar" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <small class="text-muted">Ejemplo: FISC-2024-001</small>
                </form>

                <?php if (isset($mensaje) && $mensaje == 'No ubicado'): ?>
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-circle-fill" style="font-size: 2rem;"></i>
                        <h5 class="mt-2">❌ No ubicado</h5>
                        <p>La carpeta con número <strong><?php echo $_POST['numero_carpeta']; ?></strong> no se encuentra registrada en el sistema.</p>
                    </div>
                <?php endif; ?>

                <?php if ($resultado): ?>
                <div class="alert alert-success">
                    <h5><i class="bi bi-check-circle-fill"></i> Carpeta Encontrada</h5>
                    
                    <table class="table table-bordered mt-3">
                        <tr>
                            <th width="35%" style="background-color: #e8f5e9;">
                                <i class="bi bi-folder"></i> Número de Carpeta:
                            </th>
                            <td><strong style="font-size: 1.2rem;"><?php echo $resultado['numero_carpeta']; ?></strong></td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-person"></i> Imputado:</th>
                            <td><?php echo $resultado['imputado']; ?></td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-file-text"></i> Delito:</th>
                            <td><?php echo $resultado['delito']; ?></td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-exclamation-diamond"></i> Agravado:</th>
                            <td>
                                <?php if ($resultado['agravado'] == 'Si'): ?>
                                    <span class="badge bg-danger">Sí</span>
                                <?php elseif ($resultado['agravado'] == 'No'): ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php else: ?>
                                    <?php echo $resultado['agravado'] ?: 'No especificado'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-flag"></i> Estado:</th>
                            <td>
                                <?php 
                                $estado = $resultado['estado_actual'] ?? $resultado['estado'];
                                $clase = match($estado) {
                                    'Activo' => 'success',
                                    'En préstamo' => 'warning',
                                    'Vencido' => 'danger',
                                    'Archivado' => 'secondary',
                                    default => 'info'
                                };
                                ?>
                                <span class="badge bg-<?php echo $clase; ?> p-2"><?php echo $estado; ?></span>
                            </td>
                        </tr>
                        <tr style="background-color: #d4edda;">
                            <th>
                                <i class="bi bi-geo-alt-fill"></i> Ubicación Física:
                            </th>
                            <td>
                                <strong style="font-size: 1.3rem; color: #155724;">
                                    📍 <?php echo $resultado['ubicacion_fisica']; ?>
                                </strong>
                            </td>
                        </tr>
                        <?php if (!empty($resultado['observaciones'])): ?>
                        <tr>
                            <th><i class="bi bi-chat"></i> Observaciones:</th>
                            <td><?php echo $resultado['observaciones']; ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <?php endif; ?>
                
                <!-- Búsqueda rápida con AJAX -->
                <div class="mt-4">
                    <h6><i class="bi bi-lightning"></i> Búsqueda Rápida (AJAX)</h6>
                    <div class="input-group">
                        <input type="text" id="busqueda_ajax" class="form-control" 
                               placeholder="Escriba número de carpeta...">
                        <button class="btn btn-outline-primary" onclick="buscarAjax()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div id="resultado_ajax" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function buscarAjax() {
    const numero = document.getElementById('busqueda_ajax').value;
    if (!numero) return;
    
    fetch('index.php?page=api/buscar&numero=' + encodeURIComponent(numero))
        .then(r => r.json())
        .then(data => {
            const div = document.getElementById('resultado_ajax');
            if (data.success) {
                div.innerHTML = `
                    <div class="alert alert-success">
                        <strong>📍 Ubicación:</strong> ${data.carpeta.ubicacion_fisica}<br>
                        <strong>Estado:</strong> ${data.carpeta.estado_actual || data.carpeta.estado}
                    </div>
                `;
            } else {
                div.innerHTML = `
                    <div class="alert alert-warning">
                        <strong>❌ ${data.mensaje}</strong>
                    </div>
                `;
            }
        });
}

// Buscar con Enter
document.getElementById('busqueda_ajax').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') buscarAjax();
});
</script>