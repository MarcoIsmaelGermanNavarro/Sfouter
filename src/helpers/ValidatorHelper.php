<?php

namespace Sfouter\src\helpers;
// No necesita instanciarse, la invocas directamente en los controaldsores enates de mandar
// lod daros al Repositorio o a la entidad

class ValidatorHelper {
/*Limpaia texto de esapacios y eqtieuetas HTML, peligrosas (Previene Xss)*/

public static function sanear(String $dato) : String {
    return htmlspecialchars(strip_tags(trim($dato))); 
}

/*Comprueba si un campo es obligatorio*/ 
public static function estaVacio(?string $dato): bool {

return $dato == null || trim($dato) === ''; 

}
/**
     * Valida que un texto tenga una longitud mínima y máxima
     * Útil para nombres de jugadores, equipos o posiciones.
     */
    public static function longitud(string $dato, int $min, int $max): bool {
        $longitud = mb_strlen(trim($dato)); // Para contar todos los caracteres. 
        return $longitud >= $min && $longitud <= $max;
    }

    /**
     * Valida que el texto SOLO contenga letras y espacios (Sin números ni símbolos)
     * Perfecto para nombres y apellidos de jugadores.
     */
    public static function soloLetras(string $dato): bool {
        // Esta expresión regular permite letras (incluyendo ñ, á, é, etc.) y espacios
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ  ]+$/u', trim($dato)) === 1;
    }


/**
     * Valida que un número esté dentro de un rango inclusivo (ej: Notas 1 a 10)
     */
    public static function enRango(int $valor, int $min, int $max): bool {
        return $valor >= $min && $valor <= $max;
    }

public static function fechaValida(string $fecha): bool {
    try {
        // 1. Creamos el objeto con la fecha del input
        $fechaInput = new \DateTime($fecha);
        
        // 2. Creamos el objeto con la fecha de hoy a las 23:59:59
        // Esto asegura que cualquier hora del día de hoy sea válida
        $hoy = new \DateTime();
        $hoy->setTime(23, 59, 59); 

        // 3. Comparamos los objetos directamente
        return $fechaInput <= $hoy;

    } catch (\Exception $e) {
        // Si el string no era una fecha válida (ej: "texto-random"), saltará la excepción
        return false;
    }
}

/**
 * Valida el tamaño y formato de la foto subida.
 * Retorna un string con el error o null si es válida.
 */
public static function validarFoto(array $archivo, int $maxMegas = 2): ?string {
    // 1. Comprobar si se ha subido un archivo
    if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return "La foto del jugador es obligatoria.";
    }

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return "Hubo un error al subir el archivo al servidor.";
    }

    // 2. Medidor de tamaño (Convertido a bytes)
    $maxBytes = $maxMegas * 1024 * 1024;
    if ($archivo['size'] > $maxBytes) {
        return "La imagen es demasiado grande. El límite son {$maxMegas}MB.";
    }

    // 3. Comprobar la extensión
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($extension, $extensionesPermitidas)) {
        return "Formato de imagen no válido. Usa JPG, PNG o WEBP.";
    }

    return null; // Todo en orden
}

/**
 * Genera un nombre de archivo limpio y seguro para evitar conflictos y caracteres raros
 */
public static function generarNombreSeguro(string $nombreOriginal): string {
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    // Eliminamos caracteres extraños del nombre base para evitar romper URLs
    $nombreLimpio = preg_replace("/[^a-zA-Z0-9]/", "", pathinfo($nombreOriginal, PATHINFO_FILENAME));
    return time() . "_" . $nombreLimpio . "." . $extension;
}

/**
 * Valida que el email tenga un formato electrónico correcto
 */
public static function emailValido(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fuerza a que la contraseña sea segura (Mínimo 8 caracteres, al menos una letra y un número)
 * A los profesores les encanta ver que exiges contraseñas robustas.
 */
public static function contrasenaSegura(string $password): bool {
    // Mínimo 8 caracteres, al menos una letra y un número
    return (strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password));
}

/**
 * Verifica si el email ya existe en la base de datos (Evita duplicados)
 */
public static function emailRepetido(string $email, $usuarioRepository, ?int $idActual = null): bool {
    return $usuarioRepository->existeEmail($email, $idActual);
}


}