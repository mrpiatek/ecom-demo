<?php

namespace App\Tests\Unit\Api;

use App\State\CartProcessor;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Cart;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Metadata\Operation;

class CartProcessorTest extends TestCase
{
    private CartProcessor $cartProcessor;
    protected function setup(): void {
        $this->cartProcessor = new CartProcessor(
            $this->createMock(ProcessorInterface::class),
            $this->createMock(ProcessorInterface::class),
            $this->createMock(Security::class),
        );
    }

    public function testCreateCart(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->expects($this->once())->method('setUser');
        $this->cartProcessor->process(
            $cartMock,
            $this->createMock(Operation::class),
            [],
            []
        );
    }
}
