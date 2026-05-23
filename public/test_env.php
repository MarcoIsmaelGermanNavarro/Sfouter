<?php
$ruta = realpath(__DIR__ . '/../.env');
if (file_exists($ruta)) {
    echo "¡Archivo .env ENCONTRADO en: " . $ruta . "!";
} else {
    echo "ERROR: Archivo .env NO encontrado. Busqué en: " . $ruta;
}
die();