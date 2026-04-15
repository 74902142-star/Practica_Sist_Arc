<?php
$prestamo = $datos ?? [];
$dias_vencido = calcularDiasVencidos($prestamo['fecha_devolucion_esperada'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Devolución - <?php echo $prestamo['numero_guia'] ?? ''; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a237e;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .content {
            margin: 20px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #1a237e;
            color: white;
        }
        .vencido {
            color: #c62828;
            font-weight: bold;
            font-size: 18px;
        }
        .firma {
            margin-top: 60px;
            text-align: center;
        }
        .firma-linea {
            width: 300px;
            margin: 40px auto 10px;
            border-top: 1px solid #000;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .print-btn {
            background: #1a237e;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        🖨️ Imprimir Nota de Devolución
    </button>

    <div class="header">
        <div class="logo">MINISTERIO PÚBLICO</div>
        <div>Archivo Central - Sistema de Archivo Fiscal</div>
    </div>
    
    <div class="title">NOTA DE DEVOLUCIÓN DE CARPETAS FISCALES</div>
    
    <div class="content">
        <p><strong>Guía de Préstamo:</strong> <?php echo $prestamo['numero_guia'] ?? ''; ?></p>
        <p><strong>Fecha de Emisión:</strong> <?php echo date('d/m/Y'); ?></p>
        <p><strong>Dependencia:</strong> <?php echo $prestamo['dependencia_nombre'] ?? ''; ?></p>
        <p><strong>Solicitante:</strong> <?php echo $prestamo['solicitante_nombre'] ?? ''; ?></p>
        
        <table class="table">
            <tr>
                <th>Fecha de Préstamo</th>
                <td><?php echo formatearFecha($prestamo['fecha_prestamo'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Fecha de Devolución Esperada</th>
                <td><?php echo formatearFecha($prestamo['fecha_devolucion_esperada'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Días de Vencimiento</th>
                <td class="vencido"><?php echo $dias_vencido; ?> días</td>
            </tr>
        </table>
        
        <p>Por medio de la presente, se notifica que el préstamo de carpetas fiscales identificado con la guía 
        <strong><?php echo $prestamo['numero_guia'] ?? ''; ?></strong> ha excedido el plazo establecido para su devolución.</p>
        
        <p>Se solicita la <strong>DEVOLUCIÓN INMEDIATA</strong> de las carpetas fiscales que se encuentran bajo su responsabilidad.</p>
        
        <p>Se recuerda que el incumplimiento en la devolución de carpetas fiscales puede acarrear responsabilidad administrativa, 
        conforme al reglamento interno del Ministerio Público.</p>
        
        <p>En caso las carpetas ya hubieran sido devueltas, sírvase ignorar la presente notificación.</p>
    </div>
    
    <div class="firma">
        <div class="firma-linea"></div>
        <p><strong>Responsable de Archivo Central</strong></p>
        <p>Ministerio Público</p>
        <p>Fecha: <?php echo date('d/m/Y'); ?></p>
    </div>
    
    <div class="footer">
        <p>Este documento ha sido generado automáticamente por el Sistema de Archivo Fiscal.</p>
        <p>Ministerio Público - Archivo Central</p>
    </div>
    
    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>