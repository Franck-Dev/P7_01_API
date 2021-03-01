<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"},message ="Cette adresse est deja utilise")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getClient(): ?Clients
    {
        return $this->Client;
    }

    public function setClient(?Clients $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(){}

    public function getSalt(){}

    public function getRoles(): ?array
    {
        if (empty($this->roles)) {
             return ['ROLE_USER'];
         }
         return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of apiToken
     */ 
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Set the value of apiToken
     *
     * @return  self
     */ 
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
