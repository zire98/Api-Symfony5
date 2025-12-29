<?php

namespace App\Controller\Api;

use App\Entity\Nacionalidad;
use App\Entity\Persona;
use App\Form\Model\NacionalidadDto;
use App\Form\Model\PersonaDto;
use App\Form\Type\PersonaFormType;
use App\Repository\NacionalidadRepository;
use App\Repository\PersonaRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            $persona = new Persona();
            $persona->setNombre($personaDto->nombre);
            if ($personaDto->base64Image) {
                $fileName = $fileUploader->uploadBase64File($personaDto->base64Image);
                $persona->setImage($fileName);
            }
            $em->persist($persona);
            $em->flush($persona);
            return $persona;
        }
        return $form;
    }

    /**
     * @Rest\Post(path="/personas/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function editPersonas(
        int $id,
        EntityManagerInterface $em,
        PersonaRepository $personaRepository,
        NacionalidadRepository $nacionalidadRepository,
        Request $request,
        FileUploader $fileUploader
    ) {
        $persona = $personaRepository->find($id);
        if (!$persona) {
            throw $this->createNotFoundException('Persona no encontrada');
        }
        $personaDto = PersonaDto::createFromPersona($persona);

        $originalNacionalidades = new ArrayCollection();
        foreach ($persona->getNacionalidades() as $nacionalidad) {
            $nacionalidadDto = NacionalidadDto::createFromNacionalidad($nacionalidad);
            $personaDto->nacionalidades[] = $nacionalidadDto;
            $originalNacionalidades->add($nacionalidadDto);
        }

        $form = $this->createForm(PersonaFormType::class, $personaDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()) {
            // Borrar categorias
            foreach ($originalNacionalidades as $originalNacionalidadDto) {
                if (!in_array($originalNacionalidadDto, $personaDto->nacionalidades)) {
                    $nacionalidad = $nacionalidadRepository->find($originalNacionalidadDto->id);
                    $persona->removeNacionalidade($nacionalidad);
                }
            }

            // Agregar Categorias
            foreach ($personaDto->nacionalidades as $newNacionalidadDto) {
                if (!$originalNacionalidades->contains($newNacionalidadDto)) {
                    $nacionalidad = $nacionalidadRepository->find($newNacionalidadDto->id ?? 0);
                    if (!$nacionalidad) {
                        $nacionalidad = new Nacionalidad();
                        $nacionalidad->setNombre($newNacionalidadDto->nombre);
                        $em->persist($nacionalidad);
                    }
                    $persona->addNacionalidade($nacionalidad);
                }
            }
            $persona->setNombre($personaDto->nombre);
            if ($personaDto->base64Image) {
                $fileName = $fileUploader->uploadBase64File($personaDto->base64Image);
                $persona->setImage($fileName);
            }
            $em->persist($persona);
            $em->flush();
            $em->refresh($persona);
            return $persona;
        }
        return $form;
    }
}
