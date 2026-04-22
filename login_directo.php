<?php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Credenciales fijas para prueba
    if ($email == 'admin@mp.gob.pe' && $password == 'admin123') {
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_nombre'] = 'Administrador';
        $_SESSION['usuario_rol'] = 'admin';
        header('Location: index.php');
        exit;
    } else {
        $mensaje = 'Email o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            background: white;
        }
        .login-header {
            background: #1a237e;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 25px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <h3>📁 Sistema de Archivo Fiscal</h3>
                <p class="mb-0">Ministerio Público</p>
            </div>
            <div class="login-body">
                <?php if ($mensaje): ?>
                    <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" 
                               value="admin@mp.gob.pe" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" 
                               value="admin123" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Ingresar al Sistema
                    </button>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center text-muted small">
                    <p class="mb-1"><strong>Credenciales:</strong></p>
                    <p class="mb-0">admin@mp.gob.pe / admin123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>