<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use App\Repository\ProduitsRepository;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 * @UniqueEntity(fields={"Name"}, message="There is already a product with this name")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(name="api_app_produit_show",parameters={ "id" = "expr(object.getId())" },
 *          absolute = true)
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route(
 *          "api_app_produits_list",
 *          absolute = true
 *      )
 * )
 */
class Produits
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
     * @Assert\NotBlank
     * @Groups({"list", "detail", "create"})
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"detail", "create"})
     */
    private $Description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
}
