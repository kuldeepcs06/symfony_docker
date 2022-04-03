<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $entity_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $CategoryName;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $sku;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'text')]
    private $shortdesc;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $link;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'string', length: 255)]
    private $brand;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $rating;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $caffine_type;

    #[ORM\Column(type: 'string',nullable: true)]
    private $count;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $flavored;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $seasonal;

    #[ORM\Column(type: 'string', length: 255)]
    private $in_stock;

    #[ORM\Column(type: 'integer')]
    private $facebook;

    #[ORM\Column(type: 'string', length: 255)]
    private $isk_cup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $entity_id): self
    {
        $this->entity_id = $entity_id;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->CategoryName;
    }

    public function setCategoryName(string $CategoryName): self
    {
        $this->CategoryName = $CategoryName;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShortdesc(): ?string
    {
        return $this->shortdesc;
    }

    public function setShortdesc(string $shortdesc): self
    {
        $this->shortdesc = $shortdesc;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCaffineType(): ?string
    {
        return $this->caffine_type;
    }

    public function setCaffineType(?string $caffine_type): self
    {
        $this->caffine_type = $caffine_type;

        return $this;
    }

    public function getCount(): ?string
    {
        return $this->count;
    }

    public function setCount(string $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getFlavored(): ?string
    {
        return $this->flavored;
    }

    public function setFlavored(?string $flavored): self
    {
        $this->flavored = $flavored;

        return $this;
    }

    public function getSeasonal(): ?string
    {
        return $this->seasonal;
    }

    public function setSeasonal(?string $seasonal): self
    {
        $this->seasonal = $seasonal;

        return $this;
    }

    public function getInStock(): ?string
    {
        return $this->in_stock;
    }

    public function setInStock(string $in_stock): self
    {
        $this->in_stock = $in_stock;

        return $this;
    }

    public function getFacebook(): ?int
    {
        return $this->facebook;
    }

    public function setFacebook(int $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getIskCup(): ?string
    {
        return $this->isk_cup;
    }

    public function setIskCup(string $isk_cup): self
    {
        $this->isk_cup = $isk_cup;

        return $this;
    }
}
