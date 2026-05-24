<?php

namespace Sfouter\utils; 

class Errores {

    public static function log(string $mensaje, array $contexto = []): void {
        // 1. Usamos la constante BASE_PATH que definimos en index.php
        // Si no está definida, usamos una ruta relativa de emergencia
        $base = defined('BASE_PATH') ? BASE_PATH : __DIR__ . '/../../';
        
        $rutaLog = $base . "storage" . DIRECTORY_SEPARATOR . "Errores.log";

        // 2. Aseguramos que la carpeta storage exista
        if (!is_dir($base . "storage")) {
            mkdir($base . "storage", 0777, true);
        }

        $fecha = date('Y-m-d H:i:s'); 
        
        // 3. Formateamos el mensaje incluyendo el contexto (como el ID de usuario)
        $extra = !empty($contexto) ? " | Contexto: " . json_encode($contexto) : "";
        $mensajeFormateado = "[$fecha] " . $mensaje . $extra . PHP_EOL; 

        // 4. Escribimos directamente. Sin variables externas que puedan fallar.
        file_put_contents($rutaLog, $mensajeFormateado, FILE_APPEND);
    }
}