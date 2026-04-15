<?php
require_once __DIR__ . '/../models/Carpeta.php';
require_once __DIR__ . '/../includes/functions.php';

class CarpetaController {
    
    public function index() {
        verificarLogin();
        $carpeta = new Carpeta();
        $carpetas = $carpeta->obtenerTodas();
        include __DIR__ . '/../views/carpetas/index.php';
    }

    public function registrar() {
        verificarLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $carpeta = new Carpeta();
            $resultado = $carpeta->registrar($_POST);
            
            if ($resultado['success']) {
                mostrarMensaje('success', '✅ Carpeta registrada exitosamente');
            } else {
                mostrarMensaje('danger', '❌ Error: ' . $resultado['error']);
            }
            header('Location: index.php?page=carpetas');
            exit;
        }
        
        include __DIR__ . '/../views/carpetas/registrar.php';
    }

    // CONSULTA DE UBICACIÓN (Requerimiento 3)
    public function buscar() {
        verificarLogin();
        
        $resultado = null;
        $mensaje = '';
        
        if (isset($_POST['buscar'])) {
            $carpeta = new Carpeta();
            $resultado = $carpeta->buscarPorNumero($_POST['numero_carpeta']);
            
            if (!$resultado) {
                $mensaje = 'No ubicado';
            }
        }
        
        include __DIR__ . '/../views/carpetas/buscar.php';
    }

    // API para búsqueda AJAX
    public function apiBuscar() {
        verificarLogin();
        
        if (isset($_GET['numero'])) {
            $carpeta = new Carpeta();
            $resultado = $carpeta->buscarPorNumero($_GET['numero']);
            
            header('Content-Type: application/json');
            if ($resultado) {
                echo json_encode(['success' => true, 'carpeta' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'No ubicado']);
            }
        }
    }

    // CARGA MASIVA DESDE EXCEL/CSV (Requerimiento 2)
    public function importar() {
        verificarLogin();
        
        $resultado_importacion = null;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_excel'])) {
            $archivo = $_FILES['archivo_excel'];
            
            // Verificar que no haya error en la subida
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $resultado_importacion = [
                    'success' => false,
                    'error' => 'Error al subir el archivo. Código: ' . $archivo['error']
                ];
            } else {
                // Verificar extensión
                $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, ['csv', 'txt'])) {
                    // Procesar archivo CSV
                    $file = fopen($archivo['tmp_name'], 'r');
                    
                    if ($file) {
                        $datos = [];
                        $fila = 0;
                        
                        // Leer archivo línea por línea
                        while (($linea = fgetcsv($file, 0, ',')) !== false) {
                            $fila++;
                            
                            // Saltar la primera fila (encabezados)
                            if ($fila === 1) {
                                continue;
                            }
                            
                            // Verificar que la línea tenga datos suficientes
                            if (count($linea) >= 6 && !empty(trim($linea[0]))) {
                                $datos[] = [
                                    'numero_carpeta' => trim($linea[0] ?? ''),
                                    'imputado' => trim($linea[1] ?? ''),
                                    'delito' => trim($linea[2] ?? ''),
                                    'agravado' => trim($linea[3] ?? ''),
                                    'estado' => trim($linea[4] ?? 'Activo'),
                                    'ubicacion_fisica' => trim($linea[5] ?? ''),
                                    'observaciones' => trim($linea[6] ?? '')
                                ];
                            }
                        }
                        fclose($file);
                        
                        if (!empty($datos)) {
                            $carpeta = new Carpeta();
                            $resultado_importacion = $carpeta->cargaMasiva($datos);
                        } else {
                            $resultado_importacion = [
                                'success' => false,
                                'error' => 'El archivo no contiene datos válidos para importar.'
                            ];
                        }
                    } else {
                        $resultado_importacion = [
                            'success' => false,
                            'error' => 'No se pudo leer el archivo.'
                        ];
                    }
                } else {
                    $resultado_importacion = [
                        'success' => false,
                        'error' => 'Formato de archivo no soportado. Use .csv o .txt'
                    ];
                }
            }
        }
        
        include __DIR__ . '/../views/carpetas/importar.php';
    }
}
?>