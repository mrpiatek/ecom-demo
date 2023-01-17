<?php

namespace App\Tests\Api\Integration;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Tests\Api\Util\LoginTrait;

class UserCartTest extends ApiTestCase
{
    use LoginTrait;

    public function testAccessOtherUsersCarts(): void
    {
        $this->loginAs('user1', 'user1');

        $cartId = $this->client->request('POST', '/carts',['json' => []])->toArray()['id'] ?? null;
        self::assertNotNull($cartId);
        $this->assertResponseStatusCodeSame(201);

        $product = static::getContainer()->get('doctrine')->getRepository(Product::class)->findOneBy([]);

        $addToCartResponse = $this->client->request('POST', "/carts/line_items",['json' => [
            'cart' => "carts/{$cartId}",
            'product' => "products/{$product->getId()}",
            'quantity' => 1
        ]]);
        
        $lineItemId = $addToCartResponse->toArray()['id'] ?? null;
        self::assertNotNull($lineItemId);
        $this->assertResponseStatusCodeSame(201);

        $this->loginAs('user2', 'user2');

        $this->client->request('GET', "/carts/{$cartId}");
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', "/carts/line_items",['json' => [
            'cart' => "carts/{$cartId}",
            'product' => "products/{$product->getId()}",
            'quantity' => 1
        ]]);
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('DELETE', "/carts/line_items/{$lineItemId}");
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnauthenticatedUser(): void
    {
        static::createClient()->request('GET', "/carts");
        $this->assertResponseStatusCodeSame(401);

        static::createClient()->request('POST', "/carts");
        $this->assertResponseStatusCodeSame(401);
    }
}
