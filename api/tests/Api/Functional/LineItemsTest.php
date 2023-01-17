<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Cart;
use App\Entity\Product;
use App\Tests\Api\Util\DoctrineHelperTrait;
use App\Tests\Api\Util\LoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class LineItemsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use LoginTrait;
    use DoctrineHelperTrait;

    protected function setup(): void 
    {
        $this->loginAs('admin', 'admin');
    }

    public function testCreateLineItem(): void
    {
        /** @var $cart Cart */
        $cart = $this->getAnyEntity(Cart::class);
        self::assertInstanceOf(Cart::class, $cart);

        /** @var $product Product */
        $product = $this->getAnyEntity(Product::class);
        self::assertInstanceOf(Product::class, $product);

        $this->client->request('POST', "/carts/line_items",['json' => [
            'cart' => "carts/{$cart->getId()}",
            'product' => "products/{$product->getId()}",
            'quantity' => 1
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/LineItem',
            '@type' => 'LineItem',
            'product' => "/products/{$product->getId()}",
            'quantity' => 1
        ]);
    }

    public function testLineItemLimit(): void
    {
        /** @var Cart */
        $cart = $this->getAnyEntity(Cart::class);
        self::assertInstanceOf(Cart::class, $cart);

        /** @var Product[] */
        $products = $this->getAllEntities(Product::class);
        self::assertGreaterThanOrEqual(5, \count($products));

        for($i = 0; $i < 3; $i++) {
            self::assertInstanceOf(Product::class, $products[$i]);
            $this->client->request('POST', "/carts/line_items",['json' => [
                'cart' => "carts/{$cart->getId()}",
                'product' => "products/{$products[$i]->getId()}",
                'quantity' => 1
            ]]);

            $this->assertResponseStatusCodeSame(4 === $i? 422 : 201);
        }
    }

    public function testLineItemQuantityLimit(): void
    {
        /** @var $cart Cart */
        $cart = $this->getAnyEntity(Cart::class);
        self::assertInstanceOf(Cart::class, $cart);

        /** @var $product Product */
        $product = $this->getAnyEntity(Product::class);
        self::assertInstanceOf(Product::class, $product);

        $this->client->request('POST', "/carts/line_items",['json' => [
            'cart' => "carts/{$cart->getId()}",
            'product' => "products/{$product->getId()}",
            'quantity' => 11
        ]]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testLineItemDisallowDuplicates(): void
    {
        /** @var $cart Cart */
        $cart = $this->getAnyEntity(Cart::class);
        self::assertInstanceOf(Cart::class, $cart);

        /** @var $product Product */
        $product = $this->getAnyEntity(Product::class);
        self::assertInstanceOf(Product::class, $product);

        for($i = 1; $i <= 3; $i++){
            $this->client->request('POST', "/carts/line_items",['json' => [
                'cart' => "carts/{$cart->getId()}",
                'product' => "products/{$product->getId()}",
                'quantity' => 10
            ]]);
            $this->assertResponseStatusCodeSame(3 === $i? 422 : 201);
        }
        
    }
}
