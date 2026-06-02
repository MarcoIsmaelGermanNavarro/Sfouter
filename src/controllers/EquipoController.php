<?php 

namespace Sfouter\controllers;

// Ponemos en este caso los 
use Sfouter\controllers\BaseController;
use Sfouter\models\repositories\EquipoRepository;
use Sfouter\helpers\ValidatorHelper;
use Sfouter\helpers\Session;     

class EquipoController extends BaseController {



public function mostrarFormulario() {

// Lo que cogemos en este caso quitamos en este caso lo que tenga puesto el formulario
$dataFormulario = $_SESSION['Formulario'] ?? null; 
$Errores = $_SESSION['Errores'] ?? []; 

unset($_SESSION['Formulario']); 
unset($_SESSION['Errores']); 

$datos = [
    "titulo" => 'Formulario Equipos',
    "Errores" => $Errores, 
    "dataFormulario" => $dataFormulario
    
]; 

$this -> renderizar("equipo/equipoCrear", $datos); 



}

public function guardar() {
    
    $this->CheckAuth();  
    $this->validarCsrf();

    $_SESSION['Formulario'] = $_POST; 

    $nombre = isset($_POST['nombre']) ? ValidatorHelper::sanear($_POST['nombre']) : '';
    $errores = []; 

    // Validaciones de formato
    if (ValidatorHelper::estaVacio($nombre)) {
        $errores[] = "El nombre del equipo no puede estar vacío.";
    }

    if (!ValidatorHelper::longitud($nombre, 3, 50)) {
        $errores[] = "El nombre del equipo debe tener entre 3 y 50 caracteres.";
    }

    if (!ValidatorHelper::soloLetras($nombre)) {
        $errores[] = "El nombre solo puede contener letras"; 
    }
    

    if (!empty($errores)) {
        $_SESSION['Errores'] = $errores;
        header("Location: index.php?controller=Equipo&action=mostrarFormulario");
        exit;
    }

    $EquipoRepo = new EquipoRepository(); 

    // 🌟 CORREGIDO: Ya no le pasamos la identidad del usuario
    if ($EquipoRepo->existeNombre($nombre)) {
        $_SESSION['Errores'] = ["El nombre del equipo ya existe en el sistema."]; 
        header("Location: index.php?controller=Equipo&action=mostrarFormulario");
        exit;
    }

    // 🌟 CORREGIDO: Tu array va en sintonía con tu base de datos (solo el nombre)
    $datosParaInsertar = [
        'nombre' => $nombre
    ]; 

    // Se ejecuta el insert de tu BaseRepository mapeando solo la columna 'nombre'
    $EquipoGuardar = $EquipoRepo->insertar($datosParaInsertar); 

    if ($EquipoGuardar) {
        unset($_SESSION['Formulario']);
        unset($_SESSION['Errores']);
        $_SESSION['Success'] = 'Se ha insertado el equipo correctamente.';
        header("Location: index.php?controller=Equipo&action=listar");
    } else {
        $_SESSION['Errores'] = ['Lo siento, ha ocurrido un error al realizar la inserción.'];
        header("Location: index.php?controller=Equipo&action=mostrarFormulario");
    }
    exit;
}

public function listar() {
    $this->isAdmin();

    $equipoRepo = new EquipoRepository();
    $equipos = $equipoRepo->obtenerTodosParaSelect();

    $errores = $_SESSION['Errores'] ?? [];
    $success = $_SESSION['Success'] ?? null;
    unset($_SESSION['Errores'], $_SESSION['Success']);

    $this->renderizar("equipo/Listar", [
        'titulo'  => 'Gestión de Equipos',
        'equipos' => $equipos,
        'errores' => $errores,
        'success' => $success,
    ]);
}

public function editar() {
    $this->isAdmin();

    $id = $_GET['id'] ?? null;
    if (!$id) {
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    $equipoRepo = new EquipoRepository();
    $equipo = $equipoRepo->buscarPorId((int)$id);

    if (!$equipo) {
        $_SESSION['Errores'] = ["El equipo no existe."];
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    $errores = $_SESSION['Errores'] ?? [];
    unset($_SESSION['Errores']);

    $this->renderizar("equipo/Editar", [
        'titulo'  => 'Editar Equipo',
        'equipo'  => $equipo,
        'errores' => $errores,
    ]);
}

public function actualizar() {
    $this->isAdmin();
    $this->validarCsrf();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    $id     = (int)($_POST['id'] ?? 0);
    $nombre = ValidatorHelper::sanear($_POST['nombre'] ?? '');
    $errores = [];

    if (!$id) {
        $_SESSION['Errores'] = ["ID inválido."];
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    if (ValidatorHelper::estaVacio($nombre)) {
        $errores[] = "El nombre del equipo no puede estar vacío.";
    } elseif (!ValidatorHelper::longitud($nombre, 3, 50)) {
        $errores[] = "El nombre debe tener entre 3 y 50 caracteres.";
    } elseif (!ValidatorHelper::soloLetras($nombre)) {
        $errores[] = "El nombre solo puede contener letras.";
    }

    if (!empty($errores)) {
        $_SESSION['Errores'] = $errores;
        header("Location: index.php?controller=Equipo&action=editar&id={$id}");
        exit;
    }

    $equipoRepo = new EquipoRepository();
    $equipoRepo->actualizarInfo($id, ['nombre' => $nombre]);

    $_SESSION['Success'] = "Equipo actualizado correctamente.";
    header("Location: index.php?controller=Equipo&action=listar");
    exit;
}

public function eliminar() {
    $this->isAdmin();
    $this->validarCsrf();

    $id = $_GET['id'] ?? null;
    if (!$id) {
        $_SESSION['Errores'] = ["ID no proporcionado."];
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    $equipoRepo = new EquipoRepository();
    $equipo = $equipoRepo->buscarPorId((int)$id);

    if (!$equipo) {
        $_SESSION['Errores'] = ["El equipo no existe."];
        header("Location: index.php?controller=Equipo&action=listar");
        exit;
    }

    $informesBorrados = $equipoRepo->eliminarConInformes((int)$id);

    if ($informesBorrados === -1) {
        $_SESSION['Errores'] = "Error al eliminar el equipo. Inténtalo de nuevo.";
    } elseif ($informesBorrados === 0) {
        $_SESSION['Success'] = "Equipo eliminado correctamente.";
    } else {
        $_SESSION['Success'] = "Equipo eliminado junto con {$informesBorrados} informe(s) asociado(s).";
    }

    header("Location: index.php?controller=Equipo&action=listar");
    exit;
}
}
