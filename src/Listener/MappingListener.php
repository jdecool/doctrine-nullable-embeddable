<?php

namespace JDecool\DoctrineNullableEmbeddable\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbeddable;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbedded;
use JDecool\DoctrineNullableEmbeddable\Mapping\EmbeddedMapping;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        $classMetadata = $args->getClassMetadata();

        foreach ($classMetadata->embeddedClasses as $embeddedProperty => $embeddedMapping) {
            $mapping = new EmbeddedMapping($embeddedMapping);

            // check on embeddable class
            $embeddableReflectionClass = new \ReflectionClass($mapping->getClass());

            $nullableAttributes = $embeddableReflectionClass->getAttributes(NullableEmbeddable::class);
            if (!empty($nullableAttributes)) {
                $classMetadata->addEntityListener(Events::postLoad, NullableEmbeddableListener::class, 'onPostLoad');

                return;
            }

            // check on embedded property
            $embeddedProperty = $classMetadata->getReflectionClass()?->getProperty($embeddedProperty);
            if (null === $embeddedProperty) {
                throw new \LogicException();
            }

            $nullableAttributes = $embeddedProperty->getAttributes(NullableEmbedded::class);
            if (!empty($nullableAttributes)) {
                $classMetadata->addEntityListener(Events::postLoad, NullableEmbeddableListener::class, 'onPostLoad');

                return;
            }
        }
    }
}
