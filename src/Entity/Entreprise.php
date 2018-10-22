<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomEntreprise;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\saisie", inversedBy="Entreprise")
     */
    private $User;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="Entreprise")
     */
    private $UserEntreprise;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->UserEntreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    /**
     * @return Collection|saisie[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(saisie $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
        }

        return $this;
    }

    public function removeUser(saisie $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserEntreprise(): Collection
    {
        return $this->UserEntreprise;
    }

    public function addUserEntreprise(User $userEntreprise): self
    {
        if (!$this->UserEntreprise->contains($userEntreprise)) {
            $this->UserEntreprise[] = $userEntreprise;
        }

        return $this;
    }

    public function removeUserEntreprise(User $userEntreprise): self
    {
        if ($this->UserEntreprise->contains($userEntreprise)) {
            $this->UserEntreprise->removeElement($userEntreprise);
        }

        return $this;
    }
}
