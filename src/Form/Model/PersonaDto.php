<?php

namespace App\Form\Model;

use App\Entity\Persona;

class PersonaDto
{
    public $nombre;
    public $base64Image;
    public $nacionalidades;

    public function __construct()
    {
        $this->nacionalidades = [];
    }

    public static function createFromPersona(Persona $persona): self
    {
        $dto = new self();
        $dto->nombre = $persona->getNombre();
        return $dto;
    }
}
