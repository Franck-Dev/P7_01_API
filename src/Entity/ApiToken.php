<?php

namespace App\Entity;

use App\Entity\Users;
use App\Entity\Clients;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use App\Repository\ApiTokenRepository;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups("Show")
     */
    private $token;

    /**
     * @ORM\OneToOne(targetEntity=Clients::class, inversedBy="apiToken", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity=users::class, inversedBy="apiToken", cascade={"persist", "remove"})
     */
    private $userClient;

    public function __construct(Clients $client=null, Users $user=null)
    {
        $this->token = bin2hex(random_bytes(60));
        if ($client) {
            $this->client = $client;
        } else {
            $this->userClient = $user;
        }
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUserClient(): ?users
    {
        return $this->userClient;
    }

    public function setUserClient(?users $userClient): self
    {
        $this->userClient = $userClient;

        return $this;
    }
}
