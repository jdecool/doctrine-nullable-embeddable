<?php

namespace JDecool\DoctrineNullableEmbeddable\Mapping;

use Doctrine\ORM\Mapping\EmbeddedClassMapping;

class EmbeddedMapping
{
    public function __construct(
        private readonly EmbeddedClassMapping|array $mapping,
    ) {
    }

    /**
     * @return class-string
     */
    public function getClass(): string
    {
        if (is_array($this->mapping)) { // doctrine/orm < 3
            return $this->mapping['class'];
        }

        return $this->mapping->class;
    }
}
