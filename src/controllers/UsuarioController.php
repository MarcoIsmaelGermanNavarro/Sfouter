<?php
namespace Sfouter\controllers; 

use Sfouter\models\entities\UsuarioEntity;
use Sfouter\controllers\BaseController;
use Sfouter\models\repositories\UsuarioRepository;
use Sfouter\models\repositories\JugadorRepository;
use Sfouter\models\repositories\InformeRepository;
use Sfouter\helpers\ValidatorHelper;
use Sfouter\helpers\Session;

class UsuarioController extends BaseController {


public function mostrarRegistro() {


    // Recuperamos ambos paquetes
    $errores = $_SESSION['Errores'] ?? [];
    $datosGuardados = $_SESSION['Formulario'] ?? [];
    
    // Limpiamos AMBOS de la sesión
    unset($_SESSION['Errores']);
    unset($_SESSION['Formulario']);

    $datosParaVista = [
        'titulo'   => 'Registro',
        'errores'  => $errores,
        'old'      => $datosGuardados // Estos son los datos para rellenar los inputs
    ];

    $this->renderizar('register/Registro', $datosParaVista);

}


public function register() {

$this->validarCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?controller=Usuario&action=mostrarRegistro");
        exit;
    }

$errores = []; 
$_SESSION['Formulario'] = $_POST; 
$_SESSION['Errores'] = []; 

$nombre   = ValidatorHelper::sanear($_POST['nombre'] ?? '');
$apellidos = ValidatorHelper::sanear($_POST['apellidos'] ?? ''); 
$email    = ValidatorHelper::sanear($_POST['email'] ?? '');
$password = trim(($_POST['password'])); 

// 1. Validar Nombre
    if (ValidatorHelper::estaVacio($nombre) || !ValidatorHelper::longitud($nombre, 2, 50) || !ValidatorHelper::soloLetras($nombre)) {
  
        $errores[] = "El nombre es obligatorio y debe tener entre 2 y 50 caracteres, y no puede contner numeros";
    }

    // 2. Validar Email
    if (!ValidatorHelper::emailValido($email)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    }

    // 3. Validar Contraseña Segura
    if (!ValidatorHelper::contrasenaSegura($password)) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número.";
    }
    // 4. Validar que el Email no esté ya registrado
    $usuarioRepo = new UsuarioRepository();
    if (empty($errores) && ValidatorHelper::emailRepetido($email, $usuarioRepo)) {
        $errores[] = "Este correo electrónico ya está registrado en el sistema.";
    }





// Le asiganamos a errores, ene ste caso el araray de errores valga la redundancia
// 4. Manejo de Errores
    if (!empty($errores)) {
        $_SESSION['Errores'] = $errores; 
        header("Location: index.php?controller=Usuario&action=mostrarRegistro"); 
        exit; 
    }

    try { 

    
    $datos = [
        'nombre' => $nombre, 
        'apellidos' => $apellidos, 
        'email' => $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT), 
        'rol' => 'user',
        'fechaReg' => date('Y-m-d H:i:s') // Es buena práctica mandarla desde aquí
    ];
        


    $CrearUsuario = $usuarioRepo -> RegistrarUsuario($datos); 

    if ($CrearUsuario) { 

    
    // Para que le presente en este caso la home. 
        unset($_SESSION['Formulario']); 
        unset($_SESSION['Errores']);

      // OJO: Aquí deberías recuperar el objeto usuario real si necesitas su ID
        $_SESSION['Success'] = "¡Bienvenido, $nombre!";
        header("Location: index.php?controller=Home&action=index"); 
        exit;
        } else {
            $_SESSION['Errores'] = ["Error interno al crear al usuario. "]; 
            header("Location: index.php?controller=Usuario&action=mostrarRegistro"); 
            exit; 
        }
        
        
        // Una pregunt, que es lo que ahce el header le cambia la URl, al 
        // usuario, es decir se la recarga para que en este tipo de casoso, 
        // como el que estamos trabajando, no dispare, y se pierda el contenido? 
        }  catch (Exception $e) {
        $_SESSION['Errores'] = ["Error al crear la cuenta: " . $e->getMessage()];
        header("Location: index.php?controller=Usuario&action=mostrarRegistro");
        exit;
    }
    }

        


public function editar() {
    $this->CheckAuth();

    // 1. Extraemos los mensajes a variables locales
    $success = $_SESSION['Success'] ?? null;
    $errores = $_SESSION['Errores'] ?? [];

 

    $identidad = $_SESSION['user']; 

    $datos = [
       'identidad' => $identidad,
       'success'   => $success, // Pasamos la variable local
       'errores'   => $errores  // Pasamos la variable local
    ]; 

    $this->renderizar("usuario/EditarUsuario", $datos); 

       // 2. LIMPIAMOS LA SESIÓN DE INMEDIATO (Esto evita que el mensaje persista)
    unset($_SESSION['Success']);
    unset($_SESSION['Errores']);
}

public function guardarEdicion() {
    $this->validarCsrf();

    $identidadActual = $_SESSION['user'] ?? null;
    if (!$identidadActual) {
        header('Location: index.php');
        exit;
    }

    // 1. SANEAMIENTO: Limpiamos las entradas antes de hacer nada
    $nombre = ValidatorHelper::sanear($_POST['nombre'] ?? '');
    $apellidos = ValidatorHelper::sanear($_POST['apellidos'] ?? '');
    $nuevoEmail = ValidatorHelper::sanear($_POST['email'] ?? '');
    $id = $identidadActual->getId();

    $errores = [];


    // 2. VALIDACIÓN: Usamos ValidatorHelper como un muro

    if (!ValidatorHelper::soloLetras($nombre)) {
    $errores[] = "El nombre no puede contener números ni caracteres especiales.";
    }

    if (!ValidatorHelper::soloLetras($apellidos)) {
    $errores[] = "El nombre no puede contener números ni caracteres especiales.";
    }


    if (ValidatorHelper::estaVacio($nombre) || !ValidatorHelper::longitud($nombre, 2, 50)) {
        $errores[] = "El nombre es inválido o demasiado corto.";
    }

    if (!ValidatorHelper::emailValido($nuevoEmail)) {
        $errores[] = "El formato de email no es válido.";
    }

    $repo = new UsuarioRepository();
    // 3. COMPROBACIÓN LÓGICA (email ya ocupado por otro)
    if (ValidatorHelper::emailRepetido($nuevoEmail, $repo, $id)) {
        $errores[] = "Lo siento, ese email ya está siendo utilizado por otro usuario.";
    }

    // 4. DECISIÓN FINAL: Si hay errores, frenamos y avisamos
    if (!empty($errores)) {
        $_SESSION['Errores'] = $errores;
        header('Location: index.php?controller=Usuario&action=editar');
        exit;
    }

    // 5. ACCIÓN: Todo está validado, es seguro proceder
    $usuarioEditado = new UsuarioEntity($id, $nombre, $apellidos, $nuevoEmail, $identidadActual->getRol());

    if ($repo->actualizarUsuario($usuarioEditado)) {
        $_SESSION['user'] = $usuarioEditado;
        $_SESSION['Success'] = "¡Perfil actualizado con éxito!";
    } else {
        $_SESSION['Errores'] = ["Error técnico al guardar en la base de datos."];
    }

    header('Location: index.php?controller=Usuario&action=editar');
    exit;
}

public function logOut() {

// Si el usuario, no esta logueado no puede en este caso cerrar sesion, aunque en el header, se le manda a iniciar sesion 
$this -> CheckAuth(); 
// Este es un metodo, que tenemos en nuestro helpers, no se si te acuardas de Session.php. 
Session::Destroy(); 


session_start(); 
$_SESSION['Success'] = "Has cerrrado sesión correctamente. ¡Vuelva pronteo!"; 

header("Location: index.php?controller=Login&action=mostrarLogin"); 
exit; 

}

// En este caso solo faltaria arreglar el header cuando en este caso lo arreglemos. 



public function mostrarDashboard() {

// En este caso accede a la variable del baseController, y automatricamente, si en este caso, 
// esta, sin loguear, lo manda en este caso directo, al login y cancel la operacion no? 
$this -> CheckAuth(); 

$idUsuario = $_SESSION['user'] -> getId(); 


$Jugador = new JugadorRepository(); 
$Informe = new InformeRepository(); 



$datos = [
    'titulo' => "Mi panel de Scouting", 
    'NumeroJugadores' => $Jugador -> numeroJugadores($idUsuario), 
    'NumeroInformes' =>  $Informe -> numeroInformes($idUsuario)
    ]; 

    $this->renderizar("dashboard/Dashboard", $datos); 
    } 




public function gestionRoles() {
    // 1. El guardián: Si no es admin, redirige al Dashboard o al Login y mete un 'exit'
    $this->isAdmin();

    // 2. Si el código sigue vivo aquí, el usuario es Admin. Vamos a por los datos.
    $usuarioRepo = new UsuarioRepository();
    
    // Recuperamos todos los usuarios para listarlos en la tabla
    // (Asumo que tienes un método 'findAll' o 'obtenerTodos' en tu repositorio base)
    $usuariosRegistrados = $usuarioRepo->cargarTodo(); 

    // 3. Pasamos las variables a la vista ejecutiva
    $datosVista = [
        'titulo'   => 'Control de Privilegios y Roles',
        'usuarios' => $usuariosRegistrados
    ];

    // 4. Renderizamos la vista de gestión de roles que maquetamos
    // (Ajusta el método de renderizado según cómo lo tengas en tu BaseController)
    $this->renderizar('usuario/gestionRoles', $datosVista);
}

// Lo utilizamos en este caso para poder actualizarRol, es decir en este caso el rol del usuario, ya sea admin o scout. 
public function actualizarRol() {

    $this->validarCsrf();

  $this -> isAdmin(); 

    // 2. Recogemos los datos enviados por el formulario POST de la vista
    $idModificar = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
    $nuevoRol = isset($_POST['nuevo_rol']) ? trim($_POST['nuevo_rol']) : null;
    $idAdminLogueado = $_SESSION['user']->getId();

    // 3. Validación de seguridad extra (Evitar auto-degradación)
    if ($idModificar === $idAdminLogueado) {
        $_SESSION['Errores'] = ["Error táctico: No puedes modificar tus propios permisos de administrador."];
        header("Location: index.php?controller=Usuario&action=gestionRoles");
        exit;
    }

    if ($idModificar && $nuevoRol) {
        // Instanciamos el repositorio si no está instanciado
        $usuarioRepo = new \Sfouter\src\models\repositories\UsuarioRepository();
        
        // 4. Llamamos a tu método de BD pasándole los parámetros ordenados
        $resultado = $usuarioRepo->actualizarRol($idModificar, $nuevoRol, $idAdminLogueado);

        if ($resultado) {
            $_SESSION['Success'] = "El rol del usuario ha sido actualizado correctamente a '" . strtoupper($nuevoRol) . "'.";
        } else {
            $_SESSION['Errores'] = ["No se pudieron guardar los cambios en la base de datos debido a un error interno."];
        }
    } else {
        $_SESSION['Errores'] = ["Datos de formulario incompletos o corruptos."];
    }

    // 5. Redirección segura para limpiar la petición POST
    header("Location: index.php?controller=Usuario&action=gestionRoles");
    exit;
}
}

?> 