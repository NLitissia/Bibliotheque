<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genre", name="api_genre",methods={"GET"})
     */
    public function list(GenreRepository $repo,SerializerInterface $serializer)
    {
        $genres = $repo->findAll();
        //dump($genres).die();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups'=>['GenreFull']
            ]
        );
        //dump($resultat).die();
        return new JsonResponse($resultat,200,[],true);
    }

    
}
