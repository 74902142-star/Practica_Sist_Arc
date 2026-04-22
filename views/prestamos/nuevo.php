<?php
$dependencias = $dependencias ?? [];
$carpetas = $carpetas ?? [];
?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    <i class="bi bi-journal-plus"></i> Nuevo Préstamo de Carpetas Fiscales
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=prestamo/crear">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building"></i> Dependencia Solicitante <span class="text-danger">*</span>
                            </label>
                            <select name="dependencia_id" class="form-select" required>
                                <option value="">Seleccione dependencia</option>
                                <?php foreach ($dependencias as $d): ?>
                                <option value="<?php echo $d['id']; ?>">
                                    <?php echo $d['nombre']; ?> (<?php echo $d['codigo']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar"></i> Plazo de Préstamo <span class="text-danger">*</span>
                            </label>
                            <select name="plazo" class="form-select" required>
                                <option value="7">7 días (Estándar)</option>
                                <option value="15">15 días</option>
                                <option value="30">30 días</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Ejemplo según práctica:</strong> "Usuario de Fiscalía Penal 1 solicita préstamo. 
                        Selecciona 3 carpetas. Sistema genera: Guía PREST-001 - Plazo: 7 días"
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-folder2-open"></i> Seleccionar Carpetas <span class="text-danger">*</span>
                        </label>
                        <div class="border rounded p-2" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Número</th>
                                        <th>Imputado</th>
                                        <th>Delito</th>
                                        <th>Ubicación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($carpetas)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            No hay carpetas disponibles para préstamo
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($carpetas as $c): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="carpetas[]" 
                                                       value="<?php echo $c['id']; ?>" 
                                                       class="form-check-input chk-carpeta">
                                            </td>
                                            <td><strong><?php echo $c['numero_carpeta']; ?></strong></td>
                                            <td><?php echo $c['imputado']; ?></td>
                                            <td><small><?php echo $c['delito']; ?></small></td>
                                            <td><small><?php echo $c['ubicacion_fisica']; ?></small></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">
                            <span id="contadorSeleccionados">0</span> carpetas seleccionadas
                        </small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="bi bi-check-circle"></i> Registrar Préstamo y Generar Guía
                        </button>
                        <a href="index.php" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <h6>Proceso de Préstamo:</h6>
                <ol class="small">
                    <li>Seleccione la dependencia solicitante</li>
                    <li>Elija el plazo del préstamo</li>
                    <li>Seleccione una o varias carpetas</li>
                    <li>El sistema generará automáticamente:
                        <ul>
                            <li>Número de guía único (PREST-XXX)</li>
                            <li>Fecha de préstamo (hoy)</li>
                            <li>Fecha de devolución esperada</li>
                        </ul>
                    </li>
                </ol>
                
                <hr>
                
                <h6>Control de Vencimiento:</h6>
                <p class="small">El sistema identificará automáticamente las carpetas no devueltas y detectará vencimientos, generando alertas.</p>
                
                <div class="alert alert-warning small">
                    <i class="bi bi-exclamation-triangle"></i>
                    Si excede el plazo, se generará una nota de devolución.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Seleccionar todos
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.chk-carpeta').forEach(cb => cb.checked = this.checked);
    actualizarContador();
});

// Actualizar contador
document.querySelectorAll('.chk-carpeta').forEach(cb => {
    cb.addEventListener('change', actualizarContador);
});

function actualizarContador() {
    const total = document.querySelectorAll('.chk-carpeta:checked').length;
    document.getElementById('contadorSeleccionados').textContent = total;
}
</script>