<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"get_role_adherent"}},
 *   CollectionOperations={
 *            "get"={
 *              "method"="GET",
 *              "path"="/adherents",
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/adherents/{id}",
 *              "access_control"="is_granted('ROLE_MANAGER')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"post_role_manager"}
 *              }
 *          },
 *          "statNbPretsParAdherent"={
 *              "method"="GET",
 *              "route_name"="adherents_nbPrets",
 *              "controller"=StatsController::class
 *          }
 *          },
 *   ItemOperations={
 *     "get"={
 *             "method"="GET",
 *              "path"="/adherents/{id}",
 *              "access_control"="( is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user )",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          }, 
 *          "getNbPrets"={
 *              "method"="GET",
 *              "route_name"="adherent_prets_count"
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/adherents/{id}",
 *              "access_control"="( is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user )",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              },             
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/adherents/{id}",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "access_control_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *          }
 *   }
 * )
 * @UniqueEntity(
 *  fields={"email"},
 *  message = "Cet email existe déja"
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "mail": "exact"
 *      }
 * )
 */
class Adherent implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADHERENT ='ROLE_ADHERENT';
    const DEFAULT_ROLE = 'ROLE_ADHERENT';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_role_adherent","get_nbPretsParadherent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","post_role_manager","put_role_manager","get_nbPretsParadherent"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","post_role_manager","put_role_manager","get_nbPretsParadherent"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent","post_role_manager","put_role_manager"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent","post_role_manager","put_role_manager"})
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent","post_role_manager","put_role_manager"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $password;

     /**
     * @ORM\Column(type="array", length=255, nullable=true)
     * @Groups({"get_role_adherent","get_role_manager","post_role_admin","put_role_admin"})
     */
    private $roles;
    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pret", mappedBy="adherent")
     * @Groups({"get_role_adherent"})
     * @ApiSubresource
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $roleDefeult[] =  self::DEFAULT_ROLE ;
        $this->roles= $roleDefeult; 
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->codeCommune;
    }

    public function setCodeCommune(?string $codeCommune): self
    {
        $this->codeCommune = $codeCommune;

        return $this;
    }

    public function getEmail(): ?string
    {  
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setAdherent($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }

        return $this;
    }
   /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles(): array
    {  
        return $this->roles;
    }
    /**
     * affecte les roles de l'utilisateur
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    public function getSalt(){
        return null;
    }
    public function getUsername(){
        return $this->getEmail();
    }
    public function eraseCredentials(){}

}
