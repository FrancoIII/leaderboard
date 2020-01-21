<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttemptRepository")
 */
class Attempt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="attempts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $attemptedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Challenge", inversedBy="attempts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $challenge;

    /**
     * @ORM\Column(type="datetime")
     */
    private $attemptedOn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attempt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttemptedBy(): ?User
    {
        return $this->attemptedBy;
    }

    public function setAttemptedBy(?User $attemptedBy): self
    {
        $this->attemptedBy = $attemptedBy;

        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getAttemptedOn(): ?\DateTimeInterface
    {
        return $this->attemptedOn;
    }

    public function setAttemptedOn(\DateTimeInterface $attemptedOn): self
    {
        $this->attemptedOn = $attemptedOn;

        return $this;
    }

    public function getAttempt(): ?string
    {
        return $this->attempt;
    }

    public function setAttempt(string $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }
}
