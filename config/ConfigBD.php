<?php
namespace Sfouter\config;

/*
class ConfigBD{
    public static $SERVER_NAME = "localhost";
    public static $SERVER_PORT_BD = "3306";
    public static $CHARSET = "utf8mb4";
    public static $DB_NAME = "sfouter_db";
    public static $USER_BD = "root";  // Trabajamos con Root para hacer pruebas      
    public static $PASSWORD_BD = "";        
}*/

class ConfigBD {
    public static $SERVER_NAME;
    public static $DB_NAME;
    public static $USER_BD;
    public static $PASSWORD_BD;

    public static function init() {
        // Usamos el operador ?? para que, si no existe la clave, use un valor por defecto
        self::$SERVER_NAME = $_ENV['DB_HOST'] ?? 'localhost';
        self::$DB_NAME     = $_ENV['DB_NAME'] ?? 'sfouter_db';
        self::$USER_BD     = $_ENV['DB_USER'] ?? 'root';
        self::$PASSWORD_BD = $_ENV['DB_PASS'] ?? '';
    }
}