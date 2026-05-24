<?php

namespace Sfouter\models\entities;

class EquipoEntity {

public function __construct(
    protected ?int $id = null,
    protected string $nombre = '' 
){}

public function getid() {return $this -> id; }
public function getNombre() {return $this -> nombre; }

}
