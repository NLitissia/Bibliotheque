<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 * @ApiResource(
 *      attributes=
 *          {
 *          "order"= {"nom":"ASC"},
 *          "pagination_enabled"=false
 *          },
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"=
 *                  {
 *                      "groups"={"get"}
 *                  }
 *           },
 *          "post"={
 *              "method"="POST",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }  
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"={
 *                  "groups"={"get_auteur_role_adherent"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }  
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          }        
 * }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "nom": "ipartial",
 *          "prenom": "ipartial",
 *          "nationalite" : "exact"
 *      }
 * )
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class, inversedBy="auteurs")
     */
    private $nationalite;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->nom." ". $this->prenom;
    }
    /**
     * Retourne le nombre de livre de l'auteur
     * @Groups({"get"})
     * @return integer
     */
    public function getNbLivres(): int {
        return $this->livres->count();
    }
    
     /**
     * Retourne le nombre de livre de l'auteur
     * @Groups({"get"})
     * @return integer
     */
    public function getNbDispo() :int {
        $nbdispo = 0;
        $tablivres = $this->livres->toArray();
        foreach( $tablivres as  $livre) {
           if($livre->getDispo() === true) {
                $nbdispo++;
           }
        }
        return $nbdispo;
    }
}
