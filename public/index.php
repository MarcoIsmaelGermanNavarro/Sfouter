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
    if (class_exists($fullControllerName)) {
        $controller = new $fullControllerName();
        if (method_exists($controller, $actionParam)) {
            $controller->$actionParam();
        } else {
            // Error de lógica: la acción no existe
            throw new Exception("La acción '$actionParam' no existe", 404);
        }
    } else {
        // Error de lógica: el controlador no existe
        throw new Exception("El controlador $fullControllerName no existe", 404);
    }
} catch (Exception $e) {
    // 1. Logueamos el error real en tu archivo Errores.log
    Errores::log($e->getMessage() . " | Trace: " . $e->getTraceAsString());

    // 2. Dependiendo del código, mostramos una vista u otra
    if ($e->getCode() === 404) {
        http_response_code(404);
        $errorController = new ErroresController();
        $errorController->error404();
    } else {
        // Cualquier otro error es un 500 (Error Interno)
        http_response_code(500);
        
        // Aquí llamas a tu vista personalizada de error 500
        /*
        $errorController = new ErroresController();
        $errorController->error500(); // Asegúrate de tener este método
        */
    }
    exit;
}