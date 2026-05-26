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
    // BASE_PATH ya fue definida en index.php como /var/www/html/
    // Así que solo tenemos que concatenar la carpeta views
    return BASE_PATH . "views" . DIRECTORY_SEPARATOR;
}


    public static function getPhysicalPath(): string {
        
        return realpath(__DIR__ . '/../') . '/public/uploads/Fotos/'; 
    }

   public static function getRutaWeb(): string {
    // Usamos la misma lógica que en getBaseUrl()
    $prefix = ($_SERVER['HTTP_HOST'] === 'localhost') ? "/Sfouter/public" : "";
    return $prefix . "/uploads/Fotos/"; 
}


    public static function getBaseUrl() {
        // Detectamos si estamos en local (localhost)
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            return "/Sfouter/public/";
        }
        // Si no, asumimos producción (raíz directa)
        return "/";
    }
}
