<?php

namespace App\Service;

use App\Entity\Nacionalidad;
use App\Repository\NacionalidadRepository;
use Doctrine\ORM\EntityManagerInterface;

class NacionalidadManager
{
    private $em;
    private $nacionalidadRepository;

    public function __construct(EntityManagerInterface $em, NacionalidadRepository $nacionalidadRepository)
    {
        $this->em = $em;
        $this->nacionalidadRepository = $nacionalidadRepository;
    }

    public function find(int $id): ?Nacionalidad
    {
        return $this->nacionalidadRepository->find($id);
    }

    public function getRepository(): NacionalidadRepository
    {
        return $this->nacionalidadRepository;
    }

    public function create(): Nacionalidad
    {
        $nacionalidad = new Nacionalidad();
        return $nacionalidad;
    }

    public function persist(Nacionalidad $nacionalidad): Nacionalidad
    {
        $this->em->persist($nacionalidad);
        return $nacionalidad;
    }

    public function save(Nacionalidad $nacionalidad): Nacionalidad
    {
        $this->em->persist($nacionalidad);
        $this->em->flush();
        return $nacionalidad;
    }

    public function reload(Nacionalidad $nacionalidad): Nacionalidad
    {
        $this->em->refresh($nacionalidad);
        return $nacionalidad;
    }
}
