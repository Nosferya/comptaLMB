<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reventeUnitaire;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="Setting")
     */
    private $User;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Saisie", inversedBy="setting")
     */
    private $Saisie;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->Saisie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReventeUnitaire(): ?int
    {
        return $this->reventeUnitaire;
    }

    public function setReventeUnitaire(int $reventeUnitaire): self
    {
        $this->reventeUnitaire = $reventeUnitaire;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Saisie[]
     */
    public function getSaisie(): Collection
    {
        return $this->Saisie;
    }

    public function addSaisie(Saisie $saisie): self
    {
        if (!$this->Saisie->contains($saisie)) {
            $this->Saisie[] = $saisie;
        }

        return $this;
    }

    public function removeSaisie(Saisie $saisie): self
    {
        if ($this->Saisie->contains($saisie)) {
            $this->Saisie->removeElement($saisie);
        }

        return $this;
    }
}
