<?php
 
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
 
/**
 * @ORM\Entity(repositoryClass="App\Repository\PretRepository")
 * @ApiResource(
 *      attributes={
 *          "order"= {
 *              "datePret":"ASC"
 *           }
 *      },
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/prets",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/prets",
 *              "denormalization_context"= {
 *                  "groups"={"pret_post_role_adherent"}
 *              }
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/prets/{id}",
 *              "access_control"="(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "access_control_message" = "Vous ne pouvez avoir accès qu'à vos propres prêts."
 *           },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/prets/{id}",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"pret_put_manager"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/prets/{id}",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          }
 *      }
 * )
 * 
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "datePret",
 *          "dateRetourPrevue",
 *          "dateRetourReelle"
 *      }
 * )
 * 
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "livre.titre": "ipartial",
 *          "adherent.id": "exact"
 *      }
 * )
 * 
 * @ApiFilter(
 *      DateFilter::class,
 *      properties={
 *          "datePret",
 *          "dateRetourPrevue",
 *          "dateRetourReelle"
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
 
class Pret
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\Column(type="datetime")
     */
    private $datePret;
 
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetourPrevue;
 
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"pret_put_manager"})
     */
    private $dateRetourReelle;
 
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Livre", inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"pret_post_role_adherent"})
     */
    private $livre;
 
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adherent", inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;
 
 
    public function __construct()
    {
        $this->datePret = new \DateTime();
        $dateRetourPrevue = date('Y-m-d H:m:n', strtotime('15 days', $this->getDatePret()->getTimestamp()));
        $dateRetourPrevue = \DateTime::createFromFormat('Y-m-d H:m:n', $dateRetourPrevue);
        $this->dateRetourPrevue = $dateRetourPrevue;
        $this->dateRetourReelle = null;
    }
    public function getId(): ? int
    {
        return $this->id;
    }
 
    public function getDatePret(): ? \DateTimeInterface
    {
        return $this->datePret;
    }
 
    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;
 
        return $this;
    }
 
    public function getDateRetourPrevue(): ? \DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }
 
    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;
 
        return $this;
    }
 
    public function getDateRetourReelle(): ? \DateTimeInterface
    {
        return $this->dateRetourReelle;
    }
 
    public function setDateRetourReelle(? \DateTimeInterface $dateRetourReelle): self
    {
        $this->dateRetourReelle = $dateRetourReelle;
 
        return $this;
    }
 
    public function getLivre(): ? Livre
    {
        return $this->livre;
    }
 
    public function setLivre(? Livre $livre): self
    {
        $this->livre = $livre;
 
        return $this;
    }
 
    public function getAdherent(): ? Adherent
    {
        return $this->adherent;
    }
 
    public function setAdherent(? Adherent $adherent): self
    {
        $this->adherent = $adherent;
 
        return $this;
    }
     /**
      *  @ORM\PrePersist
      *
      * @return void
      */
    // public function RendInDispoLivre(){
    // 
    //   $this->getLivre()->setDispo(false);
    // }
}