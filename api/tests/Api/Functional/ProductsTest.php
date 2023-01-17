<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Tests\Api\Util\LoginTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProductsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use LoginTrait;

    protected function setup(): void{
        $this->loginAs('admin', 'admin');
    }

    public function testCreateProduct(): void
    {
        $this->client->request('POST', '/products',['json' => [
            'name' => 'Red Dead Redemption 3',
            'price' => 19999
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Product',
            '@type' => 'Product',
            'name' => 'Red Dead Redemption 3',
            'price' => 19999
        ]);
    }

    public function testDeleteProduct(): void
    {
        $iri = $this->findIriBy(Product::class, ['name' => 'Red Dead Redemption 2']);

        $this->client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Product::class)->findOneBy(['name' => 'Red Dead Redemption 2'])
        );
    }

    public function testListProducts(): void
    {
        $response = $this->client->request('GET', '/products');
        $this->assertResponseStatusCodeSame(200);

        $this->assertCount(3, $response->toArray()['hydra:member'] ?? []);
        $this->assertGreaterThan(3, $response->toArray()['hydra:totalItems'] ?? 0);
    }
}
