<?php

namespace Sfouter\config; 

// Esta es la clase que centraliza, la conficguracion estatica de la clase, 
// es decir en este caso es la que mantiene,  si index, le mandda algio que no ha hecho el usuario,
// se lo manda directamente a Parametro, lo que lo hace en este caso, mas llevadero. 
class Parameters {

    public static $CONTROLLER_DEFAULT = "home"; 
    public static $ACTION_DEFAULT = "index"; 

    public static $BASE_URL = "http://localhost/Sfouter/";

    /**
     * Ruta física para el sistema de vistas
     */
    public static function viewsPath(): string {
        // Asegúrate de que BASE_PATH esté bien definida en tu index.php
        return BASE_PATH . "views" . DIRECTORY_SEPARATOR;
    }

    /**
     * REFACTORIZADO: Ruta física del servidor para SUBIR archivos (move_uploaded_file)
     * Usamos una ruta inequívoca basada en la raíz del servidor de XAMPP para evitar fallos de dirname()
     */
    public static function getPhysicalPath(): string {
        return $_SERVER['DOCUMENT_ROOT'] . "/Sfouter/public/uploads/Fotos/"; 
    }

    /**
     * Ruta URL para pintar la imagen en las etiquetas HTML <img src="...">
     */
    public static function getRutaWeb(): string {
        return self::$BASE_URL . "public/uploads/Fotos/"; 
    }

    public static function getBaseUrl(): string {
        return self::$BASE_URL; 
    }
}



?> 