<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Nationalite;
use App\Repository\AuteurRepository;
use App\Repository\NationaliteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods="GET")
     */
    public function listeAuteurs(AuteurRepository $repo,SerializerInterface $serializer)
    {
        $auteurs = $repo->findAll();
        $resultat= $serializer->serialize(
            $auteurs,
            'json',
            [
                'groups' =>["Auteurs"]
            ]
        );
        

        return new JsonResponse($resultat,Response::HTTP_OK,[], true);
       
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_show",methods="GET")
     */
    public function show(Auteur $auteur,SerializerInterface $serializer){
        
        $resultat = $serializer->serialize(
           $auteur,
           'json',
           [
               'groups' =>["PropAuteur"]
           ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    } 
    /**
     * @Route("/api/auteur/{id}", name="api_auteur_edit",methods="PUT")
     */

    public function edit(Auteur $auteur,Request $request,ManagerRegistry $em,NationaliteRepository $RepoNationalite,SerializerInterface $serializer){
      $data = $request->getContent();
      $dataTab = $serializer->decode($data,'json');
      $nationalite = $RepoNationalite->find($dataTab['nationalite']['id']);
      $serializer->deserialize($data,Auteur::class,'json',['object_to_populate'=>$auteur]);
      $auteur ->setNationalite($nationalite);

      $manager = $em->getManager();
      $manager->persist($auteur);
      $manager->flush();

      return new JsonResponse("l'auteur a été bien modifie",Response::HTTP_OK,[],true);
    }
}
