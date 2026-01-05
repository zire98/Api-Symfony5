<?php

namespace App\Controller\Api;

use App\Service\PersonaFormProcessor;
use App\Service\PersonaManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View as RestBundleView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonasController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getPersonas(PersonaManager $personaManager)
    {
        return $personaManager->getRepository()->findAll();
    }

    /**
     * @Rest\Get(path="/personas/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function getPersona(PersonaManager $personaManager, int $id)
    {
        $persona = $personaManager->find($id);
        if (!$persona) {
            return RestBundleView::create('Persona not found', Response::HTTP_BAD_REQUEST);
        }
        return $persona;
    }

    /**
     * @Rest\Post(path="/personas")
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function postPersonas(
        PersonaManager $personaManager,
        PersonaFormProcessor $personaFormProcessor,
        Request $request,
    ) {
        $persona = $personaManager->create();
        [$persona, $error] = ($personaFormProcessor)($persona, $request);
        $statusCode = $persona ? Response::HTTP_CREATED :  Response::HTTP_BAD_REQUEST;
        $data = $persona ?? $error;
        return RestBundleView::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/personas/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function editPersonas(
        int $id,
        PersonaFormProcessor $personaFormProcessor,
        PersonaManager $personaManager,
        Request $request
    ) {
        $persona = $personaManager->find($id);
        if (!$persona) {
            return RestBundleView::create('Persona not found', Response::HTTP_BAD_REQUEST);
        }
        [$persona, $error] = ($personaFormProcessor)($persona, $request);
        $statusCode = $persona ? Response::HTTP_CREATED :  Response::HTTP_BAD_REQUEST;
        $data = $persona ?? $error;
        return RestBundleView::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/personas/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"persona"}, serializerEnableMaxDepthChecks=true)
     */
    public function deletePersonas(
        int $id,
        PersonaManager $personaManager,
        Request $request
    ) {
        $persona = $personaManager->find($id);
        if (!$persona) {
            return RestBundleView::create('Persona not found', Response::HTTP_BAD_REQUEST);
        }
        $personaManager->delete($persona);
        return RestBundleView::create(null, Response::HTTP_NO_CONTENT);
    }
}
