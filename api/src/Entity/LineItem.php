<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class LineItem
{
    private ?Uuid $id = null;

    public ?Cart $cart = null;

    public ?Product $product = null;

    public int $quantity = 0;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }
}
