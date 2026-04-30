<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';
}

if (file_exists(__DIR__ . '/../config/mail.php')) {
    require_once __DIR__ . '/../config/mail.php';
}

class Mailer {
    
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configurar();
    }
    
    private function configurar() {
        $this->mail->isSMTP();
        $this->mail->Host       = MAIL_HOST;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = MAIL_USERNAME;
        $this->mail->Password   = MAIL_PASSWORD;
        $this->mail->SMTPSecure = MAIL_ENCRYPTION;
        $this->mail->Port       = MAIL_PORT;
        $this->mail->CharSet    = MAIL_CHARSET;
        
        // Configuración adicional para Gmail
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        
        $this->mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        
        if (defined('MAIL_REPLY_TO')) {
            $this->mail->addReplyTo(MAIL_REPLY_TO, MAIL_REPLY_TO_NAME ?? '');
        }
    }
    
    public function enviarAlertaVencimiento($destinatario, $datosPrestamo, $datosCarpetas) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario);
            
            $asunto = "⚠️ URGENTE: Nota de Devolución - Préstamo Vencido - Guía " . $datosPrestamo['numero_guia'];
            $this->mail->Subject = $asunto;
            
            $this->mail->isHTML(true);
            $this->mail->Body = $this->generarCuerpoHTML($datosPrestamo, $datosCarpetas);
            $this->mail->AltBody = $this->generarCuerpoTexto($datosPrestamo, $datosCarpetas);
            
            $this->mail->send();
            
            $this->log("Correo enviado a: $destinatario - Guía: " . $datosPrestamo['numero_guia']);
            
            return ['success' => true, 'message' => 'Correo enviado exitosamente a ' . $destinatario];
            
        } catch (Exception $e) {
            $this->log("ERROR: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'error' => $this->mail->ErrorInfo];
        }
    }
    
    private function generarCuerpoHTML($prestamo, $carpetas) {
        $diasVencido = $this->calcularDiasVencidos($prestamo['fecha_devolucion_esperada']);
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 700px; margin: 0 auto; background: white; }
        .header { background: #1a237e; color: white; padding: 25px; text-align: center; }
        .urgent { background: #ffebee; border-left: 5px solid #c62828; padding: 20px; margin: 20px; }
        .info-box { background: #f5f5f5; padding: 20px; margin: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #1a237e; color: white; padding: 12px; text-align: left; }
        td { border: 1px solid #ddd; padding: 10px; }
        .badge-danger { background: #c62828; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        .footer { background: #e0e0e0; padding: 20px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🏛️ MINISTERIO PÚBLICO</h2>
            <h3>Sistema de Archivo Fiscal</h3>
            <p><strong>NOTA DE DEVOLUCIÓN - PRÉSTAMO VENCIDO</strong></p>
        </div>
        
        <div class="urgent">
            <h3 style="color: #c62828;">⚠️ URGENTE - ACCIÓN INMEDIATA REQUERIDA</h3>
            <p>El préstamo de carpetas fiscales ha <strong>EXCEDIDO EL PLAZO</strong> establecido.</p>
            <p><strong>Se solicita la DEVOLUCIÓN INMEDIATA de las carpetas fiscales.</strong></p>
        </div>
        
        <div class="info-box">
            <h4>📋 DATOS DEL PRÉSTAMO</h4>
            <table>
                <tr><td width="200"><strong>Guía:</strong></td><td><span class="badge-danger">' . htmlspecialchars($prestamo['numero_guia']) . '</span></td></tr>
                <tr><td><strong>Dependencia:</strong></td><td>' . htmlspecialchars($prestamo['dependencia_nombre']) . '</td></tr>
                <tr><td><strong>Solicitante:</strong></td><td>' . htmlspecialchars($prestamo['solicitante_nombre']) . '</td></tr>
                <tr><td><strong>Fecha Préstamo:</strong></td><td>' . date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) . '</td></tr>
                <tr><td><strong>Devolución Esperada:</strong></td><td>' . date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) . '</td></tr>
                <tr><td><strong>Días Vencido:</strong></td><td style="color: #c62828; font-size: 18px; font-weight: bold;">' . $diasVencido . ' DÍAS</td></tr>
            </table>
        </div>
        
        <h4 style="margin-left: 20px;">📁 CARPETAS PENDIENTES DE DEVOLUCIÓN</h4>
        <table style="margin: 0 20px; width: calc(100% - 40px);">
            <thead>
                <tr><th>Número</th><th>Imputado</th><th>Delito</th><th>Ubicación</th></tr>
            </thead>
            <tbody>';
        
        foreach ($carpetas as $c) {
            $html .= '<tr>
                <td><strong>' . htmlspecialchars($c['numero_carpeta']) . '</strong></td>
                <td>' . htmlspecialchars($c['imputado']) . '</td>
                <td>' . htmlspecialchars($c['delito']) . '</td>
                <td>' . htmlspecialchars($c['ubicacion_fisica']) . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
        </table>
        
        <div style="background: #fff3e0; padding: 20px; margin: 20px; border-radius: 8px;">
            <h4>⚖️ IMPORTANTE</h4>
            <p>El incumplimiento puede acarrear responsabilidad administrativa.</p>
        </div>
        
        <div style="padding: 20px;">
            <p>Atentamente,</p>
            <p><strong>Responsable de Archivo Central</strong></p>
            <p>Ministerio Público</p>
        </div>
        
        <div class="footer">
            <p>© ' . date('Y') . ' Ministerio Público - Sistema de Archivo Fiscal</p>
        </div>
    </div>
</body>
</html>';
        return $html;
    }
    
    private function generarCuerpoTexto($prestamo, $carpetas) {
        $diasVencido = $this->calcularDiasVencidos($prestamo['fecha_devolucion_esperada']);
        $texto = "MINISTERIO PÚBLICO - NOTA DE DEVOLUCIÓN\n";
        $texto .= str_repeat("=", 50) . "\n\n";
        $texto .= "⚠️ PRÉSTAMO VENCIDO - Guía: " . $prestamo['numero_guia'] . "\n";
        $texto .= "Días vencido: " . $diasVencido . " DÍAS\n\n";
        $texto .= "CARPETAS PENDIENTES:\n";
        foreach ($carpetas as $c) {
            $texto .= "  • " . $c['numero_carpeta'] . " - " . $c['imputado'] . "\n";
        }
        $texto .= "\nSe solicita DEVOLUCIÓN INMEDIATA.\n";
        return $texto;
    }
    
    private function calcularDiasVencidos($fechaEsperada) {
        $fecha1 = new DateTime($fechaEsperada);
        $fecha2 = new DateTime();
        if ($fecha2 > $fecha1) {
            return $fecha1->diff($fecha2)->days;
        }
        return 0;
    }
    
    private function log($mensaje, $nivel = 'INFO') {
        if (!defined('MAIL_LOG_ENABLED') || !MAIL_LOG_ENABLED) return;
        $logDir = dirname(MAIL_LOG_PATH);
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $fecha = date('Y-m-d H:i:s');
        @file_put_contents(MAIL_LOG_PATH, "[$fecha] [$nivel] $mensaje\n", FILE_APPEND);
    }
}
?>