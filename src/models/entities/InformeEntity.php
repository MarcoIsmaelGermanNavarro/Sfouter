<?php
namespace Sfouter\models\entities;

use DateTime;
use InvalidArgumentException;

class InformeEntity {
    const POSICIONES_VALIDAS = ['Portero', 'Defensa', 'Medio', 'Delantero'];

    public function __construct(
        public ?int $id = null,
        public int $idUsuario,
        public int $idJugador,
        public ?string  $nombreJugador = null, 
        public int $idEquipo,
        public ?DateTime $fechaInf = null, // Objeto DateTime nativo
        public ?DateTime $fechaReg = null, // Objeto DateTime nativo
        public string $posicion = 'Portero',
        public int $velocidad = 0,
        public int $resistencia = 0,
        public int $dribbling = 0,
        public ?string $observaciones = null
    ) {
        // Validamos SIEMPRE, no importa si es nuevo o editado
        $this->validar();
    }

    private function validar(): void {
        if (!in_array($this->posicion, self::POSICIONES_VALIDAS)) {
            throw new InvalidArgumentException("Posición no válida: {$this->posicion}");
        }

        if ($this->velocidad < 1 || $this->velocidad > 10) {
            throw new InvalidArgumentException("La velocidad debe estar entre 1 y 10");
        }
        if ($this->resistencia < 1 || $this->resistencia > 10) {
            throw new InvalidArgumentException("La resistencia debe estar entre 1 y 10");
        }
        if ($this->dribbling < 1 || $this->dribbling > 10) {
            throw new InvalidArgumentException("El dribbling debe estar entre 1 y 10");
        }
    }

    // Getters limpios
    public function getId(): ?int { return $this->id; }
    public function getIdUsuario(): int { return $this->idUsuario; }
    public function getIdJugador(): int { return $this->idJugador; }
    public function getIdEquipo(): int { return $this->idEquipo; }
    public function getPosicion(): string { return $this->posicion; }
    public function getVelocidad(): int { return $this->velocidad; }
    public function getResistencia(): int { return $this->resistencia; }
    public function getDribbling(): int { return $this->dribbling; }
    public function getObservaciones(): ?string { return $this->observaciones; }
    public function getNombreJugador(): string {return $this->nombreJugador ?? 'Jugador Desconocido';  }

    // Al ser ya objetos DateTime, el retorno es directo y seguro
    public function getFechaInf(): ?DateTime { return $this->fechaInf; }
    public function getFechaReg(): ?DateTime { return $this->fechaReg; }

    public function toArray(): array {
    return [
        'idUsuario'     => $this->idUsuario,
        'idJugador'     => $this->idJugador,
        'idEquipo'      => $this->idEquipo,
        'fechaInf'      => $this->fechaInf ? $this->fechaInf->format('Y-m-d') : null,
        'posicion'      => $this->posicion,
        'velocidad'     => $this->velocidad,
        'resistencia'   => $this->resistencia,
        'dribbling'     => $this->dribbling,
        'observaciones' => $this->observaciones
    ];
}

public function getFechaFormateada(): string {
        // Asegúrate de que $this->fechaInf sea un objeto DateTime
        return ($this->fechaInf instanceof \DateTime) 
               ? $this->fechaInf->format('d/m/Y') 
               : 'Fecha inválida';
    }

    public function getNotaMedia(): float {
    // Sumamos y dividimos
    $media = ($this->velocidad + $this->dribbling + $this->resistencia) / 3;
    return round($media, 1); // Redondeamos a 1 decimal
}
}