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
    <title>Acceso - Ministerio Público</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0B2B5E 0%, #1A4A7A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        
        /* Lado izquierdo - Formulario */
        .login-form-side {
            flex: 1;
            padding: 60px 40px;
            background: #FFFFFF;
        }
        
        .login-header {
            margin-bottom: 32px;
        }
        
        .login-header h2 {
            color: #0B2B5E;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }
        
        .login-header p {
            color: #7F8C8D;
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .login-header .divider {
            width: 50px;
            height: 3px;
            background: #1A4A7A;
            margin-top: 16px;
            border-radius: 2px;
        }
        
        .form-label {
            font-weight: 500;
            color: #2C3E50;
            margin-bottom: 6px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group-text {
            background: #F8F9FA;
            border: 1px solid #DEE2E6;
            border-right: none;
            color: #7F8C8D;
        }
        
        .form-control {
            border: 1px solid #DEE2E6;
            border-left: none;
            padding: 12px 16px;
            font-size: 14px;
            background: #F8F9FA;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: #1A4A7A;
            box-shadow: none;
            background: #FFFFFF;
        }
        
        .form-control:focus ~ .input-group-text {
            border-color: #1A4A7A;
        }
        
        .btn-login {
            background: #0B2B5E;
            color: white;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 15px;
            border-radius: 8px;
            width: 100%;
            margin-top: 16px;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            background: #1A4A7A;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 74, 122, 0.3);
        }
        
        .btn-login i {
            margin-right: 8px;
        }
        
        .credentials-box {
            margin-top: 32px;
            padding: 16px;
            background: #F8F9FA;
            border-radius: 8px;
            border-left: 3px solid #1A4A7A;
        }
        
        .credentials-box h6 {
            color: #2C3E50;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .credentials-box code {
            background: #FFFFFF;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            color: #1A4A7A;
            border: 1px solid #E9ECEF;
        }
        
        .login-footer {
            margin-top: 24px;
            text-align: center;
            color: #95A5A6;
            font-size: 12px;
        }
        
        /* Lado derecho - Imagen y contenido */
        .login-image-side {
            flex: 1;
            background: linear-gradient(135deg, #0B2B5E 0%, #1A4A7A 50%, #2C6B9E 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        
        .login-image-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23FFFFFF" fill-opacity="0.03" d="M100 0L120 60H180L130 100L150 160L100 130L50 160L70 100L20 60H80L100 0Z"/></svg>');
            background-size: 60px 60px;
            opacity: 0.5;
        }
        
        .image-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }
        
        .image-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .image-icon i {
            font-size: 48px;
            color: white;
        }
        
        .image-content h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            color: white;
        }
        
        .image-content p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 32px;
            max-width: 300px;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }
        
        .feature-list li {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }
        
        .feature-list li i {
            margin-right: 12px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            width: 24px;
            text-align: center;
        }
        
        .justice-scale {
            margin-top: 40px;
            opacity: 0.8;
        }
        
        .justice-scale i {
            font-size: 80px;
            color: rgba(255, 255, 255, 0.15);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }
            
            .login-image-side {
                display: none;
            }
            
            .login-form-side {
                padding: 32px 24px;
            }
        }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-form-side {
            animation: fadeInUp 0.5s ease-out;
        }
        
        .image-content {
            animation: fadeInUp 0.5s ease-out 0.1s both;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Lado Izquierdo - Formulario -->
        <div class="login-form-side">
            <div class="login-header">
                <h2>Ministerio Público</h2>
                <p>Sistema de Archivo Fiscal</p>
                <div class="divider"></div>
            </div>
            
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger" style="background: #FADBD8; border: none; color: #922B21; border-left: 4px solid #C0392B;">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" 
                               placeholder="ejemplo@mp.gob.pe" 
                               value="admin@mp.gob.pe" required autofocus>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" 
                               placeholder="••••••••" value="admin123" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Ingresar al Sistema
                </button>
            </form>
            
            <div class="credentials-box">
                <h6><i class="bi bi-key-fill"></i> Credenciales de prueba</h6>
                <code>admin@mp.gob.pe / admin123</code>
            </div>
            
            <div class="login-footer">
                <i class="bi bi-shield-check"></i> Acceso restringido · Ministerio Público
            </div>
        </div>
        
        <!-- Lado Derecho - Imagen y Contenido -->
        <div class="login-image-side">
            <div class="image-content">
                <div class="image-icon">
                    <i class="bi bi-building"></i>
                </div>
                
                <h3>Sistema de Archivo Fiscal</h3>
                <p>Gestión eficiente de carpetas fiscales para el Ministerio Público</p>
                
                <ul class="feature-list">
                    <li>
                        <i class="bi bi-folder-check"></i>
                        <span>Registro y control de carpetas</span>
                    </li>
                    <li>
                        <i class="bi bi-search"></i>
                        <span>Búsqueda rápida de ubicación</span>
                    </li>
                    <li>
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Gestión de préstamos</span>
                    </li>
                    <li>
                        <i class="bi bi-bell"></i>
                        <span>Alertas de vencimiento</span>
                    </li>
                    <li>
                        <i class="bi bi-bar-chart"></i>
                        <span>Reportes y estadísticas</span>
                    </li>
                </ul>
                
                <div class="justice-scale">
                    <i class="bi bi-shield-shaded"></i>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>