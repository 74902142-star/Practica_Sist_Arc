<?php
// Solo admin puede acceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/../../models/Usuario.php';
    
    $usuario = new Usuario();
    
    $datos = [
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'rol' => $_POST['rol']
    ];
    
    $resultado = $usuario->registrar($datos);
    
    if ($resultado['success']) {
        $success = '✅ Usuario registrado exitosamente.';
    } else {
        $error = '❌ Error: ' . $resultado['error'];
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header" style="background: linear-gradient(90deg, #8B0000 0%, #A52A2A 100%); color: white; border-bottom: 2px solid #DAA520;">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus-fill"></i> Registrar Nuevo Usuario
                    </h4>
                </div>
                <div class="card-body" style="background-color: #FFF8DC;">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label" style="color: #8B0000; font-weight: bold;">
                                <i class="bi bi-person-fill"></i> Nombre Completo
                            </label>
                            <input type="text" name="nombre" class="form-control" 
                                   placeholder="Ej: Juan Pérez González" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #8B0000; font-weight: bold;">
                                <i class="bi bi-envelope-fill"></i> Correo Electrónico
                            </label>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="ejemplo@mp.gob.pe" required>
                            <small class="text-muted">Debe ser un correo válido y único</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #8B0000; font-weight: bold;">
                                <i class="bi bi-lock-fill"></i> Contraseña
                            </label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Mínimo 6 caracteres" minlength="6" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #8B0000; font-weight: bold;">
                                <i class="bi bi-shield-fill"></i> Rol del Usuario
                            </label>
                            <select name="rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <option value="admin">👑 Administrador - Acceso total</option>
                                <option value="fiscal">⚖️ Fiscal - Gestión de carpetas y préstamos</option>
                                <option value="archivo">📁 Archivo - Gestión de archivo y devoluciones</option>
                                <option value="usuario">👤 Usuario - Solo consulta</option>
                            </select>
                        </div>
                        
                        <div class="alert" style="background-color: #FFF3E0; border-left: 5px solid #DAA520;">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Información de roles:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li><strong>Admin:</strong> Acceso total al sistema</li>
                                <li><strong>Fiscal:</strong> Puede registrar carpetas y hacer préstamos</li>
                                <li><strong>Archivo:</strong> Gestiona devoluciones y ubicaciones</li>
                                <li><strong>Usuario:</strong> Solo puede consultar información</li>
                            </ul>
                        </div>
                        
                        <hr style="border-color: #DAA520;">
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #8B0000 0%, #5C0000 100%); color: white; border: 1px solid #DAA520;">
                                <i class="bi bi-save"></i> Registrar Usuario
                            </button>
                            <a href="index.php?page=usuarios" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>