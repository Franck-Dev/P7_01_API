<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ClientsRepository::class)
 * @UniqueEntity(fields={"Name"}, message="There is already a client with this name")
 */
class Clients implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="Client")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=ApiToken::class, mappedBy="client", cascade={"persist", "remove"})
     */
    private $apiToken;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Name;
    }

    public function setUsername(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }

    public function getApiToken(): ?ApiToken
    {
        return $this->apiToken;
    }

    public function setApiToken(ApiToken $apiToken): self
    {
        // set the owning side of the relation if necessary
        if ($apiToken->getClient() !== $this) {
            $apiToken->setClient($this);
        }

        $this->apiToken = $apiToken;

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }
}
