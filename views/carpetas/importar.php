<?php
// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$resultado_importacion = $resultado_importacion ?? null;
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header" style="background-color: #1d6f42; color: white;">
                <h4 class="mb-0">
                    <i class="bi bi-file-excel"></i> Carga Masiva de Carpetas desde Excel
                </h4>
            </div>
            <div class="card-body">
                
                <!-- Instrucciones -->
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Instrucciones:</strong> El archivo debe tener las siguientes columnas en este orden:
                </div>
                
                <!-- Estructura del archivo -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Columna</th>
                                <th>Campo</th>
                                <th>Obligatorio</th>
                                <th>Ejemplo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A</td>
                                <td><strong>Número de Carpeta</strong></td>
                                <td><span class="badge bg-danger">Sí</span></td>
                                <td>FISC-2026-001</td>
                            </tr>
                            <tr>
                                <td>B</td>
                                <td><strong>Imputado</strong></td>
                                <td><span class="badge bg-danger">Sí</span></td>
                                <td>Juan Pérez González</td>
                            </tr>
                            <tr>
                                <td>C</td>
                                <td><strong>Delito</strong></td>
                                <td><span class="badge bg-danger">Sí</span></td>
                                <td>Robo Agravado</td>
                            </tr>
                            <tr>
                                <td>D</td>
                                <td><strong>Agravado</strong></td>
                                <td><span class="badge bg-secondary">No</span></td>
                                <td>Si / No</td>
                            </tr>
                            <tr>
                                <td>E</td>
                                <td><strong>Estado</strong></td>
                                <td><span class="badge bg-secondary">No</span></td>
                                <td>Activo / Archivado</td>
                            </tr>
                            <tr>
                                <td>F</td>
                                <td><strong>Ubicación Física</strong></td>
                                <td><span class="badge bg-danger">Sí</span></td>
                                <td>Estante A-1 - Bandeja 3</td>
                            </tr>
                            <tr>
                                <td>G</td>
                                <td><strong>Observaciones</strong></td>
                                <td><span class="badge bg-secondary">No</span></td>
                                <td>Información adicional</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Validaciones -->
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Validaciones del sistema:</strong>
                    <ul class="mb-0 mt-2">
                        <li>El <strong>número de carpeta</strong> debe ser único en el sistema</li>
                        <li>Los campos obligatorios no pueden estar vacíos</li>
                        <li><strong>❌ Las carpetas duplicadas serán RECHAZADAS</strong> (no se actualizarán)</li>
                        <li>Formatos aceptados: <strong>.csv, .txt</strong></li>
                    </ul>
                </div>
                
                <!-- Ejemplo del caso práctico -->
                <div class="alert alert-success">
                    <i class="bi bi-lightbulb-fill"></i>
                    <strong>Ejemplo según práctica:</strong> 
                    "Se cargan 100 carpetas desde Excel" - El sistema procesará el archivo y mostrará:
                    <ul class="mb-0 mt-2">
                        <li>✅ Registros insertados correctamente</li>
                        <li>🔄 Registros actualizados (si ya existían)</li>
                        <li>⚠️ Errores encontrados (filas con problemas)</li>
                    </ul>
                </div>
                
                <!-- Formulario de carga -->
                <form method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-upload"></i> Seleccionar Archivo CSV/Excel
                        </label>
                        <input type="file" name="archivo_excel" class="form-control form-control-lg" 
                               accept=".csv,.txt,.xlsx,.xls" required>
                        <small class="text-muted">
                            Formatos soportados: .csv, .txt (archivos de texto con columnas separadas por comas)
                        </small>
                    </div>
                    
                    <div class="mb-4">
                        <a href="#" class="btn btn-outline-success" id="descargarPlantilla">
                            <i class="bi bi-download"></i> Descargar Plantilla CSV de Ejemplo
                        </a>
                        <small class="text-muted ms-2">(Incluye un duplicado intencional para prueba)</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="importar" class="btn btn-success btn-lg">
                            <i class="bi bi-upload"></i> Importar Carpetas
                        </button>
                        <a href="index.php?page=carpetas" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                </form>
                
                <!-- Resultados de la importación -->
                <?php if (isset($resultado_importacion) && $resultado_importacion): ?>
                <div class="mt-5">
                    <div class="card border">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle"></i> Resultado de la Importación
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($resultado_importacion['success']): ?>
                                <div class="alert alert-success">
                                    <h5><i class="bi bi-check-circle-fill"></i> ✅ Importación Completada</h5>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h2 class="text-success"><?php echo $resultado_importacion['insertados']; ?></h2>
                                                    <p class="mb-0">✅ Registros Insertados</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h2 class="text-warning"><?php echo $resultado_importacion['duplicados']; ?></h2>
                                                    <p class="mb-0">⚠️ Duplicados Rechazados</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h2 class="text-info"><?php echo $resultado_importacion['total_procesados']; ?></h2>
                                                    <p class="mb-0">📊 Total Procesados</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($resultado_importacion['duplicados_lista'])): ?>
                                        <hr>
                                        <h6 class="text-warning"><i class="bi bi-exclamation-triangle"></i> Carpetas Duplicadas (NO importadas):</h6>
                                        <div class="alert alert-warning">
                                            <?php foreach (array_unique($resultado_importacion['duplicados_lista']) as $dup): ?>
                                                <span class="badge bg-warning text-dark me-2 mb-1 p-2">📁 <?php echo $dup; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($resultado_importacion['errores'])): ?>
                                        <hr>
                                        <h6 class="text-danger"><i class="bi bi-x-circle"></i> Detalle de Errores:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-danger">
                                                <tbody>
                                                    <?php foreach ($resultado_importacion['errores'] as $error): ?>
                                                        <tr>
                                                            <td><i class="bi bi-exclamation-circle"></i></td>
                                                            <td><?php echo $error; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="text-info small mt-2">
                                        <i class="bi bi-info-circle-fill"></i> 
                                        <strong>Nota sobre duplicados:</strong> Los números de carpeta que ya existen en el sistema 
                                        son <strong>RECHAZADOS</strong> y no se importan, de acuerdo a los requerimientos.
                                    </p>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="index.php?page=carpetas" class="btn btn-primary">
                                        <i class="bi bi-list-ul"></i> Ver Listado de Carpetas
                                    </a>
                                    <a href="index.php?page=buscar" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i> Buscar Carpeta
                                    </a>
                                    <a href="index.php?page=importar" class="btn btn-outline-success">
                                        <i class="bi bi-upload"></i> Nueva Importación
                                    </a>
                                </div>
                                
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <h5><i class="bi bi-x-circle-fill"></i> ❌ Error en la Importación</h5>
                                    <p class="mt-2"><?php echo $resultado_importacion['error'] ?? 'Error desconocido al procesar el archivo.'; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Tarjeta de ayuda adicional -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-question-circle"></i> Ayuda Rápida</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="bi bi-filetype-csv"></i> Crear archivo CSV</h6>
                        <p class="small">Puedes usar Excel o Google Sheets. Guarda como "CSV (delimitado por comas)" con codificación UTF-8.</p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="bi bi-exclamation-diamond"></i> Manejo de duplicados</h6>
                        <p class="small">El sistema detecta números de carpeta repetidos y actualiza el registro existente con los nuevos datos.</p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="bi bi-arrow-repeat"></i> Procesamiento</h6>
                        <p class="small">El archivo se procesa fila por fila. Los errores en una fila no detienen el procesamiento del resto.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Script para descargar plantilla -->
<script>
document.getElementById('descargarPlantilla').addEventListener('click', function(e) {
    e.preventDefault();
    
    // Contenido del archivo CSV de ejemplo
    const csvContent = `numero_carpeta,imputado,delito,agravado,estado,ubicacion_fisica,observaciones
FISC-2026-001,Juan Pérez González,Robo Agravado,Si,Activo,Estante A-1 - Bandeja 1,Caso en investigación preliminar
FISC-2026-002,María López Ramírez,Estafa,No,Activo,Estante B-2 - Bandeja 3,Audiencia programada 15/04/2026
FISC-2026-003,Carlos Ruiz Mendoza,Homicidio Simple,No,Activo,Estante A-3 - Bandeja 5,Esperando peritaje balístico
FISC-2026-004,Ana Torres Vega,Lavado de Activos,Si,Activo,Estante C-1 - Bandeja 2,Investigación con reserva
FISC-2026-005,Pedro Sánchez Díaz,Violencia Familiar,Si,Activo,Estante D-2 - Bandeja 4,Medidas de protección vigentes
FISC-2026-002,María López Ramírez,Estafa,No,Activo,Estante B-2 - Bandeja 3,⚠️ DUPLICADO INTENCIONAL - Este registro actualizará el anterior
FISC-2026-006,Luisa Fernández Castro,Falsificación Documental,No,Activo,Estante E-1 - Bandeja 1,Pericia grafotécnica en curso
FISC-2026-007,Roberto Gómez Luna,Tráfico Ilícito de Drogas,Si,Activo,Estante F-3 - Bandeja 2,Investigación preliminar
FISC-2026-008,Sandra Rojas Paz,Apropiación Ilícita,No,Activo,Estante G-2 - Bandeja 4,En espera de documentos
FISC-2026-009,Miguel Ángel Castro,Secuestro,Si,Activo,Estante H-1 - Bandeja 3,Caso en etapa de instrucción
FISC-2026-010,Diana Morales León,Fraude Informático,No,Activo,Estante I-4 - Bandeja 1,Pericia informática solicitada
FISC-2026-011,Oscar Ruiz Paredes,Cohecho,Si,Activo,Estante J-2 - Bandeja 5,Investigación fiscal
FISC-2026-012,Carmen Vega Soto,Lesiones Graves,No,Activo,Estante K-1 - Bandeja 2,Certificado médico pendiente
FISC-2026-013,Ricardo Palma Flores,Extorsión,Si,Activo,Estante L-3 - Bandeja 4,Víctima en protección
FISC-2026-014,Sofía Mendoza Ruiz,Usurpación,No,Activo,Estante M-2 - Bandeja 1,Desalojo programado
FISC-2026-015,Alberto Núñez Díaz,Peculado,Si,Activo,Estante N-1 - Bandeja 3,Auditoría en curso`;

    // Crear blob y descargar
    const blob = new Blob(["\ufeff" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'plantilla_carpetas_fiscales.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Mostrar mensaje
    alert('📥 Plantilla CSV descargada exitosamente.\n\nContiene 15 registros de prueba incluyendo un duplicado (FISC-2026-002) para probar la funcionalidad.');
});
</script>

<style>
/* Estilos adicionales para la página de importación */
.table-sm td, .table-sm th {
    padding: 0.5rem;
    vertical-align: middle;
}

.card-header {
    font-weight: 600;
}

.btn-lg {
    padding: 12px 24px;
}

.alert ul {
    padding-left: 1.2rem;
}

.alert li {
    margin-bottom: 0.25rem;
}
</style>