<?php

namespace Sfouter\src\models\entities;

use DateTime;

class UsuarioEntity {
    
    public function __construct(
        private int $id,
        private string $nombre = ' ',
        private string $apellidos = ' ',
        private string $rol = 'user',
        private string $email = ' ',
        private string $password = ' ',
        private ?DateTime $fechaReg = null // Usamos la clase nativa de PHP, Lo hacemos null
    ) {}

    /*
    // GETTERS (Solo lectura)
    public function getId(): ?int { return $this->id; }
    public function getNombreCompleto(): string { 
        return "{$this->nombre} {$this->apellidos}"; 
    }
    public function getRol(): string { return $this->rol; }

    // VALIDACIÓN (Lógica de negocio en el Setter)
    public function setRol(string $rol): void {
        $rolesValidos = ['admin', 'user'];
        if (!in_array($rol, $rolesValidos)) {
            throw new \InvalidArgumentException("El rol '$rol' no es válido.");
        }
        $this->rol = $rol;
    }
    
    public function getPassword(): string {
    
    return $this-> password; 
      
    }
    // El password nunca se devuelve en texto plano por seguridad, 
    // pero eso lo veremos en el Repository.
    */
public function getId(): ?int { return $this->id; }
    public function getNombreCompleto(): string { return "{$this->nombre} {$this->apellidos}"; }
    public function getNombre(): string {return $this -> nombre; }
    public function getApellidos(): string {return $this -> apellidos; } 
    public function getRol(): string { return $this->rol; }
    public function getEmail(): string { return $this->email; }
    
    // IMPORTANTE: PDO inyectará el Hash aquí.
    public function getPassword(): string { return $this->password; }

     public function getFechaReg(): \DateTime {
    // Si ya es un objeto, devuélvelo directamente
    if ($this->fechaReg instanceof \DateTime) {
        return $this->fechaReg;
    }
}
}