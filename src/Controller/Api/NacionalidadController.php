<?php

namespace App\Controller\Api;

use App\Form\Model\NacionalidadDto;
use App\Form\Type\NacionalidadFormType;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\NacionalidadManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class NacionalidadController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/nacionalidades")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getNacionalidades(NacionalidadManager $nacionalidadManager)
    {
        return $nacionalidadManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/nacionalidades")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function createNacionalidad(NacionalidadManager $nacionalidadManager, Request $request)
    {
        $nacionalidadDto = new NacionalidadDto();
        $form = $this->createForm(NacionalidadFormType::class, $nacionalidadDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nacionalidad = $nacionalidadManager->create();
            $nacionalidad->setNombre($nacionalidadDto->nombre);
            $nacionalidadManager->save($nacionalidad);
            return $nacionalidad;
        }
        return $form;
    }
}
