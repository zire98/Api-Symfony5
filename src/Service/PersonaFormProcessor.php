<?php

namespace App\Service;

use App\Entity\Persona;
use App\Form\Model\NacionalidadDto;
use App\Form\Model\PersonaDto;
use App\Form\Type\PersonaFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class PersonaFormProcessor
{
    private $personaManager;
    private $nacionalidadManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        PersonaManager $personaManager,
        NacionalidadManager $nacionalidadManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->personaManager = $personaManager;
        $this->nacionalidadManager = $nacionalidadManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Persona $persona, Request $request): array
    {
        $personaDto = PersonaDto::createFromPersona($persona);
        $originalNacionalidades = new ArrayCollection();
        foreach ($persona->getNacionalidades() as $nacionalidad) {
            $nacionalidadDto = NacionalidadDto::createFromNacionalidad($nacionalidad);
            $personaDto->nacionalidades[] = $nacionalidadDto;
            $originalNacionalidades->add($nacionalidadDto);
        }
        $form = $this->formFactory->create(PersonaFormType::class, $personaDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submited']; //Mas optimo crear objeto error!
        }
        if ($form->isValid()) {
            // Borrar categorias
            foreach ($originalNacionalidades as $originalNacionalidadDto) {
                if (!in_array($originalNacionalidadDto, $personaDto->nacionalidades)) {
                    $nacionalidad = $this->nacionalidadManager->find($originalNacionalidadDto->id);
                    $persona->removeNacionalidade($nacionalidad);
                }
            }

            // Agregar Categorias
            foreach ($personaDto->nacionalidades as $newNacionalidadDto) {
                if (!$originalNacionalidades->contains($newNacionalidadDto)) {
                    $nacionalidad = $this->nacionalidadManager->find($newNacionalidadDto->id ?? 0);
                    if (!$nacionalidad) {
                        $nacionalidad = $this->nacionalidadManager->create();
                        $nacionalidad->setNombre($newNacionalidadDto->nombre);
                        $this->nacionalidadManager->persist($nacionalidad);
                    }
                    $persona->addNacionalidade($nacionalidad);
                }
            }
            $persona->setNombre($personaDto->nombre);
            if ($personaDto->base64Image) {
                $fileName = $this->fileUploader->uploadBase64File($personaDto->base64Image);
                $persona->setImage($fileName);
            }
            $this->personaManager->save($persona);
            $this->personaManager->reload($persona);
            return [$persona, null];
        }
        return [null, $form];
    }
}
