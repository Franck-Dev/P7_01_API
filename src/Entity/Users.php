<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"},message ="Cette adresse est deja utilise")(groups={"Create"})
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(name="api_app_user_show",parameters={ "id" = "expr(object.getId())" },
 *          absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"client"})
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route(
 *          "api_app_users_list",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"client"})
 * )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"Show","detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     * @Groups({"Show","detail"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     * @Groups("detail")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("client")
     */
    private $Client;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     * @Groups("detail")
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     * @Groups("detail")
     */
    private $roles = [];

    /**
     * @ORM\OneToOne(targetEntity=ApiToken::class, mappedBy="userClient", cascade={"persist", "remove"})
     * @Groups({"Show","detail"})
     */
    private $apiToken;

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
