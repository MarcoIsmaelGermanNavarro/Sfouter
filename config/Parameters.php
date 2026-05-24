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
    // Si este archivo está en src/config/, para llegar a views subimos 2 niveles
    // y luego entramos en la carpeta views.
    return realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
}
    public static function getPhysicalPath(): string {
        
        return realpath(__DIR__ . '/../') . '/public/uploads/Fotos/'; 
    }

    public static function getRutaWeb(): string {
        return "/uploads/Fotos/"; 
    }
}



?> 