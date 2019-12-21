<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email", "username"}, message="There is already an account with this email")
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="steam64id", type="string", length=255, nullable=false)
     */
    private $steam64id;

    /**
     * @ORM\Column(name="totalTaken", type="integer", nullable=false, options={"default"="0"})
     */
    private $totaltaken = '0';

    /**
     * @ORM\Column(name="dailyLimit", type="integer", nullable=false, options={"default"="25"})
     */
    private $dailylimit = '25';

    /**
     * @ORM\Column(name="totalRecruited", type="integer", nullable=false, options={"default"="0"})
     */
    private $totalrecruited = '0';

    /**
     * @ORM\Column(name="totalRejected", type="integer", nullable=false, options={"default"="0"})
     */
    private $totalrejected = '0';

    /**
     * @ORM\Column(name="totalSkipped", type="integer", nullable=false, options={"default"="0"})
     */
    private $totalSkipped = '0';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recruit", mappedBy="user")
     */
    private $recruits;

    public function __toString()
    {
        return $this->username.' '.$this->email;
    }


    public function __construct()
    {
        $this->recruits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getSteam64id()
    {
        return $this->steam64id;
    }

    /**
     * @param mixed $steam64id
     */
    public function setSteam64id($steam64id): void
    {
        $this->steam64id = $steam64id;
    }

    /**
     * @return mixed
     */
    public function getTotaltaken()
    {
        return $this->totaltaken;
    }

    /**
     * @param mixed $totaltaken
     */
    public function setTotaltaken($totaltaken): void
    {
        $this->totaltaken = $totaltaken;
    }

    /**
     * @return mixed
     */
    public function getDailylimit()
    {
        return $this->dailylimit;
    }

    /**
     * @param mixed $dailylimit
     */
    public function setDailylimit($dailylimit): void
    {
        $this->dailylimit = $dailylimit;
    }

    /**
     * @return mixed
     */
    public function getTotalrecruited()
    {
        return $this->totalrecruited;
    }

    /**
     * @param mixed $totalrecruited
     */
    public function setTotalrecruited($totalrecruited): void
    {
        $this->totalrecruited = $totalrecruited;
    }

    /**
     * @return mixed
     */
    public function getTotalrejected()
    {
        return $this->totalrejected;
    }

    /**
     * @param mixed $totalrejected
     */
    public function setTotalrejected($totalrejected): void
    {
        $this->totalrejected = $totalrejected;
    }

    /**
     * @return Collection|Recruit[]
     */
    public function getRecruits(): Collection
    {
        return $this->recruits;
    }

    public function addRecruit(Recruit $recruit): self
    {
        if (!$this->recruits->contains($recruit)) {
            $this->recruits[] = $recruit;
            $recruit->setUser($this);
        }

        return $this;
    }

    public function removeRecruit(Recruit $recruit): self
    {
        if ($this->recruits->contains($recruit)) {
            $this->recruits->removeElement($recruit);
            // set the owning side to null (unless already changed)
            if ($recruit->getUser() === $this) {
                $recruit->setUser(null);
            }
        }

        return $this;
    }
    public function increaseTotalTaken()
    {
        $this->totaltaken++;

        return $this;
    }

    public function increaseRecruited()
    {
        $this->totalrecruited++;

        return $this;
    }

    public function increaseRejected()
    {
        $this->totalrejected++;

        return $this;
    }

    public function increaseSkipped()
    {
        $this->totalSkipped++;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalSkipped()
    {
        return $this->totalSkipped;
    }

    /**
     * @param mixed $totalSkipped
     */
    public function setTotalSkipped($totalSkipped): void
    {
        $this->totalSkipped = $totalSkipped;
    }
}
