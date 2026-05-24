<?php
namespace Sfouter\database; 
use Sfouter\config\ConfigBD; 
use PDO;
use Exception;

class ConexionSfouter {
    public static function conectar(): PDO {
        $dsn = 'mysql:host='.ConfigBD::$SERVER_NAME.';dbname='.ConfigBD::$DB_NAME.';port='.ConfigBD::$SERVER_PORT_BD.';charset='.ConfigBD::$CHARSET;
        
        // Esto lanzará una excepción real si falla, en lugar de devolver NULL
        $conexion = new PDO($dsn, ConfigBD::$USER_BD, ConfigBD::$PASSWORD_BD);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    }
}
?> 