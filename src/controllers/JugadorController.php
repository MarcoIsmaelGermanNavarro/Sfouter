<?php

// Importamos en este caso tanto Controllers, como el repositorio de Jugador y el baseController
namespace Sfouter\controllers;
use Sfouter\models\repositories\JugadorRepository;
use Sfouter\controllers\BaseController;
use Sfouter\helpers\ValidatorHelper;
use Sfouter\helpers\Session;
use Sfouter\models\repositories\InformeRepository;
use Sfouter\config\Parameters;

class JugadorController  extends BaseController { 
    
public function FormularioCrear() {
    // 1. El guardián de seguridad que ya comprobamos que funciona
    $this->CheckAuth();
    
    // 2. Solo recogemos los errores de validación si el intento previo falló
    $errores = $_SESSION['Errores'] ?? []; 
    $dataFormulario = $_SESSION['Formulario'] ?? null; // Almacena lo que ya escribió para no borrarle los inputs
    
    $datos = [
        'titulo'  => 'Registrar Nuevo Futbolista', // 🌟 CORREGIDO: Título coherente con la acción
        'errores' => $errores,
        'dataFormulario' => $dataFormulario // Pasamos los datos viejos para la persistencia del formulario
    ]; 
    
    // 3. Renderizamos la vista de creación pura
    $this->renderizar("jugador/FormularioCrear", $datos); 
    
    // 4. Limpieza higiénica de la sesión
    unset($_SESSION['Errores']); 
    unset($_SESSION['Formulario']); 
}
    // En este caso el constructor no es necesario, ya que la session, es algo global. 

// Por ko que creo que no se usan parametros es porque los datos que necesitanmso viuendo desde el nos lo, no desde 
// ningun lugar en este caso ecterno. Claro los datos en este caso los coge de un formulario
public function Crear() { 
        $this->CheckAuth();
        
        $this->validarCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=Jugador&action=FormularioCrear");
            exit;
        }

        // Guardamos persistencia por si hay errores
        $_SESSION['Formulario'] = $_POST;
        $errores = [];

        // Captura y saneamiento estricto
        $nombre   = ValidatorHelper::sanear($_POST['nombre'] ?? '');
        $apellidos = ValidatorHelper::sanear($_POST['apellidos'] ?? '');
        $fechaNac = $_POST['FechaNac'] ?? '';

        // Validaciones de Nombre
        if (ValidatorHelper::estaVacio($nombre)) {
            $errores[] = "El nombre es obligatorio."; 
        } elseif (!ValidatorHelper::longitud($nombre, 2, 50)) { 
            $errores[] = "El nombre debe tener entre 2 y 50 caracteres."; 
        } elseif (!ValidatorHelper::soloLetras($nombre)) {
            $errores[] = "El nombre no puede contener números ni caracteres especiales."; 
        }

        // Validaciones de Apellido
        if (ValidatorHelper::estaVacio($apellidos)) {
            $errores[] = "El apellido es obligatorio.";
        } elseif (!ValidatorHelper::longitud($apellidos, 2, 50)) {
            $errores[] = "El apellido debe tener entre 2 y 50 caracteres.";
        } elseif (!ValidatorHelper::soloLetras($apellidos)) {
            $errores[] = "El apellido no puede contener números ni caracteres especiales.";
        }   

        // Validación de Fecha (Unificada en el array de errores)
        if (ValidatorHelper::estaVacio($fechaNac)) {
            $errores[] = "La fecha de nacimiento es obligatoria.";
        } elseif (!ValidatorHelper::fechaValida($fechaNac)) { // Usando tu ValidatorHelper
            $errores[] = "La fecha de nacimiento no puede ser futura.";
        }

        // Validación de la Foto
        $fotoArchivo = $_FILES['foto'] ?? null; 
        $nombreParaBd = 'default.png'; // 1. Valor por defecto si no suben nada. 

        $hayFoto = ($fotoArchivo && $fotoArchivo['error'] !== UPLOAD_ERR_NO_FILE); 

        if ($hayFoto) {
            // 2. Solo si hayb foto, la validamos con tu helper
            $errorFoto = ValidatorHelper::validarFoto($fotoArchivo, 2); 
            if ($errorFoto !== null) {
                $errores[] = $errorFoto; 
            }

        }

        // Si hay errores, rebotamos al FORMULARIO, no a Crear
        if (!empty($errores)) {
            $_SESSION['Errores'] = $errores;
            header("Location: index.php?controller=Jugador&action=FormularioCrear");
            exit;
        }

        try  {
       
        if ($hayFoto) {

        // Si el código llega aquí, los datos son 100% fiables
        $nombreParaBd = ValidatorHelper::generarNombreSeguro($fotoArchivo['name']);
        $rutaDestinoFisica = Parameters::getPhysicalPath() . $nombreParaBd;

        if (!move_uploaded_file($fotoArchivo['tmp_name'], $rutaDestinoFisica)) {
            throw new Exception("No se puede trasladar la imagen a la carpeta del servidor."); 

        }
    }
                // Preparamos los datos de forma exacta a las columnas de tu BD
                $datosJugador = [
                    'nombre'   => $nombre, 
                    'apellidos' => $apellidos, // Corregido el plural y la variable
                    'fechaNac' => $fechaNac, // Corregido nombre de variable
                    'foto'     => $nombreParaBd
                ]; 

                $jugadorRepo = new JugadorRepository(); 
                $jugadorRepo->insertar($datosJugador);  

                // Éxito total: limpiamos persistencias
                unset($_SESSION['Formulario']);
                $_SESSION['Success'] = "Jugador registrado con éxito.";
                
                header("Location: index.php?controller=Jugador&action=listarJugadores");
                exit;

         } catch (Exception $e) {

         $_SESSION['Errores'] = ["Error en el proceso". $e-> getMessage()]; 
         header("Location: index.php?controller=Jugador&action=FormularioCrear"); 
        exit; 
         }
    }



public function VerUnSoloJugador() {

// En este caso regoemos el id
$id = trim($_POST['id'] ?? ''); 
$errores = []; 

// Comprobamos en este caso que no este vacio. 
if (empty($id)) {

$errores = 'Lo siento, el ID no puede estar vacio'; 

}

if (empty($Errores)) { 

$repo = new JugadorRepository(); 

$jugador = $repo -> buscarPorId($id); 

if ($Jugador) {
// Guardamso en la session, para que lo muestre abajo. 
$_SESSION['JugadorEncontrado'] = $jugador; 

// Sin en este caso, mandarle el leyout que es main por defecto, 
// Lo vanmso a mostrar abajo en al misma pagina
 }  else {

   $_SESSION['Errores'] = ['No encontramso ese id']; 

    
}
}

 else {

    $_SESSION['Errores'] = $errores; 

    }

    // Redireccion correcta del formulario
    header('Location: index.php?controller=Jugador&action=FormularioSoloJugador'); 
    exit; 

}

// Este es el metodo de listar todos los jugadores. 

public function listarJugadores() {

    $JugadorRepo = new JugadorRepository(); 
    $lista = []; // Inicializamos la variable que se va a enviar a la vista

    if (isset($_GET['propios']) && $_GET['propios'] == 1) {
        $id = $_SESSION['user']->getId(); 
        $lista = $JugadorRepo->cargarTodo($id); 
        $titulo = "Mis jugadores ojeados"; 
    } else { 
        $lista = $JugadorRepo->cargarTodo(); 
        $titulo = "Lista global de Jugadores"; 
    }

    $this->renderizar("jugador/Verjugador", [
        'titulo' => $titulo,
        'datos' => $lista
    ]);
}

// Esta es la que se encarga de mostrar todos los informes de los usaurios por el id correspondidente que se ha pasado por el Get de la url,v 
public function verPerfil() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header('Location: index.php?controller=Jugador&action=listarJugadores');
        exit;
    }

    $jugadorRepo = new JugadorRepository(); 
    $jugador = $jugadorRepo->buscarPorId($id); // Ya no filtra por usuario

    if (!$jugador) {
        $_SESSION['Errores'] = ["El jugador no existe."];
        header('Location: index.php?controller=Jugador&action=listarJugadores');
        exit;
    }

    // 3. Buscar informes del jugador (PÚBLICOS)
    $informeRepo = new InformeRepository(); 
    $listaInformes = $informeRepo->listarPorJugador($id); // Método nuevo sin filtrar por usuario

    $this->renderizar("jugador/Perfil", [
        'titulo'   => "Perfil de " . $jugador->getNombre(), 
        'jugador'  => $jugador,
        'informes' => $listaInformes
    ]);
}
// Lo hacemos, en este caso es eliminar Jugador
public function eliminarJugador() {
    $this->CheckAuth(); 
    $this->validarCsrf();
    
    $id = $_GET['id'] ?? null; 
    $usuarioActivo = $_SESSION['user'];
    $rol = $usuarioActivo->getRol(); 

    if ($id === null) {
        $_SESSION['Errores'] = "ID de jugador no proporcionado.";
        header("Location: index.php?controller=Jugador&action=listarJugadores");
        exit;
    }

    try {
        $jugadorRepo = new JugadorRepository(); 
        $jugador = $jugadorRepo->buscarPorId($id); 

        if ($jugador && $rol === 'admin') {
            // Pasamos NULL como segundo parámetro porque el Admin tiene poder total
            // y no tenemos un idUsuario asociado al jugador.
            $borrado = $jugadorRepo->EliminarFila((int)$id, null); 

            if ($borrado) {
                $_SESSION['Success'] = "El jugador se ha eliminado correctamente."; 
            } else {
                $_SESSION['Errores'] = "Tiene que eliminar antes, los formularios para eliminar al Jugador."; 
            }
        } else {
            $_SESSION['Errores'] = "No tienes permisos de administrador para realizar esta acción.";
        }

    } catch(Exception $e) {
        $_SESSION['Errores'] = $e->getMessage(); 
    }

    header("Location: index.php?controller=Jugador&action=listarJugadores");
    exit; 
}
}
