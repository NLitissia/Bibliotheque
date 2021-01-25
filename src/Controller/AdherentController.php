<?php

namespace App\Controller;

use App\Entity\Adherent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{
    /**
     * @Route("/adherent", name="adherent")
     */
    public function index(): Response
    {
        return $this->render('adherent/index.html.twig', [
            'controller_name' => 'AdherentController',
        ]);
    }
    /**
     * Renvoyer le nombre de prets pour un adherent donner
     * @Route(
     * path="apiPlatform/adherent/{id}/pret/count",
     * name="adherent_prets_count",
     * methods={"GET"},
     * defaults={
     *       "_controller"="\app\controller\AdherentController::nombrePrets",
     *       "_api_resource_class"="App\Entity\Adherent",
     *       "_api_item_operation_name"="getNbPrets" 
     * })
     */

     public function nombresPrets(Adherent $data){

        $count = $data->getPrets()->count();
        return $this->json([
            "id" => $data->getId(),
             "nombre_prets" => $count
        ]);
     }
}
