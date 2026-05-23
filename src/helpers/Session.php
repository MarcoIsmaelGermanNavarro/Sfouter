<?php

namespace Sfouter\src\helpers;

// La funcion de este archivo es la de en este caso, todas las confuiguaraciones, de las 
// Sesiones, en este caso centralizarlas. 
class Session { 

    
// ¿No se tendria que en este caso, poner como paramtros de las funciones, 
// las secciones, para que en este caso la busque, o en verada me estoy confuncdiondo, 
// porque una de las caracteristicas princiapales de las funciones es que son globales?

// Respuesta: No te preocupes que es global. Hay esta la gracia en no tener que escribir continuamente, Session
public static function  isUserAdminLogged():bool {

// Para acceder a una propiedad de un obnjeto o array en la sesion se usa flecha (->) o ['key'], si es Array
if (isset($_SESSION['user']) && $_SESSION['user']-> getRol() === "admin") {
    return true; 
} 

return false; 
     
}


// ¿Existe en este caso el usuario?
public static function  isUserLogged():bool {

return (isset($_SESSION['user'])); 

}

// Elimina una variable especifica de la sesion. 
public static function remove(string $nombre_variable): void {
        if ($isset($_SESSION[$nombre_variable])) {
            unset($_SESSION[$nombre_variable]); 
        }



}

// Pra hacer el LogOut
public static function destroy(): void {
    $_SESSION = []; // 1. Vaciamso el array. 

    if (session_id() != "") {
        session_destroy(); // 2. Rompemos el contendeor
    }

    }

}
?> 