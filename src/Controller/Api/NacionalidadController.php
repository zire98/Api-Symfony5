<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\NacionalidadManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class NacionalidadController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/nacionalidades")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getPersonas(NacionalidadManager $nacionalidadManager)
    {
        return $nacionalidadManager->getRepository()->findAll();
    }
}
