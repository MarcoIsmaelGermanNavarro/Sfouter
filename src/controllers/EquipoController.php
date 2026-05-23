<?php 

namespace Sfouter\src\controllers;

// Ponemos en este caso los 
use Sfouter\src\controllers\BaseController;
use Sfouter\src\models\repositories\EquipoRepository;
use Sfouter\src\helpers\ValidatorHelper;
use Sfouter\src\helpers\Session;     

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
        header("Location: index.php?controller=Home&action=index"); 
    } else {
        $_SESSION['Errores'] = ['Lo siento, ha ocurrido un error al realizar la inserción.'];
        header("Location: index.php?controller=Equipo&action=mostrarFormulario"); 
    }
    exit; 
}

}





?> 