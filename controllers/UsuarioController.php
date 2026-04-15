<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../includes/functions.php';

class UsuarioController {
    
    // Listar usuarios (solo admin)
    public function index() {
        verificarLogin();
        
        if ($_SESSION['usuario_rol'] != 'admin') {
            header('Location: index.php');
            exit;
        }
        
        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();
        
        include __DIR__ . '/../views/usuarios/index.php';
    }
    
    // Mostrar formulario de registro (solo admin)
    public function registrar() {
        verificarLogin();
        
        if ($_SESSION['usuario_rol'] != 'admin') {
            header('Location: index.php');
            exit;
        }
        
        include __DIR__ . '/../views/auth/registro.php';
    }
}
?>