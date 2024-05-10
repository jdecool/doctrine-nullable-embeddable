<?php

namespace JDecool\DoctrineNullableEmbeddable\Tests;

use JDecool\DoctrineNullableEmbeddable\Tests\Entity\Foo;

class NullableEmbeddableTest extends TestCase
{
    public function testNullableEmbeddable(): void
    {
        $entity = $this->entityManager->getRepository(Foo::class)->find(1);

        static::assertNull($entity->bar);
    }

    public function testNullableEmbedded(): void
    {
        $entity = $this->entityManager->getRepository(Foo::class)->find(1);

        static::assertNull($entity->bar);
    }
}
