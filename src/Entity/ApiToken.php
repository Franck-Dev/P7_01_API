<?php

namespace App\Entity;

use App\Entity\Clients;
use Doctrine\ORM\Mapping as ORM;
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
     */
    private $token;

    /**
     * @ORM\OneToOne(targetEntity=Clients::class, inversedBy="apiToken", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function __construct(Clients $client)
    {
        $this->token = bin2hex(random_bytes(60));
        $this->client = $client;
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
}
