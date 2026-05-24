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
    public static $SERVER_PORT_BD;
    public static $CHARSET; // <--- FALTABA ESTA
    public static $DB_NAME;
    public static $USER_BD;
    public static $PASSWORD_BD;

    public static function init() {
        self::$SERVER_NAME    = $_ENV['DB_HOST'] ?? 'localhost';
        self::$SERVER_PORT_BD = $_ENV['DB_PORT'] ?? '3306';
        self::$CHARSET        = 'utf8mb4'; // <--- AGREGA ESTA
        self::$DB_NAME        = $_ENV['DB_NAME'] ?? 'sfouter_db';
        self::$USER_BD        = $_ENV['DB_USER'] ?? 'root';
        self::$PASSWORD_BD    = $_ENV['DB_PASS'] ?? '';
    }
}
