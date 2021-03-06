<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\IsNull;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Entreprise", mappedBy="User")
     */
    private $Entreprise;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Setting", mappedBy="Saisie")
     */
    private $setting;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="saisies")
     */
    private $User;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSaisie;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->Entreprise = new ArrayCollection();
        $this->setting = new ArrayCollection();
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

    /**
     * @return Collection|Setting[]
     */
    public function getSetting(): Collection
    {
        return $this->setting;
    }

    public function addSetting(Setting $setting): self
    {
        if (!$this->setting->contains($setting)) {
            $this->setting[] = $setting;
            $setting->addSaisie($this);
        }

        return $this;
    }

    public function removeSetting(Setting $setting): self
    {
        if ($this->setting->contains($setting)) {
            $this->setting->removeElement($setting);
            $setting->removeSaisie($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDateSaisie(): ?\DateTimeInterface
    {
        return $this->dateSaisie;
    }

    public function setDateSaisie(?\DateTimeInterface $dateSaisie): self
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }
}
