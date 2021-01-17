<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function show(Genre $genre,ManagerRegistry $manager,SerializerInterface $serializer){
        
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups'=>['GenreProp']
            ]
            );
            return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genre", name="api_genre_create", methods={"POST"})
     */
    
     public function create(Request $request,ManagerRegistry $em,SerializerInterface $serializer,ValidatorInterface $validator){
        
       $data = $request->getContent();
       $genre = new Genre();
       $serializer->deserialize($data, Genre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]);
       
       //Gestion des erreur de validation
       $error = $validator->validate($genre);
       if(count($error)){
           $errorJson=$serializer->serialize($error,'json');
           return new JsonResponse($errorJson,Response::HTTP_BAD_REQUEST,[],true);
       }
       $manager = $em->getManager();
       $manager->persist($genre);
       $manager->flush();
       return new JsonResponse(
           "Le genre a été crée",
       Response::HTTP_CREATED,
       [
           "location"=>"/api/genre/".$genre->getId()
       ],
       true);

     }


     /**
      * @Route("/api/genre/{id}", name="api_genre_update",methods={"PUT"})
      */
      public function edit(Genre $genre,Request $request, ManagerRegistry $em,SerializerInterface $serializer){
   
         $data = $request->getContent();
         $serializer->deserialize(
            $data,
            Genre::class,
            'json',
            ['object_to_populate'=>$genre]
        );
           $manager = $em->getManager();
           $manager->persist($genre);
           $manager->flush();

        return new JsonResponse(
            "le genre a été bien modifié",
             Response::HTTP_OK,
            [],
            true
        );
         
      }

      /**
       * @Route("/api/genre/{id}", name="api_genre_delete", methods={"DELETE"})
       */
    public function delete(Genre $genre,ManagerRegistry $em){

        $manager = $em->getManager();
        $manager->remove($genre);
        $manager->flush();
        return new JsonResponse(
            "le genre a été bien supprimer",
            Response::HTTP_OK,
            [],
            true
        );
    }  
}
