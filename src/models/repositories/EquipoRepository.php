<?php

namespace Sfouter\src\models\repositories;

use Sfouter\src\utils\Errores; 
use Sfouter\src\models\entities\EquipoEntity; 
use Sfouter\src\models\repositories\BaseRepository; 
use PDO; 
use PDOException; 

 class EquipoRepository extends BaseRepository {


 public function __construct(?PDO $db = null) {
    // Enlazamos al padre con la tabla y su entidad correspondiente
 parent::__construct($db, "equipo",  EquipoEntity::class);  

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

    // En EquipoRepository.php
// En EquipoRepository.php
public function buscarPorId(int $id, int $idUsuario = null) {
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


?> 