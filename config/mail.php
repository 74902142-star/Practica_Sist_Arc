<?php
// CONFIGURACIÓN DE CORREO - GMAIL CON CONTRASEÑA DE APLICACIÓN

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'maycolccq@gmail.com');        // ← TU correo de Gmail
define('MAIL_PASSWORD', 'yeowgfepbvogzdxk');       // ← La contraseña de 16 caracteres (SIN ESPACIOS)
define('MAIL_FROM_EMAIL', 'TU_CORREO@gmail.com');
define('MAIL_FROM_NAME', 'Sistema de Archivo Fiscal - MP');
define('MAIL_ENCRYPTION', 'tls');                      // tls para puerto 587
define('MAIL_CHARSET', 'UTF-8');

// Opcional - Correo de respuesta
define('MAIL_REPLY_TO', 'TU_CORREO@gmail.com');
define('MAIL_REPLY_TO_NAME', 'Archivo Central - MP');

// Logs para depuración
define('MAIL_LOG_ENABLED', true);
define('MAIL_LOG_PATH', __DIR__ . '/../logs/mail.log');
?>