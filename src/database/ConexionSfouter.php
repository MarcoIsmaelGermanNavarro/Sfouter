<?php
    namespace Sfouter\database; 
    use Sfouter\config\ConfigBD; 

	class ConexionSfouter {
		public static function conectar(){
			try{
				$conexion = new \PDO('mysql:host='.ConfigBD::$SERVER_NAME.';dbname='.ConfigBD::$DB_NAME.';port='.ConfigBD::$SERVER_PORT_BD.';charset='.ConfigBD::$CHARSET, ConfigBD::$USER_BD, ConfigBD::$PASSWORD_BD);
				$conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				return $conexion;
			}catch (\PDOException $e){
				
				return NULL;
			}
		}
	}
?> 