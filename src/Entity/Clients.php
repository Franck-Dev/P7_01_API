<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientsRepository;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ClientsRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already a client with this name")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(name="api_app_client_show",parameters={ "id" = "expr(object.getId())" },
 *          absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"listUsers"})
 * )
 * @Hateoas\Relation(
 *     "listClients",
 *     href = @Hateoas\Route(
 *          "api_app_clients_list",
 *          absolute = true
 *      )
 * )
 */
class Clients implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"list", "detail"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("detail")
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="Client")
     * @Groups("listUsers")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=ApiToken::class, mappedBy="client", cascade={"persist", "remove"})
     * @Groups({"detail","list"})
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
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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
        return array('ROLE_CLIENT');
    }

    public function eraseCredentials()
    {
    }
}
