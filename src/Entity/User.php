<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"nickname"},
 * message="le nickname est déjà utilisé"
 * )
 */
class User implements UserInterface
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
    private $prenomUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telUser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = "8", minMessage= "Votre mot de passe doit contenir 8 caractères", max = "50", maxMessage="Votre mot de passe est trop long !")
     */
    private $passwordUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $primeUser;

    /**
     * @ORM\Column(type="date")
     */
    private $dateInscription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Grade", inversedBy="user")
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="relation")
     */
    private $niveau;

    private $userRole;

 

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Entreprise", mappedBy="UserEntreprise")
     */
    private $Entreprise;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Setting", mappedBy="User")
     */
    private $Setting;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="integer")
     */
    private $firstLogin;

    /** 
    * @Assert\EqualTo(propertyPath="PasswordUser", message="vos mots de passe ne sont pas identiques")
    */

    public $confirmPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Saisie", mappedBy="User", cascade={"remove"})
     */
    private $saisies;

    public function __construct()
    {
        $this->Saisies = new ArrayCollection();
        $this->Entreprise = new ArrayCollection();
        $this->Setting = new ArrayCollection();
        $this->saisies = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): self
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): self
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getTelUser(): ?int
    {
        return $this->telUser;
    }

    public function setTelUser(?int $telUser): self
    {
        $this->telUser = $telUser;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->passwordUser;
    }

    public function setPasswordUser(string $passwordUser): self
    {
        $this->passwordUser = $passwordUser;

        return $this;
    }

    public function getPrimeUser(): ?int
    {
        return $this->primeUser;
    }

    public function setPrimeUser(?int $primeUser): self
    {
        $this->primeUser = $primeUser;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getNiveau():?Niveau
    {
        return  $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

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
            $entreprise->addUserEntreprise($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->Entreprise->contains($entreprise)) {
            $this->Entreprise->removeElement($entreprise);
            $entreprise->removeUserEntreprise($this);
        }

        return $this;
    }

    /**
     * @return Collection|Setting[]
     */
    public function getSetting(): Collection
    {
        return $this->Setting;
    }

    public function addSetting(Setting $setting): self
    {
        if (!$this->Setting->contains($setting)) {
            $this->Setting[] = $setting;
            $setting->addUser($this);
        }

        return $this;
    }

    public function removeSetting(Setting $setting): self
    {
        if ($this->Setting->contains($setting)) {
            $this->Setting->removeElement($setting);
            $setting->removeUser($this);
        }

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->nickname;
    }
    
    public function getPassword(): ?string
    {
        return $this->passwordUser;
    }

    
    public function eraseCredentials()
    {
    }
    public function getSalt()
    {
    }
    public function getRoles()
    {
        $roles[] = $this->niveau->getNomNiveau();
        


     return $roles;
    }

    public function getFirstLogin(): ?int
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(int $firstLogin): self
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * @return Collection|Saisie[]
     */
    public function getSaisies(): Collection
    {
        return $this->saisies;
    }

    public function addSaisy(Saisie $saisy): self
    {
        if (!$this->saisies->contains($saisy)) {
            $this->saisies[] = $saisy;
            $saisy->setUser($this);
        }

        return $this;
    }

    public function removeSaisy(Saisie $saisy): self
    {
        if ($this->saisies->contains($saisy)) {
            $this->saisies->removeElement($saisy);
            // set the owning side to null (unless already changed)
            if ($saisy->getUser() === $this) {
                $saisy->setUser(null);
            }
        }

        return $this;
    }
}
