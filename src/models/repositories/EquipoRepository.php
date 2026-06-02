<?php

namespace Sfouter\models\repositories;

use Sfouter\utils\Errores; 
use Sfouter\models\entities\EquipoEntity; 
use Sfouter\models\repositories\BaseRepository; 
use PDO; 
use PDOException; 

 class EquipoRepository extends BaseRepository {


 public function __construct(?PDO $db = null) {
    // Enlazamos al padre con la tabla y su entidad correspondiente
 parent::__construct("equipo", EquipoEntity::class, $db);  

 }
/**
     * Convierte un array de la base de datos en un objeto EquipoEntity
*/
private function hidratar(array $d): EquipoEntity {
    // 🌟 PROTECCIÓN: Si los datos no vienen, asignamos valores por defecto seguros
    return new EquipoEntity(
        isset($d['id']) ? (int)$d['id'] : null,
        isset($d['nombre']) ? (string)$d['nombre'] : 'Sin nombre'
    );
}
 

public function obtenerTodosParaSelect(): array {
    try {
        $consulta = "SELECT id, nombre FROM equipo"; // Asegúrate que estos nombres sean los REALES en tu BD
        $stmt = $this->db->query($consulta);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 🌟 SIEMPRE verifica si hay filas antes de mapear
        if (!$filas) {
            return [];
        }

        return array_map([$this, 'hidratar'], $filas);

    } catch (\PDOException $e) {
        Errores::log("Error en EquipoRepository: " . $e->getMessage());
        return [];
    }
}
 
    public function existeNombre(string $nombre): bool { //  CORREGIDO: Ya no pedimos el idUsuario
    // Tu SQL dependerá de si los equipos son globales o únicos por scout/usuario.
        // Asumiendo que un usuario no puede repetir nombres de equipo:
    try {
        // CORREGIDO: Consulta limpia sin filtros de usuario
        $sql = "SELECT COUNT(*) as total FROM equipo WHERE nombre = :nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre
        ]);
        
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)($resultado['total'] ?? 0) > 0;
    } catch (\PDOException $e) {
        Errores::log("Error en existeNombre de EquipoRepository: " . $e->getMessage());
        return false;
        }
    }

    // Exactamente la misma logica que en JugadorRepository, pero aqui el hijo es INFORME
    // con la columna idEquipo en lugar de idJugador.
    // El motivo del orden es el mismo: INFORME depende de EQUIPO por clave ajena,
    // asi que si intentasemos borrar el equipo primero la BD nos rechazaria la operacion.
    // Con la transaccion garantizamos que si el segundo DELETE falla por cualquier razon,
    // los informes que ya borramos se restauran automaticamente con el rollBack,
    // y la base de datos no queda en un estado inconsistente.
    public function eliminarConInformes(int $id): int {
        try {
            $this->db->beginTransaction();

            // 1. Borramos primero los informes asociados al equipo (los hijos)
            $stmtInformes = $this->db->prepare("DELETE FROM informe WHERE idEquipo = :id");
            $stmtInformes->execute(['id' => $id]);
            $informesBorrados = $stmtInformes->rowCount();

            // 2. Ahora borramos el equipo sin problemas de FK (el padre)
            $stmtEquipo = $this->db->prepare("DELETE FROM equipo WHERE id = :id");
            $stmtEquipo->execute(['id' => $id]);

            // Todo correcto: confirmamos los cambios de manera atomica
            $this->db->commit();
            return $informesBorrados;

        } catch (\PDOException $e) {
            // Algo fallo: revertimos todo, la BD queda intacta
            $this->db->rollBack();
            Errores::log("Error en EquipoRepository::eliminarConInformes: " . $e->getMessage());
            return -1;
        }
    }

    public function buscarPorId(int $id, ?int $idUsuario = null) {
    // 1. Llamamos al padre para obtener el array crudo
    $fila = parent::buscarPorId($id, $idUsuario);

    // 2. SI NO HAY FILA, DEVOLVEMOS NULL INMEDIATAMENTE
    if (!$fila) {
        return null;
    }

    // 3. Solo si hay datos, hidratamos
    return $this->hidratar($fila);
}
 }
 