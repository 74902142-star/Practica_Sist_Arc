<?php
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Archivo Fiscal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            max-width: 420px;
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
        .btn-login {
            background: #1a237e;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
        }
        .btn-login:hover {
            background: #0d1757;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-folder-fill" style="font-size: 3rem;"></i>
                <h4 class="mt-2">Sistema de Archivo Fiscal</h4>
                <p class="mb-0">Ministerio Público</p>
            </div>
            <div class="login-body">
                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" name="email" class="form-control" 
                               placeholder="admin@mp.gob.pe" 
                               value="admin@mp.gob.pe" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="••••••••" value="admin123" required>
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Ingresar al Sistema
                    </button>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center text-muted small">
                    <p class="mb-1"><strong><i class="bi bi-key"></i> Credenciales de prueba:</strong></p>
                    <p class="mb-0">📧 admin@mp.gob.pe</p>
                    <p class="mb-0">🔑 admin123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>