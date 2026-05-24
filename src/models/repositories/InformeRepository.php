<?php

namespace Sfouter\models\repositories;
use Sfouter\models\repositories\BaseRepository;
use Sfouter\models\entities\BaseEntity; 
use Sfouter\models\entities\InformeEntity;
use Sfouter\utils\Errores; 
use PDO; 
use PDOException; // Importante en este caso importar la excepcion

class InformeRepository extends BaseRepository {

    public function __construct(?PDO $db = null) {

    // ¿En el constructor tambien se puede en este caso poner la tabla informe directamente
    // y le quitamos trabajo al controlador?
    parent::__construct("informe",InformeEntity::class, $db); 
    }

    
    private function hidratar(array $d): InformeEntity {
        return new InformeEntity(
            (int)$d['id'],
            (int)$d['idUsuario'],
            (int)$d['idJugador'],
            (string)($d['nombre_jugador'] ?? 'Desconocido'),
            (int)$d['idEquipo'],
            new \DateTime($d['fechaInf']), // Obligatorio según tu SQL
            $d['fechaReg'] ? new \DateTime($d['fechaReg']) : null,
            $d['posicion'],
            (int)$d['velocidad'],
            (int)$d['resistencia'],
            (int)$d['dribbling'],
            $d['observaciones']
        );
    }

    // Lo que hacemos es listar, todos, los informes que corresponden a un jugador. 
    public function listarPorJugador($idJugador, ?int $idUsuario = null) { 
        try {

// SQL optimizado (más rápido)
$consulta = "SELECT * FROM informe WHERE idJugador = :idJugador";

$sentencia = $this->db->prepare($consulta); 
$sentencia->execute(['idJugador' => $idJugador]); 
return array_map([$this, 'hidratar'], $sentencia->fetchAll(PDO::FETCH_ASSOC)); 
    } catch(\PDOException $e) {

    Errores::log('Fallo en el informe repository, en la consulta listarJugadores' . $e -> getmessage(), [
        'user:id' => $idUsuario
    ]); 

    // Array vacio para mantener la vista. 
    return[]; 
    
        }

    }

    public function getUltimoInforme($idJugador, ?int $idUsuario = null) {

    try {

    
        $consulta = "Select i.* 
                    From informe i 
                    join jugador j on i.idJugador = j.id
                    join usuario u on i.idUsuario = u.id
                    Where idJugador = :idJugador
                    Order by i.fecha desc
                    LIMIT 1";

        $sentencia = $this -> db -> prepare ($consulta); 
        $sentencia -> execute(['idJugador' => $idJugador]); 
       
        $fila = $sentencia->fetch(PDO::FETCH_ASSOC);
        return $fila ? $this->hidratar($fila) : null;

        
    } catch (\PDOException $e) { 

        Errores::log("Ha ocurrido un error en la consulta de Ultimo informe de 
                     la clase de Informe Entity" . $e-> getMessage(), [
                        'IdUsuario' => $idUsuario, 
                        'idJugador' => $idJugador
                     ]); 
    }
        // En este acso inserta el array de retur, si algo sale mal para no reomper en este caso el diseño
        return null; 
    } 

   public function cargarTodo(?int $userId = null): array {
    try {
        // 1. Consulta base con el JOIN para tener SIEMPRE el nombre del jugador en la tabla
        $consulta = "SELECT i.*, j.nombre AS nombreJugador 
                     FROM informe i 
                     INNER JOIN jugador j ON i.idJugador = j.id";

        // 2. Si pasamos un ID (Scout), filtramos sus informes. Si es null (Admin), se salta el IF y trae TODO.
        if ($userId !== null) {
            $consulta .= " WHERE i.idUsuario = :userId";
            $parametros = ['userId' => $userId];
        } else {
            $parametros = [];
        }

        // Ordenamos para que los últimos informes salgan los primeros
        $consulta .= " ORDER BY i.fechaReg DESC";

        $sentencia = $this->db->prepare($consulta);
        $sentencia->execute($parametros);
        
        $filas = $sentencia->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($filas)) {
            return [];
        }

        // 3. Convertimos los arrays planos de la BD en Objetos UsuarioEntity/InformeEntity (Hidratación)
        return array_map([$this, 'hidratar'], $filas);

    } catch (\PDOException $e) {
        Errores::log("Error crítico en InformeRepository::CargarTodo: " . $e->getMessage());
        return [];
    }
}

    public function GuardarInforme($informe) {
    try {
        // 1. Convertimos el objeto en array asociativo
        $datos = get_object_vars($informe); 

        // 2. Creamos los nombres de las columnas (id, idUsuario, etc.)
        $columnas = implode(', ', array_keys($datos));

        // 3. Creamos los marcadores para PDO (:id, :idUsuario, etc.)
        $marcadores = ':' . implode(', :', array_keys($datos));

        // 4. Montamos la consulta
        $consulta = "INSERT INTO {$this->tabla} ($columnas) VALUES ($marcadores)";

        $sentencia = $this->db->prepare($consulta);

        // 5. Ejecutamos pasando el array de datos directamente
        return $sentencia->execute($datos);

    } catch (\PDOException $e) {

            Errores::log('Fallo en Informe Repository, en la consulta de guardar'. $e -> getmessage(), [
                'idUsuario' => $idUsuario, 
                'tabla' => $this-> tabla
            ]); 

            return false; 
            }
}

public function buscarPorId(int $id, ?int $idUsuario = null) {

$filas = parent::buscarPorId($id, $idUsuario); 


if (!$filas) {

return false; 

} 

return $this -> hidratar($filas);  



}


// Es el numero de informes que tiene en este caso dicho usuario. 
public function numeroInformes(int $idUsuario) {

    $consulta = "Select COUNT(i.id) as total
                From informe i 
                WHERE idUsuario = :idUsuario"; 

    $sentencia = $this -> db -> prepare($consulta); 
    $sentencia -> execute(['idUsuario' => $idUsuario]); 

    $resultado = $sentencia -> fetch(\PDO::FETCH_ASSOC);
    return $resultado['total'] ?? 0; // Devuelve el numero real.  

}

public function listarPorUsuario(int $idUsuario): array {

try {
     

$consulta = "Select i.*, j.nombre as nombre_jugador
            FROM informe i 
            join jugador j On i.idJugador = j.id
            WHERE i.idUsuario = :idUsuario
            ORDER BY i.fechaReg DESC"; 

   $sentencia = $this -> db -> prepare($consulta); 
   $sentencia -> execute(['idUsuario' => $idUsuario]); 

   $resultados = $sentencia -> fetchAll(\PDO::FETCH_ASSOC);
   if (!$resultados) return [];  
   // Funcion nativa no de onjeto. 
   return  array_map([$this, 'hidratar'], $resultados); // Aplica hidratar a cada fila 
   // No te olvides en este caso de poner los try catch

} catch (\PDOException $e) {
    // Usa tu sistem de logs que mencionae array_intersect
    return[]; 
}

}
}


?> 