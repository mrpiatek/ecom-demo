<?php

namespace App\Tests\Api\Integration;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Api\Util\LoginTrait;

class AddProductToCartTest extends ApiTestCase
{
    use LoginTrait;

    public function testCreateLineItem(): void
    {
        $this->loginAs('user1', 'user1');
        $cartId = $this->client->request('POST', '/carts',['json' => []])->toArray()['id'] ?? null;
        
        self::assertNotNull($cartId);

        $productId = $this->client->request('GET', '/products',['json' => [
        ]])->toArray()['hydra:member'][0]['id'] ?? null;
        self::assertNotNull($productId);

        $this->client->request('POST', "/carts/line_items",['json' => [
            'cart' => "carts/{$cartId}",
            'product' => "products/{$productId}",
            'quantity' => 1
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/LineItem',
            '@type' => 'LineItem',
            'product' => "/products/{$productId}",
            'quantity' => 1
        ]);
    }
}
