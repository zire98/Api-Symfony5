<?php

namespace App\Controller\Api;

use App\Entity\Persona;
use App\Form\Type\PersonaFormType;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class PersonasController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getPersonas(PersonaRepository $personaRepository)
    {
        return $personaRepository->findAll();
    }

    /**
     * @Rest\Post(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function postPersonas(EntityManagerInterface $em, Request $request)
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaFormType::class, $persona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($persona);
            $em->flush($persona);
            return $persona;
        }
        return $form;
    }
}
