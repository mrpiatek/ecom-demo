<?php

namespace App\Tests\Unit\Api;

use App\State\LineItemProcessor;
use ApiPlatform\State\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use ApiPlatform\Metadata\Operation;
use App\Entity\Cart;
use App\Entity\LineItem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LineItemProcessorTest extends TestCase
{
    private LineItemProcessor $lineItemProcessor;
    private ProcessorInterface $persistProcessorMock;

    protected function setup(): void {
        $this->lineItemProcessor = new LineItemProcessor(
            $this->persistProcessorMock = $this->createMock(ProcessorInterface::class),
            $this->createMock(ProcessorInterface::class)
        );
    }

    public function testCreateInvalidCart(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->method('getLineItems')->willReturn(new ArrayCollection([1,2,3]));

        $lineItemMock = $this->createMock(LineItem::class);
        $lineItemMock->method('getCart')->willReturn($cartMock);

        $this->persistProcessorMock->expects($this->never())->method('process');

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->lineItemProcessor->process(
            $lineItemMock,
            $this->createMock(Operation::class),
            [],
            []
        );
    }

    public function testCreateValidCart(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->method('getLineItems')->willReturn(new ArrayCollection([1,2]));

        $lineItemMock = $this->createMock(LineItem::class);
        $lineItemMock->method('getCart')->willReturn($cartMock);

        $this->persistProcessorMock->expects($this->once())->method('process');

        $this->lineItemProcessor->process(
            $lineItemMock,
            $this->createMock(Operation::class),
            [],
            []
        );
    }
}
