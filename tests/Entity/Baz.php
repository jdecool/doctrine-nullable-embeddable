<?php

namespace JDecool\DoctrineNullableEmbeddable\Tests\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Baz
{
    public function __construct(
        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $value1 = null,

        #[ORM\Column(type: Types::INTEGER, nullable: true)]
        public ?int $value2 = null,
    ) {
    }
}
