<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Product
{
    private ?Uuid $id = null;

    private string $name = '';

    private int $price = 0;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
