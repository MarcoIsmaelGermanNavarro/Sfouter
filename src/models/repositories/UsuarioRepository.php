<?php
// ¿En este caso el namespace es repositories, poqrque en este cas
// es la carpeta que contiene en estc aso el reopositories, esd ecir que si hubietra otra carpeta, o archivo von este nombre, 
// se podraian usar los dos, mediante un use, en este caso ya que estan, 
// en diferentes namespaces?
namespace Sfouter\models\repositories;

use Sfouter\models\repositories\BaseRepository;
use Sfouter\models\entities\UsuarioEntity;
use Sfouter\utils\Errores; 
use PDO; 
use PDOException;  

class UsuarioRepository extends BaseRepository {


    public function __construct(
     
     ?PDO $db = null
    ) {
        // ¿No hace faltya meterla en el constructor porque para que, no 
        // si sabemos que va a ser siempre al usurio?
        parent::__construct("usuario", UsuarioEntity::class, $db);
    }

    private function hidratar(array $d): UsuarioEntity {
            return new UsuarioEntity (
                (int)$d['id'], 
                $d['nombre'], 
                $d['apellidos'], 
                $d['rol'], 
                $d['email'], 
                $d['password'],
                new \DateTime($d['fechaReg'])   
            ); 


    }

    public function Login(string $email, string $password): ?UsuarioEntity {
    try {
        // Buscamos los datos brutos (Array)
        $sql = "SELECT * FROM {$this->tabla} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$datos) return null; // No existe el email

        // Convertimos el array en un "Ciudadano" de tu código (Objeto)
        // Usamos el método hidratar que centraliza la creación y las fechas
        $usuario = $this->hidratar($datos);

        // Ahora que el usuario es un objeto real, verificamos su "llave"
        if (password_verify($password, $usuario->getPassword())) {
            return $usuario; // Login exitoso
        }

        return null; // Contraseña incorrecta

    } catch (\PDOException $e) {
        Errores::log("Fallo crítico en Login: " . $e->getMessage());
        return null;
    }
}



    

    public function actualizarRol($id, $NuevoRol, ?int $idUsuario = null) { 

    try {

        $consulta = "UPDATE {$this -> tabla}
                     SET rol = :NuevoRol
                     Where id = :id"; 

         $sentencia = $this -> db ->  prepare($consulta); 
        $sentencia -> execute(['id' => $id, 'NuevoRol' => $NuevoRol]); 
        return $sentencia; 

    } catch (\PDOException $e) {

    Errores::log("Ha ocurrido un error, eun UsuarioRepository, en la consulta de actualizar rol" . $e -> getmessage().  [

        // Lo que estoy haciendo en este ejercicio es lo de simpelemnte mostra en panatlla
        // El id del Usuario
        'idUsuario' =>  $idUsuario,
        'id' => $id
        
    ]); 

    return false; 
    }
}

    // En estre caso, traemos como parametro, los registro de los datos, que en este caso 
    // vamos a insertar. 
    public function RegistrarUsuario($RegistroDatos) {

          try {

          $datos = implode(",", array_keys($RegistroDatos)); 
          $marcadores = ":" . implode(", :", array_keys($RegistroDatos)); 


          $consulta = "INSERT INTO {$this -> tabla}  ($datos)
                        VALUES ($marcadores)"; 

            $sentencia = $this -> db -> prepare($consulta); 
            // Devolvemos true si se inserto correctamente. 
            return $sentencia -> execute($RegistroDatos); 

          } catch (\PDOException $e) {

            Errores::log("Ahi un error, en usuario Repositories, en la consulta de Registra Usuaior" . $e -> getmessage()); 

            // Si hay una excepcion devolvemos false para que el controlador los sepa. 
            return false; 

          }
    }

    // Esta es la logia de si existe un emial, es decir a la hora, del registrar un usuario, es importante, 
    // comprobar, que cuando vayamos en este caso a iniciar sesion, saber que no nos pueden colar, dos, 
    // email que sean lo mimso
public function ExisteEmail(string $email, ?int $idActual = null): bool {
    try {
        // Consulta base: ¿Alguien tiene este email?
        $consulta = "SELECT COUNT(*) FROM {$this->tabla} WHERE email = :email";
        $parametros = ['email' => $email];

        // SI se proporciona un idActual, excluimos a ese usuario de la búsqueda
        if ($idActual !== null) {
            $consulta .= " AND id != :idActual";
            $parametros['idActual'] = $idActual;
        }

        $sentencia = $this->db->prepare($consulta);
        $sentencia->execute($parametros);

        // Si el conteo es mayor que 0, significa que el email está ocupado por OTRO
        return $sentencia->fetchColumn() > 0;

    } catch (\PDOException $e) {
        Errores::log("Error en existeEmail: " . $e->getMessage());
        
        throw new \Exception("Error de conexion con la base de datos."); 
    }
}      
         
     
public function getUsuarioByEmail(string $email): ?UsuarioEntity {
    try {
        $sql = "SELECT * FROM {$this-> tabla} WHERE email = :email";
        $sentencia = $this->db->prepare($sql);
        $sentencia->bindValue(':email', $email);
        $sentencia->execute();

        // Traemos los datos como un simple array asociativo
        $datos = $sentencia->fetch(\PDO::FETCH_ASSOC);

        return $datos ? $this -> hidratar($datos) : null; 

    } catch (\PDOException $e) {
        Errores::log("Error en Repository: " . $e->getMessage());
        return null;
    }

}

// Se actualiza en este caso el usuario, con la entidad de este incluida
public function actualizarUsuario(UsuarioEntity $usuario): bool {
    $sql = "UPDATE {$this->tabla} SET 
            nombre = :nombre, 
            apellidos = :apellidos, 
            email = :email 
            WHERE id = :id";
    
    $stmt = $this->db->prepare($sql);
    
    return $stmt->execute([
        ':nombre'    => $usuario->getNombre(),
        ':apellidos' => $usuario->getApellidos(),
        ':email'     => $usuario->getEmail(),
        ':id'        => $usuario->getId()
    ]);
}

public function buscarPorEmail(string $email): ?UsuarioEntity {
    try {
        $consulta = "SELECT * FROM {$this->tabla} WHERE email = :email LIMIT 1";
        $sentencia = $this->db->prepare($consulta);
        $sentencia->execute(['email' => $email]);

        $fila = $sentencia->fetch(\PDO::FETCH_ASSOC);

        // Hidratamos la entidad para que el controlador tenga un objeto con cara y ojos
        return $fila ? $this -> hidratar($fila) : null; 

    } catch (\PDOException $e) {
        Errores::log("Error en buscarPorEmail: " . $e->getMessage());
        return null;
    }
}

public function cargarTodo(?int $userId = null): array { 

    $filas = parent::cargarTodo($userId); 

    //  CORREGIDO: Si no hay usuarios, devolvemos un array vacío, NUNCA false
    if (!$filas) {
        return []; 
    }

    return array_map([$this, 'hidratar'], $filas); 
}
}