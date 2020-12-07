<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Length(min=2, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Length(min=2, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @var string|null
     * @Assert\Regex(
     * pattern="/[0-9]{10}/"
     * )
     * @ORM\Column(type="string", length=10)
     */
    private $phone;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Length(min=10)
     * @ORM\Column(type="text")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
