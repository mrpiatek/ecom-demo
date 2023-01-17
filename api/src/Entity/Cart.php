<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Cart
{
    private ?Uuid $id = null;

    public ?User $user = null;

    private $lineItems;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
    }

    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getTotal(): int
    {
        return $this->lineItems->reduce(
            static fn(int $carry, LineItem $lineItem) => $carry + $lineItem->getProduct()->getPrice() * $lineItem->getQuantity(),
            0
        );
    }
}
