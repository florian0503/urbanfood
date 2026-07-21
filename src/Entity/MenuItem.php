<?php

namespace App\Entity;

use App\Repository\MenuItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuItemRepository::class)]
class MenuItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * Description alternative affichee dans "Les incontournables" de l'accueil.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $featuredDescription = null;

    #[ORM\Column(length: 20)]
    private ?string $price = null;

    /**
     * Tag pilule affiche dans "Les incontournables" (ex. BEST-SELLER).
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tag = null;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column]
    private bool $featured = false;

    /**
     * Ordre d'affichage dans "Les incontournables" (null si non mis en avant).
     */
    #[ORM\Column(nullable: true)]
    private ?int $featuredPosition = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MenuCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFeaturedDescription(): ?string
    {
        return $this->featuredDescription;
    }

    public function setFeaturedDescription(?string $featuredDescription): static
    {
        $this->featuredDescription = $featuredDescription;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }

    public function getFeaturedPosition(): ?int
    {
        return $this->featuredPosition;
    }

    public function setFeaturedPosition(?int $featuredPosition): static
    {
        $this->featuredPosition = $featuredPosition;

        return $this;
    }

    public function getCategory(): ?MenuCategory
    {
        return $this->category;
    }

    public function setCategory(?MenuCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
