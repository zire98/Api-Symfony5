<?php

namespace App\Form\Model;

use App\Entity\Nacionalidad;

class NacionalidadDto
{
    public $id;
    public $nombre;

    public static function createFromNacionalidad(Nacionalidad $nacionalidad): self
    {
        $dto = new self();
        $dto->id = $nacionalidad->getId();
        $dto->nombre = $nacionalidad->getNombre();
        return $dto;
    }
}
