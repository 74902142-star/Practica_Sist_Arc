<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    
    public function login() {
        // Si ya está logueado, redirigir al index
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php');
            exit;
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $usuario = new Usuario();
            $resultado = $usuario->login($email, $password);
            
            if ($resultado['success']) {
                $_SESSION['usuario_id'] = $resultado['usuario']['id'];
                $_SESSION['usuario_nombre'] = $resultado['usuario']['nombre'];
                $_SESSION['usuario_rol'] = $resultado['usuario']['rol'];
                
                header('Location: index.php');
                exit;
            } else {
                $error = '❌ Email o contraseña incorrectos';
            }
        }
        
        // Incluir la vista de login
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?>