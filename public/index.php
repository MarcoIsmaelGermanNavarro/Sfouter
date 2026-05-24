<?php

echo "Carpeta raíz: " . BASE_PATH . "<br>";
$files = scandir(BASE_PATH);
echo "Contenido de la raíz: " . implode(", ", $files);
die();

// 1. Inicio de sesión único
if (session_status() === PHP_SESSION_NONE) {
    session_name("Sfouter_Session");
    session_start();
}


// 2. Configuración de errores (Solo para desarrollo, luego se desactiva)
ini_set('display_errors', 1);
error_reporting(E_ALL);


// 3. Definición de la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// 4. Carga del Autoloader de Composer
$autoloadPath = BASE_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (!file_exists($autoloadPath)) {
    die("Error Crítico: Ejecuta 'composer install' para generar el autoload.");
}

require_once $autoloadPath;

// CÓDIGO CORREGIDO (EL QUE NO FALLA)
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}
// Si el archivo no existe, no hace nada porque las variables 
// ya están inyectadas automáticamente por Railway en el sistema.

// 5. Inicializar configuración dinámica
Sfouter\config\ConfigBD::init();

// 6. Importación de clases necesarias
use Sfouter\config\Parameters;
use Sfouter\controllers\ErroresController;
use Sfouter\utils\Errores;


// 7. Lógica de Enrutamiento (Router)
$controllerParam = $_GET['controller'] ?? Parameters::$CONTROLLER_DEFAULT;
$actionParam = $_GET['action'] ?? Parameters::$ACTION_DEFAULT;

// Construimos el nombre de la clase
$controllerName = ucfirst($controllerParam) . "Controller";
$fullControllerName = "Sfouter\\controllers\\" . $controllerName;

// 8. Despacho (Dispatcher)
try {
    if (class_exists($fullControllerName)) {
        $controller = new $fullControllerName();
        if (method_exists($controller, $actionParam)) {
            $controller->$actionParam();
        } else {
            throw new Exception("La acción '$actionParam' no existe", 404);
        }
    } else {
        throw new Exception("Controlador $fullControllerName no existe", 404);
    }
} catch (Throwable $e) {
    // Aquí sí puedes usar echo porque aún no has enviado contenido al navegador
    echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
    exit;
}
