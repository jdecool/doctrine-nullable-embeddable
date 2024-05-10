<?php

namespace JDecool\DoctrineNullableEmbeddable\Tests\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbedded;

#[ORM\Entity]
class Foo
{
    #[
        ORM\Column(type: Types::INTEGER),
        ORM\Id,
        ORM\GeneratedValue(strategy: 'AUTO'),
    ]
    public int $id;

    #[ORM\Embedded(class: Bar::class)]
    public ?Bar $bar = null;

    #[ORM\Embedded(class: Baz::class)]
    #[NullableEmbedded]
    public ?Baz $baz = null;
}
