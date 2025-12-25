<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonaController extends AbstractController
{

    /**
     * @Route("/persona/list", name="persona_list")
     */
    public function list(PersonaRepository $personaRepository)
    {
        $personas = $personaRepository->findAll();
        $personasAsArray = [];

        foreach ($personas as $persona) {
            $personasAsArray = [
                'id' => $persona->getId(),
                'nombre' => $persona->getNombre(),
                'image' => $persona->getImage()
            ];
        }

        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' => $personasAsArray
        ]);
        return $response;
    }

    /**
     * @Route("/persona/create", name="create_persona")
     */
    public function createPersona(Request $request, EntityManagerInterface $em)
    {
        $persona = new Persona();
        $nombre = $request->get('nombre');

        if (empty($nombre)) {
            $response = new JsonResponse([
                'succes' => false,
                'error' => 'Empty name',
                'data' => null
            ]);
            return $response;
        }

        $persona->setNombre($nombre);
        $em->persist($persona);
        $em->flush($persona);
        $response = new JsonResponse([
            'succes' => true,
            'data' => [
                'id' => $persona->getId(),
                'nombre' => $persona->getNombre()
            ]
        ]);
        return $response;
    }
}
