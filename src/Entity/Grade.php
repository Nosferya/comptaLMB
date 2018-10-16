<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 */
class Grade
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
    private $nomGrade;

    /**
     * @ORM\Column(type="integer")
     */
    private $pourcentPnj;

    /**
     * @ORM\Column(type="integer")
     */
    private $pourcentPj;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="grade")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGrade(): ?string
    {
        return $this->nomGrade;
    }

    public function setNomGrade(string $nomGrade): self
    {
        $this->nomGrade = $nomGrade;

        return $this;
    }

    public function getPourcentPnj(): ?int
    {
        return $this->pourcentPnj;
    }

    public function setPourcentPnj(int $pourcentPnj): self
    {
        $this->pourcentPnj = $pourcentPnj;

        return $this;
    }

    public function getPourcentPj(): ?int
    {
        return $this->pourcentPj;
    }

    public function setPourcentPj(int $pourcentPj): self
    {
        $this->pourcentPj = $pourcentPj;

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
            $user->setGrade($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGrade() === $this) {
                $user->setGrade(null);
            }
        }

        return $this;
    }
}
