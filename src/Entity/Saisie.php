<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaisieRepository")
 */
class Saisie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $venteGrossiste;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $venteParticulier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $achatEntreprise;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="Saisies")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Entreprise", mappedBy="User")
     */
    private $Entreprise;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->Entreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVenteGrossiste(): ?int
    {
        return $this->venteGrossiste;
    }

    public function setVenteGrossiste(?int $venteGrossiste): self
    {
        $this->venteGrossiste = $venteGrossiste;

        return $this;
    }

    public function getVenteParticulier(): ?int
    {
        return $this->venteParticulier;
    }

    public function setVenteParticulier(?int $venteParticulier): self
    {
        $this->venteParticulier = $venteParticulier;

        return $this;
    }

    public function getAchatEntreprise(): ?int
    {
        return $this->achatEntreprise;
    }

    public function setAchatEntreprise(?int $achatEntreprise): self
    {
        $this->achatEntreprise = $achatEntreprise;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getEntreprise(): Collection
    {
        return $this->Entreprise;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->Entreprise->contains($entreprise)) {
            $this->Entreprise[] = $entreprise;
            $entreprise->addUser($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->Entreprise->contains($entreprise)) {
            $this->Entreprise->removeElement($entreprise);
            $entreprise->removeUser($this);
        }

        return $this;
    }
}
