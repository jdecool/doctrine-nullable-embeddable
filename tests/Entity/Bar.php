<?php

namespace JDecool\DoctrineNullableEmbeddable\Tests\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbeddable;

#[ORM\Embeddable]
#[NullableEmbeddable]
class Bar
{
    public function __construct(
        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $value = null,
    ) {
    }
}
