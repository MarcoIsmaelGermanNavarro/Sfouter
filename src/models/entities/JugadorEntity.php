<?php
namespace Sfouter\models\entities;

use DateTime; 

class JugadorEntity {
     
    
    // Tner cuidade de insertarlo en el constructor como parametro perdeterminados. 
    // El constructor espaetra 5 datos y PDO le da 0. 
    // Cuando PDO, crea la clase, no pasa  argumentos al constructoe. Intenta crear el objeto "vacio"
    public function __construct(
        
    // Es la forma mejor private y poner = null, como si fuera en estcaso PHP8. 
        public ?int $id = null,   
        public string $nombre = '' ,  
        public string $apellidos = '' ,  
        public ?DateTime $fechaNac = null ,  
        public ?string $foto = '',   

)  
{}

public function getId(): ?int { return $this->id; }

    public function getNombre(): string { return $this->nombre; }

    public function getApellidos(): string { return $this->apellidos; }

    public function getFoto(): ?string { return $this->foto; }

    public function getNombreCompleto() {
        
    return "{$this-> nombre}   {$this -> apellidos}";
}

public function getFechaNac(): ?DateTime { return $this->fechaNac; }
}







?>