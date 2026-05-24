<?php
namespace Sfouter\controllers;
use Sfouter\models\entities\InformeEntity;
use Sfouter\controllers\BaseController;
use Sfouter\models\repositories\InformeRepository;
use Sfouter\models\repositories\JugadorRepository;
use Sfouter\models\repositories\EquipoRepository;
use Sfouter\helpers\ValidatorHelper;



class InformeController extends BaseController {

// Aqui mostramos el formuario, es decir se carga el formulario, y lo mandamos a la vista
public function Crear() {
    // 1. Verificación de seguridad
    $this->CheckAuth(); 

    // 2. Capturar el ID del jugador que vamos a evaluar
    $idJugador = $_GET['idjugador'] ?? null;

    if (!$idJugador) {
        $_SESSION['errores'] = ["Debes seleccionar un jugador para crear un informe."];
        header('Location: index.php?controller=Jugador&action=listarJugadores');
        exit;
    }

    $dataFormulario = $_SESSION['Formulario'] ?? [];
    $errores = $_SESSION['Errores'] ?? [];

    // IMPORTANTE: Los errores los limpiamos tras leerlos para que no se queden "pegados"
    unset($_SESSION['Errores']);
    unset($_SESSION['Formulario']); 

    // 3. Opcional: Buscar el nombre del jugador para mostrarlo en el título del formulario
    $jugadorRepo = new JugadorRepository();
    $jugador = $jugadorRepo->buscarPorId($idJugador, $_SESSION['user']->getId());
    $EquipoRepo = new EquipoRepository(); // Necesitamos esta repo. 

    $datos = [
        'titulo' => "Nuevo Informe Técnico",
        'idJugador' => $idJugador,
        'nombreJugador' => $jugador ? $jugador->getNombre() : "Jugador", 
        'apellidos' => $jugador ? $jugador->getApellidos() : "", 
        'equipos' => $EquipoRepo -> obtenerTodosParaSelect(), // Traemos la lista para el Select. 
        'dataFormulario' => $dataFormulario,
        'Errores' => $errores
    ]; 

    $this->renderizar("informe/CrearInforme", $datos);

    // Las alertas se limpian mejor después de renderizar en el BaseController, 
    // pero si lo haces aquí, asegúrate de que no borras los mensajes antes de que la vista los lea.
}

// Esto es deespues de pasarlo por la vista, es decir el resultado de en este caso yo, poner o exigir los datos en el formulario. 
public function Guardar() {
    $this->CheckAuth();

    $this->validarCsrf();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $_SESSION['Formulario'] = $_POST; 
        $idUsuario    = $_SESSION['user']->getId();
        $errores = []; 

       // 2. Saneamiento de datos de texto
        $posicion      = ValidatorHelper::sanear($_POST['posicion'] ?? '');
        $observaciones = ValidatorHelper::sanear($_POST['observaciones'] ?? '');
        $fechaInf      = $_POST['fechaInf'] ?? '';

        $idJugador    = $_POST['idJugador'] ?? null;
        $idEquipo     = $_POST['idEquipo'] ?? null;
        $velocidad    = (int)($_POST['velocidad'] ?? 0);
        $resistencia  = (int)($_POST['resistencia'] ?? 0);
        $dribbling    = (int)($_POST['dribbling'] ?? 0);

        

       // 3. VALIDACIONES ESTRICTAS
        if (ValidatorHelper::estaVacio($posicion)) {
            $errores[] = "La posición en la que jugó es obligatoria.";
        }

        if (ValidatorHelper::estaVacio($idEquipo)) {
            $errores[] = "Debes seleccionar el equipo del encuentro.";
        }

        if (!ValidatorHelper::fechaValida($fechaInf)) {
            $errores[] = "La fecha de la observación no puede ser una fecha futura.";
        }

        // Validación del bloque técnico (1-10)
        if (!ValidatorHelper::enRango($velocidad, 1, 10) || 
            !ValidatorHelper::enRango($resistencia, 1, 10) || 
            !ValidatorHelper::enRango($dribbling, 1, 10)) {
            $errores[] = "Las notas de los atributos técnicos deben estar estrictamente entre 1 y 10.";
        }

      

        // 4. CONTROL DE FISCALIZACIÓN
        if (!empty($errores)) {
            $_SESSION['Errores'] = $errores;
            header("Location: index.php?controller=Informe&action=Crear&idJugador=$idJugador");
            exit;
        }

        try {

            $objetoFechaInf = !empty($fechaInf) ? new \DateTime($fechaInf) : null;

            // 3. Crear la entidad con el equipo del CONTEXTO del informe
            $nuevoInforme = new InformeEntity(
                id: null,
                idUsuario: $idUsuario,
                idJugador: (int)$idJugador,
                idEquipo: (int)$idEquipo, // El equipo donde jugó ESE día
                fechaInf: $objetoFechaInf,
                fechaReg: null,
                posicion: $posicion,
                velocidad: $velocidad,
                resistencia: $resistencia,
                dribbling: $dribbling,
                observaciones: $observaciones
            );

            // 4. Mandar al repositorio
            $informeRepo = new InformeRepository();


            if ($informeRepo->insertar($nuevoInforme->toArray())) {
                // ÉXITO: Aquí sí limpiamos los datos del formulario
                unset($_SESSION['Formulario']);
                unset($_SESSION['Errores']);
                $_SESSION['Success'] = "Informe guardado correctamente.";
                
                header("Location: index.php?controller=Jugador&action=verPerfil&id=$idJugador");
                exit;
            } else {
               $_SESSION['Errores'] = ["Error técnico en el servidor al guardar."];
            }

        } catch (InvalidArgumentException $e) {
            $_SESSION['Errores'] = [$e->getMessage()];
        }

// Si llega aquí, es que hubo un error: redirigimos al formulario
        header("Location: index.php?controller=Informe&action=Crear&idJugador=$idJugador");
        exit;
    }
}


// Ver el formulario en detalle.
public function VerDetalle() {
    $this->CheckAuth(); 

    $id = $_GET['id'] ?? null; 
    $idUsuario = $_SESSION['user']->getId(); // Usamos user como quedamos
    $idJugador = $_GET['idjugador'] ?? null; 

    if ($id) {
        try {
            // El Repositorio es el que busca en la BBDD, no la Entidad directamente
            $repo = new InformeRepository(); 
            $informe = $repo->buscarPorId($id, $idUsuario); 


            if ($informe) {

                $repoEquipo = new EquipoRepository();
                $equipo = $repoEquipo->buscarPorId($informe->getIdEquipo());  
                // Preparamos los datos para la vista
                $datos = [
                    'titulo'  => "Detalle del Informe #$id", 
                    'informe' => $informe,
                    'idJugador' => $idJugador, // Lo pasamos para el botón de "Volver"
                    "nombreEquipo" => $equipo ? $equipo -> getNombre() : "Equipo deconocido"
                ];

                // Renderizamos la vista de detalle
                $this->renderizar("informe/verDetalle", $datos);
                return; // Importante: Salimos de la función para que no ejecute el header de abajo
            } else {
                $_SESSION['Error'] = "No se encontró el informe o no tienes permiso.";
            }
        } catch (Exception $e) {
            $_SESSION['Error'] = "Error en el sistema: " . $e->getMessage();
        }
    } else {
        $_SESSION['Error'] = "Faltan parámetros obligatorios.";
    }

    // Si llegamos aquí es porque algo falló, volvemos al perfil
    header("Location: index.php?controller=Jugador&action=verPerfil&id=$idJugador");
    exit;
}

// Es boton de eliminar, el formulario en este caso. 
public function eliminar() {
    $this->CheckAuth(); //

    $this->validarCsrf();
    
    $id = $_GET['id'] ?? null; 
    $idJugador = $_GET['idjugador'] ?? null; 
    $usuarioActivo = $_SESSION['user']; //
    $rol = $usuarioActivo->getRol(); //

    if (!$id) {
        $_SESSION['Error'] = "ID de informe no válido.";
        header("Location: index.php?controller=Jugador&action=listarJugadores");
        exit;
    }

    try {
        $repo = new InformeRepository();
        $informe = $repo->buscarPorId($id); //

        if (!$informe) {
            throw new Exception("El informe no existe.");
        }

        // LÓGICA DE PERMISOS:
        // Si es admin, puede borrar. Si no, comparamos IDs de usuario.
        if ($rol === 'admin' || $informe->getIdUsuario() == $usuarioActivo->getId()) {
            
            // Si es admin, mandamos NULL al segundo parámetro para que el Repo no filtre por usuario
            $idSeguridad = ($rol === 'admin') ? null : $usuarioActivo->getId();
            
            $borradoExitoso = $repo->EliminarFila((int)$id, $idSeguridad); //

            if ($borradoExitoso) {
                $_SESSION['Success'] = "Informe eliminado correctamente.";
            } else {
                $_SESSION['Errores'] = "No se pudo eliminar el informe.";
            }
        } else {
            $_SESSION['Errores'] = "No tienes permiso para borrar este informe.";
        }

    } catch (Exception $e) {
        $_SESSION["Errores"] = $e->getMessage(); //
    }

    header("Location: index.php?controller=Jugador&action=verPerfil&id=$idJugador");
    exit;
}
 

public function misInformes() {
    // 1. Seguridad: Solo usuarios logueados
    $this->CheckAuth();

    // 2. Obtenemos el ID directamente de la sesión (Seguridad nivel experto)
    // Usamos 'user' que es la clave que definiste en tu Helper Session
    $idUsuario = $_SESSION['user']->getId();

    try {
        $repo = new InformeRepository();
        $JugadorRepo = new JugadorRepository(); 
        // Llamamos al método que ya ordenamos por fecha en el Repositorio
        $informes = $repo->listarPorUsuario($idUsuario);
        
        $datos = [
            'titulo' => "Mi Historial de Informes",
            'informes' => $informes
        ];

        // 3. Renderizamos la vista específica de listado de informes
        $this->renderizar("informe/listar", $datos);

    } catch (Exception $e) {
        $_SESSION['Error'] = "No se pudieron cargar tus informes.";
        header("Location: index.php?controller=Usuario&action=mostrarDashboard");
        exit;
    }
}



}