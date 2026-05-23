<?php
// Siempre recuerda usar "\", estas barras
namespace Sfouter\src\controllers;
use Sfouter\src\controllers\BaseController;
use Sfouter\src\models\repositories\UsuarioRepository;
use Sfouter\src\helpers\ValidatorHelper;
use Sfouter\src\helpers\Session;                     



class LoginController extends BaseController {
// Vale en este caso que es lo que pasa, es decir, no necesitamos en este caso instanciar, el 
// contructor ya que en este caso la conexion a la base de datos la heredamos en este caso, +
// de baseController, asi como en este caso la sesiones, y los metodos de renderizaros, etc. 


// ¿Que es lo que, ene ste caso recoge , la peticion de loguear y basicamente te mete la vista ?
public function mostrarLogin() {

// He dudado en este caso el insertarle el titulo, y ponerselo directamente al 
// footer, que es lo que tendria mas sentido, ya que el controller no se encarga de mostar datos, pero 
// tambinen asi hago mas mdulable el programa. 

$errores = $_SESSION['Errores'] ?? []; 

$datosGuardados = $_SESSION['Formulario'] ?? [];

   // Limpiamos AMBOS de la sesión
    unset($_SESSION['Errores']);
    unset($_SESSION['Formulario']);

$datos = ['titulo' => 'Login',
         'errores' => $errores, 
         'old' => $datosGuardados
         ];

$this->renderizar('login/Login', $datos);

         
}

public function loguear() {

$this->validarCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?controller=Login&action=mostrarLogin"); 
    exit; 
}

// 1. Recogida, y sanemianto de los datos. 
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL); 
    $password = $_POST['password'] ?? '';  
    $errores = []; 

  if (ValidatorHelper::estaVacio($email) || ValidatorHelper::estaVacio($password)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!empty($errores)) {
        $_SESSION['Errores'] = $errores;
        header("Location: index.php?controller=Login&action=mostrarLogin");
        exit;
    }
   
     // Buscamos al usuario por Email
    $usuarioRepo = new UsuarioRepository();
    $usuario = $usuarioRepo->buscarPorEmail($email); // Este método te devuelve la Entity o null

    // Verificamos si existe y si la contraseña coincide con el Hash de la BD
    if ($usuario && password_verify($password, $usuario->getPassword())) {
        
        // LOGIN CORRECTO: Creamos la sesión con el objeto de la entidad
        $_SESSION['user'] = $usuario;
        
        $_SESSION['Success'] = "Bienvenido de nuevo, " . $usuario->getNombreCompleto();
        header("Location: index.php?controller=Usuario&action=mostrarDashboard");
        exit;
    } else {
        // Por seguridad, da un mensaje genérico. No digas "La contraseña está mal" o "El email no existe".
        // Así evitas dar pistas a los hackers.
        $_SESSION['Errores'] = ["El correo electrónico o la contraseña son incorrectos."];
        header("Location: index.php?controller=Login&action=mostrarLogin");
        exit;
    }
}


// ¿Mis preguntas(Contestadas): 
// 1. Porque en este caso llammaos a UsuarioRepository, y se crea una instancia, para usar sus  metodso, no
// `pero en este caso despues podemos usar, los metodos, como objetos eso me imagino que es por la entidad
// 
// 2. Me falta comprobar la contrseña, como no puedo accder directamente a la contraseña, solo podria en el metodo de 
// comprobacion que esta justo en este caso, En el medtodo de Logium de Usuario Repository
// 
// 
// 3. Si quiero en este caso que ponga una alert, que diga en est caso bienvenido tal, en el home, que tengo que hacer, 
// Mandarle los datos directamente desde aqui al home controller, o en est caso mandarlo, desde el homecontroller a la vista
// tner un if o else, dependiedno si accedmos en este caso desde registro o login, o tener dos home una para registro y otra oara login?
}
?> 