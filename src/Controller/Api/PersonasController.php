<?php

namespace App\Controller\Api;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class PersonasController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAcions(PersonaRepository $personaRepository)
    {
        return $personaRepository->findAll();
    }

    /**
     * @Rest\Post(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAcions(EntityManagerInterface $em)
    {
        $persona = new Persona();

        $persona->setNombre("Joel");
        $em->persist($persona);
        $em->flush($persona);
        return $persona;
    }
}
