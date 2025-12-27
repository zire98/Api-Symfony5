<?php

namespace App\Controller\Api;

use App\Entity\Persona;
use App\Form\Model\PersonaDto;
use App\Form\Type\PersonaFormType;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use League\Flysystem\FilesystemOperator;
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
    public function postPersonas(EntityManagerInterface $em, Request $request, FilesystemOperator $defaultStorage)
    {
        $personaDto = new PersonaDto();
        $form = $this->createForm(PersonaFormType::class, $personaDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extension = explode('/', mime_content_type($personaDto->base64Image))[1];
            $data = explode(',', $personaDto->base64Image);
            $fileName = sprintf('%s.%s', uniqid('persona_', true), $extension);
            $defaultStorage->write($fileName, base64_decode($data[1]));

            $persona = new Persona();
            $persona->setNombre($personaDto->nombre);
            $persona->setImage($fileName);
            $em->persist($persona);
            $em->flush($persona);
            return $persona;
        }
        return $form;
    }
}
