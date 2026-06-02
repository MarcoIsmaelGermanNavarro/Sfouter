<?php

namespace Sfouter\models\repositories; 

use Sfouter\models\repositories\BaseRepository;
use Sfouter\models\entities\JugadorEntity;
use Sfouter\utils\Errores; 
use PDO; 
use PDOException;  

class JugadorRepository extends BaseRepository {

    // Si es null, mi padre ya se encarga de esto. 
    public function __construct(?PDO $db = null) {
        // Llamamos al constructor del padre pasando:
        // 1. La conexión $db
        // 2. El nombre de la tabla 'jugadores'
        // 3. El nombre de la clase Entidad (Namespace completo)
        parent::__construct("jugador", JugadorEntity::class, $db);
    }

    private function hidratar(array $d): JugadorEntity {
        return new JugadorEntity(
            (int)$d['id'],
            $d['nombre'],
            $d['apellidos'],
            $d['fechaNac'] ? new \DateTime($d['fechaNac']) : null,
            $d['foto']
        );
    }

    /**
     * Ejemplo de método específico que NO está en el Base.
     * Buscar jugadores por posición (ej: 'Delantero')
     */

// Buscar jugadores por posición uniendo tablas de forma correcta
    public function buscarPorPosicion(string $posicion): array {
        try {
            //  CORREGIDO: i.idJugador en lugar de jugador_id
            $consulta = "SELECT j.* FROM {$this->tabla} j 
                         INNER JOIN informe i ON j.id = i.idJugador 
                         WHERE i.posicion = :pos";
            
            $sentencia = $this->db->prepare($consulta);
            $sentencia->execute(['pos' => $posicion]);
            
            $filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hidratar'], $filas);
        } catch (\PDOException $e) {
            Errores::log("Error en Join de posición: " . $e->getMessage());
            return [];
        }
    }

// Buscar un único jugador de manera segura
    public function buscarPorId(int $id, ?int $idUsuario = null): ?JugadorEntity {
        try { 
            $filas = parent::buscarPorId($id, $idUsuario); 

            // 🌟 CORREGIDO: Si no hay datos, devolvemos null respetando el tipado
            if (!$filas) {
                return null; 
            }
            
            return $this->hidratar($filas); 
        } catch(\PDOException $e) {
            Errores::log('Fallo en JugadorRepository::buscarPorId: ' . $e->getMessage(), [
                'usuario_id' => $idUsuario
            ]);
            return null; // CORREGIDO: Jamás devolvemos un array aquí
        }
    }


public function cargarTodo(?int $userId = null): array {
    // 1. Inicializamos el contenedor de filas arriba para evitar variables fantasma
    $filas = []; 

    if ($userId === null) {        
        // OPCIÓN A (Admin): Cargar TODOS los jugadores del sistema usando al padre
        //  Respetamos la mayúscula del método del padre: CargarTodo
        $filas = parent::cargarTodo($userId); 
    } else {
        // OPCIÓN B (Scout): Cargar solo jugadores que tienen informes redactados por este usuario
        try {
            $consulta = "SELECT DISTINCT j.* FROM jugador j 
                         INNER JOIN informe i ON j.id = i.idJugador
                         WHERE i.idUsuario = :userid"; 
                         
            $sentencia = $this->db->prepare($consulta); 
            $sentencia->execute(['userid' => $userId]); 
            
            $filas = $sentencia->fetchAll(\PDO::FETCH_ASSOC);

        } catch(\PDOException $e) {
            Errores::log("Error en JugadorRepository::CargarTodo al filtrar por usuario: " . $e->getMessage()); 
            return []; 
        }
    }

    // 2. Si la base de datos no devolvió nada, devolvemos array vacío de forma segura
    if (empty($filas)) {
        return [];
    }

    // 3. Transformamos las filas crudas en objetos JugadorEntity
    return array_map([$this, 'hidratar'], $filas); 
}

    // Tenemos en este caso la consulta, de cuantos jugadores, tiene ojoedaor un usaurio
    // No ponemos null, porque no puede ser en este caso nulo. 
    // Este metodo elimina un jugador junto con todos sus informes de manera segura.
    // Lo hacemos con una transaccion, que es como un contrato con la base de datos:
    // o se hace todo, o no se hace nada. Asi evitamos quedarnos a medias, es decir,
    // con los informes borrados pero el jugador todavia en pie, o al reves.

    // Por que borramos primero los informes y luego el jugador?
    // Porque en la base de datos, INFORME tiene una clave ajena (idJugador) que apunta
    // a JUGADOR. Si intentasemos borrar el jugador primero, la base de datos nos daria
    // un error de integridad referencial: "no puedes borrar al padre si todavia tiene hijos".
    // Entonces el orden correcto es siempre: borrar los hijos primero, luego el padre.

    // beginTransaction() le dice a la base de datos: "ojo, voy a hacer varias consultas,
    // no ejecutes ninguna todavia, esperame".
    // commit() le dice: "todo ha ido bien, confirma los cambios".
    // rollBack() le dice: "algo ha fallado, deshaz todo como si no hubiera pasado nada".

    // rowCount() nos devuelve cuantas filas ha afectado el DELETE de informes,
    // asi podemos decirle al usaurio cuantos informes se han borrado junto al jugador.
    // Si devuelve -1 es que algo salio mal y el rollBack ya ha dejado la BD intacta.
    public function eliminarConInformes(int $id): int {
        try {
            $this->db->beginTransaction();

            // 1. Primero borramos los informes que pertenecen a este jugador (los hijos)
            $stmtInformes = $this->db->prepare("DELETE FROM informe WHERE idJugador = :id");
            $stmtInformes->execute(['id' => $id]);
            $informesBorrados = $stmtInformes->rowCount();

            // 2. Ahora ya podemos borrar el jugador sin que la FK se queje (el padre)
            $stmtJugador = $this->db->prepare("DELETE FROM jugador WHERE id = :id");
            $stmtJugador->execute(['id' => $id]);

            // Si llegamos aqui, todo ha ido bien: confirmamos los dos DELETEs a la vez
            $this->db->commit();
            return $informesBorrados;

        } catch (\PDOException $e) {
            // Algo ha fallado: deshacemos todo, la BD queda exactamente como estaba
            $this->db->rollBack();
            Errores::log("Error en JugadorRepository::eliminarConInformes: " . $e->getMessage());
            return -1;
        }
    }

    public function numeroJugadores(int $idUsuario) {

    
        // Lo que queremos en este caso, es el numero de jugadores, ojeador, por un ojeador, es decir, en este caso X. 
        
        // No hace falta ahcer un left, porque si no teien un informe, no hace falta ene stec aso contanilizarlo

        try {
       // Contamos jugadores ÚNICOS ojeados por este usuario, es decir en este caso, 
       // Todos los jugadores que no sean iguales, que corresponden con el idUsuario. 
        $consulta = "SELECT COUNT(DISTINCT idJugador) as total 
                    FROM informe 
                    WHERE idUsuario = :idUsuario";

            $sentencia = $this -> db -> prepare($consulta); 
            $sentencia -> execute(['idUsuario' => $idUsuario]); 

            // En este caso se ejecuta un arrayAsociativo
            $fila = $sentencia -> fetch(\PDO::FETCH_ASSOC); 

            return $fila['total'] ?? 0; 

        } catch (\PDOException $e) {

            Errores::log('Fallo en la consulta de Jugador Repository' . $e -> getmessage(), [
                'user_id' => $idUsuario
            ]); 

            return 0; 

        }
    }  


}