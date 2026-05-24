<?php

namespace Sfouter\controllers;
use Sfouter\config\Parameters;
use Sfouter\utils\Errores;
use Sfouter\models\repositories\BaseRepository;
use Sfouter\database\ConexionSfouter;
use Sfouter\helpers\Session;
use PDO; 


 class BaseController {

 // Aqui esta el error, lo que hago es decir, si la conexion es nula, la cogo directamente del archivo de Conexion
 protected ?PDO $db = null; 

 public function __construct() {


 if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
 }

 // Generamos el token CSRF una sola vez por sesión
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

 try {
 // La he insertado como una funcion estatica de la clase. 
       //$this -> db = ConexionSfouter::conectar(); 
 } catch (\Exception $e) {
   // Si la bd, falla que no muera el sistema
   Errores::log("Error de conexion: " . $e -> getMessage()); 
 }
    }
public function renderizar($vista, $datos = [], $layout = "main") { 

    $datos['csrf_token'] = $_SESSION['csrf_token']; 
    // Extreamos los datos
    extract($datos);
    $rutavista = Parameters::viewsPath() . $vista . ".php"; 

    if (!file_exists($rutavista)) {
        Errores::log('No se encuentra la vista', ['ruta' => $rutavista]);
       
    }

    if ($layout == false) {
        // Si no hay layout, cargamos la vista directamente
        require $rutavista; 
    } else { 
        // 1. CAPTURAMOS la vista en una variable llamada $contenido
        ob_start(); // Abrimos el grifo del búfer
        require $rutavista;
        $contenido = ob_get_clean(); // Cerramos el grifo y guardamos el agua en $contenido, de esta manera despues lo 
        // volcamos en este caso con Requiere_once. 

        // 2. Cargamos el Layout
        $rutaLayout = Parameters::viewsPath() . "layouts/" . $layout . ".php"; 

        if (file_exists($rutaLayout)) { 
            require_once $rutaLayout; // Aquí el Layout usará la variable $contenido
            
        } else { 
            Errores::log('Layout no encontrado', ['ruta' => $rutaLayout]);
            die("Error crítico: Layout no encontrado.");
          }
      }
   }

   /**
     * SEGURIDAD: Validación de tokens CSRF para formularios
     */
    public function validarCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tokenEnviado = $_POST['csrf_token'] ?? '';
            
            if (!isset($_SESSION['csrf_token']) || $tokenEnviado !== $_SESSION['csrf_token']) {
                Errores::log("Ataque CSRF bloqueado.");
                die("Error de seguridad: Token inválido.");
            }
        }
    }
    
/**
 * AUTENTICACIÓN: ¿Está el usuario registrado en el sistema?
 * (Usa tu método isUserLogged)
 */
public function CheckAuth(): void {

    if (Session::isUserLogged() === false) {
        $_SESSION['Error_Global'] = "Acceso denegado. Debes iniciar sesión para acceder a Sfouter.";
        header("Location: index.php?controller=Login&action=mostrarLogin");
        exit; // Detiene la ejecución aquí mismo si es un anónimo
    }
}

/**
 * AUTORIZACIÓN: ¿El usuario es Administrador?
 * (Usa tu método isUserAdminLogged)
 */
public function isAdmin(): void {
    // 1. Primero obligamos a que pase el filtro de estar logueado
    $this->CheckAuth();

    // 2. Si está logueado pero NO es administrador, lo echamos de forma SEGURA al Dashboard
    // 🌟 Usamos tu método estático específico para el Admin
    if (Session::isUserAdminLogged() === false) {
        $_SESSION['Errores'] = "Lo siento, no eres administrador. No puedes realizar dicha acción."; 
        
        // Redirección ESTÁTICA y SEGURA a su Dashboard de Scout (Cero bucles infinitos)
        header("Location: index.php?controller=Usuario&action=mostrarDashboard"); 
        exit; // Detiene la ejecución para que el Scout no haga destrozos
    }
}

   // Lo hacemos para que la clase hija en est caso envie la alerta
   public function setAlerta($texto, $categoria) {

   //
   $_SESSION['notificacion'] = [

   'mensaje' => $texto,  
   'tipo' => $categoria 
   ]; 
   }

 }
?> 