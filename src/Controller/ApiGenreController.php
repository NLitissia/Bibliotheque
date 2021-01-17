<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

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

    /**
     * @Route("/api/genre/{id}", name="api_genre_show",methods={"GET"})
     */
    public function show(Genre $genre,SerializerInterface $serializer){
        
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups'=>['GenreProp']
            ]
            );
            return new JsonResponse($resultat,Response::HTTP_OK,[],true);



    }
    
}
