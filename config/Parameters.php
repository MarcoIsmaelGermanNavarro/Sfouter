<?php

namespace Sfouter\config; 

// Esta es la clase que centraliza, la conficguracion estatica de la clase, 
// es decir en este caso es la que mantiene,  si index, le mandda algio que no ha hecho el usuario,
// se lo manda directamente a Parametro, lo que lo hace en este caso, mas llevadero. 
class Parameters {
    public static $CONTROLLER_DEFAULT = "home"; 
    public static $ACTION_DEFAULT = "index"; 

    // Ajustamos la URL base para que sea relativa
    public static $BASE_URL = "/"; 

    public static function viewsPath(): string {
        // Obtenemos la ruta absoluta de la raíz del proyecto
        // Si index está en public, la raíz es /var/www/html/
        return realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
    }

    public static function getPhysicalPath(): string {
        // En lugar de hardcodear "/Sfouter/", usamos la raíz del servidor
        return realpath(__DIR__ . '/../') . '/public/uploads/Fotos/'; 
    }

    public static function getRutaWeb(): string {
        return "/uploads/Fotos/"; 
    }
}



?> 