<?php


// 1. Configuración de errores (Solo para desarrollo, luego se desactiva)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Estamos en el index.php</h1>"; //

// 2. Definición de la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// 3. Carga del Autoloader de Composer
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

// 4. Inicializar configuración dinámica
Sfouter\config\ConfigBD::init();

// 5. Importación de clases necesarias
use Sfouter\config\Parameters;
use Sfouter\controllers\ErroresController;
use Sfouter\utils\Errores;

// 6. Inicio de sesión único
if (session_status() === PHP_SESSION_NONE) {
    session_name("Sfouter_Session");
    session_start();
}

// 7. Lógica de Enrutamiento (Router)
$controllerParam = $_GET['controller'] ?? Parameters::$CONTROLLER_DEFAULT;
$actionParam = $_GET['action'] ?? Parameters::$ACTION_DEFAULT;

// Construimos el nombre de la clase
$controllerName = ucfirst($controllerParam) . "Controller";
$fullControllerName = "Sfouter\\controllers\\" . $controllerName;

// 8. Despacho (Dispatcher)
try {
    echo "DEBUG: Buscando clase $fullControllerName...<br>"; // Línea nueva
    
    if (class_exists($fullControllerName)) {
        echo "DEBUG: La clase existe. Instanciando...<br>"; // Línea nueva
        $controller = new $fullControllerName();
        
        echo "DEBUG: Instancia creada. Buscando método $actionParam...<br>"; // Línea nueva
        if (method_exists($controller, $actionParam)) {
            $controller->$actionParam();
        } else {
            throw new Exception("La acción '$actionParam' no existe en $fullControllerName", 404);
        }
    } else {
        throw new Exception("El controlador '$fullControllerName' no existe. Verifica el Namespace y el archivo.", 404);
    }
} catch (Throwable $e) { // Cambiamos Exception por Throwable para capturar errores de tipo también
    echo "<h1>ERROR FATAL EN DISPATCHER</h1>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . " en línea " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}
