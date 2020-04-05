<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ValidationRepository")
 * @UniqueEntity({"createdBy", "challenge"})
 */
class Validation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="validations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;
// La doc a l'air de dire que pour l'unicité d'un couple d'entités, on mets pas le unique = true
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Challenge", inversedBy="validations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $challenge;

    /**
     * @ORM\Column(type="datetime")
     */
    private $validatedOn;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "La note de feedback est un nombre supérieur à {{ limit }}",
     *     maxMessage = "La note de feedback est un nombre inférieur à {{ limit }}"
     * )
     */
    private $feedback;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

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

    public function getValidatedOn(): ?\DateTimeInterface
    {
        return $this->validatedOn;
    }

    public function setValidatedOn(\DateTimeInterface $validatedOn): self
    {
        $this->validatedOn = $validatedOn;

        return $this;
    }

    public function getFeedback(): ?int
    {
        return $this->feedback;
    }

    public function setFeedback(int $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }
}
