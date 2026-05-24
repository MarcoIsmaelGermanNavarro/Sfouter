<?php

namespace Sfouter\models\repositories;

// Al usar en este caso Use, ten cuidado, porque puedes tener problemas a la hora de Conectar
use Sfouter\database\ConexionSfouter; // <-- Añade esto arriba. 
use Sfouter\utils\Errores;   
use PDO; 
use PDOException; 

abstract class BaseRepository {

    protected PDO $db; 
    protected string $entidad; 
    protected string  $tabla; 
    
    // Si la conexion es nula se ejecuta lo que hay abajo
    public function __construct(String $tabla, string $entidad, ?PDO $db = null) {
    $this -> tabla = $tabla;
    $this -> entidad = $entidad; 
    // Si no me pasan conexión, la pido a mi clase ConexionSfouter
    $this->db = $db ?? ConexionSfouter::conectar();
    }

   /**
     * Este método es el secreto. Por defecto no hace nada,
     * pero los hijos lo usarán para convertir el array en objeto.
     */
    protected function mapear(array $datos) {
        return $datos; 
    }


public function cargarTodo(?int $userId = null): array {
    try { 
        // Si viene ID filtramos por la columna idUsuario de esa tabla, si no, todo recto
        if ($userId !== null) {
            $consulta = "SELECT * FROM {$this->tabla} WHERE idUsuario = :userId";
            $parametros = ['userId' => $userId];
        } else {
            $consulta = "SELECT * FROM {$this->tabla}";
            $parametros = [];
        }

        $sentencia = $this->db->prepare($consulta); 
        $sentencia->execute($parametros); 

        return $sentencia->fetchAll(\PDO::FETCH_ASSOC); 
    } catch (\PDOException $e) { 
        Errores::log('Fallo en BaseRepository::cargarTodo: ' . $e->getMessage()); 
        return []; 
    }
}

// Si es un ojeador normal, pongo los dos parametso, si no, pongo solo uno, y 
// de igual manera en este caso se eliminarian. 
public function EliminarFila(int $id, ?int $idUsuario = null) {
    try {
        // Iniciamos la consulta base
        $consulta = "DELETE FROM {$this->tabla} WHERE id = :id";
        $parametros = ['id' => $id];

        // SI se proporciona un idUsuario, añadimos la restricción de seguridad
        if ($idUsuario !== null) {
            $consulta .= " AND idUsuario = :idU";
            $parametros['idU'] = $idUsuario;
        }

        $sentencia = $this->db->prepare($consulta);
        $sentencia->execute($parametros);

        return $sentencia->rowCount() > 0;

    } catch (\PDOException $e) {
        Errores::log("Error en EliminarFila: " . $e->getMessage());
        return false;
    }
}


    public function insertar(array $datos, ?int $idUsuario = null) {

        try {

        $columnas = implode(", ", array_keys($datos)); 
        $marcadores = ":" . implode(", :", array_keys($datos)); 

        $consulta = "INSERT INTO {$this->tabla} ($columnas) VALUES ($marcadores)"; 
        $sentencia = $this -> db -> prepare($consulta); 

        // Si uso marcadores como :nomnre, tienes que pasarle os datos al execute, para uqe separa que valor poner
        // en cada marcador. Debe ser $sentencia -> execute($datos). 

        return $sentencia -> execute($datos); 

        } catch (\PDOException $e) {
            Errores::log('Fallo en la baseRepository, en la consulta de insertar. ' . $e -> getmessage(), [
            'user_id' => $idUsuario
           ]); 

           return false; 
        }
    }
 // En este caso, lo que ahce es recoger las filas que en este caso coincidan con el id del jugador. 
    public function buscarPorId(int $id, int $idUsuario = null) {

    try {

    $consulta = "Select * from {$this -> tabla} WHERE id = :id"; 
    $sentencia = $this -> db -> prepare($consulta); 
    $sentencia -> execute(['id' => $id]);
    
     $sentencia -> setFetchMode(PDO::FETCH_ASSOC); 

    $resultado = $sentencia -> fetch(); 

    return $resultado ?: null; 
    
    } catch (\PDOException $e) {
        
    // En este caso ciuando hacemos errores.log, es en estcaso, usar, el valor errores, que en estec aso habiasmo especificado en el use, 
    // y lo insertamos en el, accediendo en este caso a su metodo estatico. 
        Errores::log("Ha habido un fallo en la conulta, buscar Por Id, en base Repository". $e -> getmessage(), [
            'user_id' => $idUsuario, 
            'id_buscado' => $id
        ]); 

        return null; // H habido un error y no hemos encontrado nada  

    }

    }

     // Actaulizando la Info de un informe es decir de la tabla
    public function actualizarInfo($id, $arrayDatos, $idUsuario = null) {
         
        try {

        $pares = []; 
        // implode en este caso convierte de array a Strings
        /* Esto podria sobrar, ya que en este caso losadatos que necesitamsos es por apres
        $columnas = implode(",", array_keys($arrayDatos)); 
        $marcadores = ":" . implode(", :", array_keys($arrayDatos));
        */
        foreach (array_keys($arrayDatos) as $columna) {

            $pares[] = "{$columna} = :{$columna}";  

            # code...
        }


        $setSql = implode(",", $pares); 
        
        $consulta = "UPDATE {$this -> tabla} 
                        SET   $setSql
                        WHERE id = :id_seguro"; 


        $sentencia = $this -> db -> prepare($consulta); 

        $arrayDatos['id_seguro'] = $id; 
        return $sentencia -> execute($arrayDatos); 
             
        } catch (\PDOException $e) { 

        Errores::log("Fallo en Informe Repository, en la funcion de actualizarInfo " . $e -> getmessage(), [
            'idUsuario' => $idUsuario, 
            'tabla' => $this -> tabla
            ]); 

            return false; 
        }
    }   
/*
       // Esto es una insercion en este caso de un informe
    public function guardar($arrayDatos, $idUsuario = null) { 

            try {
            $columnas = implode(",", array_keys($arrayDatos)); 
            $marcadores = ":" . implode(", :", array_keys($arrayDatos)); 

            $consulta = "INSERT INTO  {$this -> tabla} ($columnas) 
                        VALUES ($marcadores)"; 
                        
            $sentencia = $this -> db -> prepare($consulta); 
            return $sentencia -> execute($arrayDatos); 
        
            } catch (\PDOException $e) {

            Errores::log('Fallo en Informe Repository, en la consulta de guardar'. $e -> getmessage(), [
                'idUsuario' => $idUsuario, 
                'tabla' => $this-> tabla
            ]); 

            return false; 
            }
    }
*/
}


?> 