<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Cart;
use App\Tests\Api\Util\LoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CartsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use LoginTrait;

    public function testCreateCart(): void
    {
        $this->loginAs('admin', 'admin');
        $response = $this->client->request('POST', '/carts', ['json'=>[]]);

        $cartId = $response->toArray()['id'] ?? null;
        self::assertNotNull($cartId);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Cart',
            '@type' => 'Cart',
        ]);

        $cartEntity = static::getContainer()->get('doctrine')->getRepository(Cart::class)->find($cartId);

        self::assertNotNull($cartEntity);
    }
}
