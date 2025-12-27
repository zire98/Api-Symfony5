<?php

namespace App\Controller\Api;

use App\Entity\Persona;
use App\Form\Model\PersonaDto;
use App\Form\Type\PersonaFormType;
use App\Repository\PersonaRepository;
use App\Service\FileUploader;
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
    public function postPersonas(EntityManagerInterface $em, Request $request, FileUploader $fileUploader)
    {
        $personaDto = new PersonaDto();
        $form = $this->createForm(PersonaFormType::class, $personaDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona = new Persona();
            $persona->setNombre($personaDto->nombre);
            $persona->setImage($fileUploader->uploadBase64File($personaDto->base64Image));
            $em->persist($persona);
            $em->flush($persona);
            return $persona;
        }
        return $form;
    }
}
