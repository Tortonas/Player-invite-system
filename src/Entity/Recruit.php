<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecruitRepository")
 */
class Recruit
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
    private $steamId;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $steamLink;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $contacted = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $takenDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="pending"})
     */
    private $action = 'pending';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recruits")
     */
    private $user;

    public function __toString()
    {
        return $this->nickname;
    }

    public function flipContacted()
    {
        if($this->contacted == 0)
        {
            $this->contacted = 1;
        }
        else
        {
            $this->contacted = 0;
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSteamId(): ?string
    {
        return $this->steamId;
    }

    public function setSteamId(string $steamId): self
    {
        $this->steamId = $steamId;

        return $this;
    }

    public function getSteamLink(): ?string
    {
        return $this->steamLink;
    }

    public function setSteamLink(string $steamLink): self
    {
        $this->steamLink = $steamLink;

        return $this;
    }

    public function getContacted(): ?int
    {
        return $this->contacted;
    }

    public function setContacted(int $contacted): self
    {
        $this->contacted = $contacted;

        return $this;
    }

    public function getTakenDate(): ?\DateTimeInterface
    {
        return $this->takenDate;
    }

    public function setTakenDate(?\DateTimeInterface $takenDate): self
    {
        $this->takenDate = $takenDate;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }
}
