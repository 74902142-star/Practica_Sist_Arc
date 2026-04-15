<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-folder-plus"></i> Registrar Nueva Carpeta Fiscal
                </h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <!-- Número de Carpeta (único) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-hash"></i> Número de Carpeta <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="numero_carpeta" class="form-control" 
                                   placeholder="Ej: FISC-2024-XXX" required>
                            <small class="text-muted">Debe ser único en el sistema</small>
                        </div>
                        
                        <!-- Imputado -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-person"></i> Imputado <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="imputado" class="form-control" 
                                   placeholder="Nombre completo del imputado" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Delito -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-file-text"></i> Delito <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="delito" class="form-control" 
                                   placeholder="Ej: Robo Agravado" required>
                        </div>
                        
                        <!-- Agravado -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-exclamation-diamond"></i> Agravado
                            </label>
                            <select name="agravado" class="form-select">
                                <option value="">Seleccione</option>
                                <option value="Si">✅ Sí</option>
                                <option value="No">❌ No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Estado -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-flag"></i> Estado <span class="text-danger">*</span>
                            </label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo">🟢 Activo</option>
                                <option value="Archivado">📦 Archivado</option>
                            </select>
                        </div>
                        
                        <!-- Ubicación Física -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-geo-alt"></i> Ubicación Física <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ubicacion_fisica" class="form-control" 
                                   placeholder="Ej: Estante A-1 - Bandeja 3" required>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-chat"></i> Observaciones
                        </label>
                        <textarea name="observaciones" class="form-control" rows="2" 
                                  placeholder="Información adicional..."></textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Registrar Carpeta
                        </button>
                        <a href="index.php" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>