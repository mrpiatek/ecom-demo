<?php

namespace App\Tests\Api\Util;

trait DoctrineHelperTrait
{
    protected function getAnyEntity(string $class)
    {
        return static::getContainer()->get('doctrine')->getRepository($class)->findOneBy([]);
    }

    protected function getAllEntities(string $class): array
    {
        return static::getContainer()->get('doctrine')->getRepository($class)->findAll();
    }
}