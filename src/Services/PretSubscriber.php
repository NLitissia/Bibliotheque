<?php

namespace App\Services;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Pret;
use App\Kernel;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface{
private $token;
public function __construct(TokenStorageInterface $token){
$this->token = $token;
}
public static function getSubscribedEvents(){


    return [
      KernelEvents::VIEW => ['getAuthenticatedUser',EventPriorities::PRE_WRITE]
    ];
}

public function getAuthenticatedUser(ViewEvent $event){
    //dump($event).die();
    $entity = $event->getControllerResult();
    $method=$event->getRequest()->getMethod();
    $adherent = $this->token->getToken()->getUser();
    if ($entity instanceof Pret) {
        if ($method == "POST") {
            $entity->setAdherent($adherent);
        } elseif ($method == "PUT") {
               if($entity->getDateRetourReelle() == null) {
                  $entity->getLivre()->setDispo(false);
               } else {
                 $entity->getLivre()->setDispo(true);
               }
        } elseif ($method == "DELETE") {
          $entity->getLivre()->setDispo(true);
        }
    }
}
}