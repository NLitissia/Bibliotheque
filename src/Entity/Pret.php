<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource()
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePert;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateRetourPrevu;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetoutRelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePert(): ?\DateTimeInterface
    {
        return $this->datePert;
    }

    public function setDatePert(\DateTimeInterface $datePert): self
    {
        $this->datePert = $datePert;

        return $this;
    }

    public function getDateRetourPrevu(): ?\DateTimeInterface
    {
        return $this->DateRetourPrevu;
    }

    public function setDateRetourPrevu(\DateTimeInterface $DateRetourPrevu): self
    {
        $this->DateRetourPrevu = $DateRetourPrevu;

        return $this;
    }

    public function getDateRetoutRelle(): ?\DateTimeInterface
    {
        return $this->dateRetoutRelle;
    }

    public function setDateRetoutRelle(?\DateTimeInterface $dateRetoutRelle): self
    {
        $this->dateRetoutRelle = $dateRetoutRelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}
