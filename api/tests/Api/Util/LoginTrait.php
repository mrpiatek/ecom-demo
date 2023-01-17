<?php

namespace App\Tests\Api\Util;

use Symfony\Contracts\HttpClient\HttpClientInterface;

trait LoginTrait
{
    protected HttpClientInterface $client;

    protected function loginAs($username, $password): void
    {
        $token = $this->getToken($username, $password);

        $this->client = static::createClient([], ['headers' => [
            'Authorization' => 'Bearer '.$token,
            'Content-Type' => 'application/ld+json',
            'Accept' => 'application/ld+json',
            ]
        ]);
    }

    protected function getToken($username, $password): string
    {
        $response = static::createClient()->request('POST', '/auth', ['json' => [
            'username' => $username,
            'password' => $password,
        ]]);

        $this->assertResponseIsSuccessful();
        $data = \json_decode($response->getContent());

        return $data->token;
    }
}