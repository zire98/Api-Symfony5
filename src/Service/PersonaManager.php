<?php

namespace App\Service;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;

class PersonaManager
{
    private $em;
    private $personaRepository;

    public function __construct(EntityManagerInterface $em, PersonaRepository $personaRepository)
    {
        $this->em = $em;
        $this->personaRepository = $personaRepository;
    }

    public function find(int $id): ?Persona
    {
        return $this->personaRepository->find($id);
    }

    public function getRepository(): PersonaRepository
    {
        return $this->personaRepository;
    }

    public function create(): Persona
    {
        $persona = new Persona();
        return $persona;
    }

    public function save(Persona $persona): Persona
    {
        $this->em->persist($persona);
        $this->em->flush();
        return $persona;
    }

    public function reload(Persona $persona): Persona
    {
        $this->em->refresh($persona);
        return $persona;
    }

    public function delete(Persona $persona)
    {
        $this->em->remove($persona);
        $this->em->flush();
    }
}
