<?php

namespace JDecool\DoctrineNullableEmbeddable\Listener;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbeddable;
use JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbedded;
use JDecool\DoctrineNullableEmbeddable\Mapping\EmbeddedMapping;

class NullableEmbeddableListener
{
    public function onPostLoad(object $entity, LifecycleEventArgs $args): void
    {
        $entityMetadata = $args->getObjectManager()->getClassMetadata($entity::class);
        if (!$entityMetadata instanceof ClassMetadata || empty($entityMetadata->embeddedClasses)) {
            return;
        }

        $entityReflectionClass = $entityMetadata->getReflectionClass();
        if (null === $entityReflectionClass) {
            return;
        }

        foreach ($entityMetadata->embeddedClasses as $embeddedProperty => $embeddedMapping) {
            $mapping = new EmbeddedMapping($embeddedMapping);

            if (!$this->isNullableEmbeddable($mapping, $entityReflectionClass, $embeddedProperty)) {
                continue;
            }

            $reflectionProperty = $entityReflectionClass->getProperty($embeddedProperty);
            if ($this->hasFilledProperty($entity, $reflectionProperty, $mapping->getClass())) {
                continue;
            }

            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($entity, null);
        }
    }

    public function isNullableEmbeddable(EmbeddedMapping $mapping, \ReflectionClass $entityReflectionClass, string $embeddedProperty): bool
    {
        // check on embeddable class
        $embeddableReflectionClass = new \ReflectionClass($mapping->getClass());

        $nullableAttributes = $embeddableReflectionClass->getAttributes(NullableEmbeddable::class);
        if (!empty($nullableAttributes)) {
            return true;
        }

        // check on embedded property
        $embeddedProperty = $entityReflectionClass->getProperty($embeddedProperty);

        $nullableAttributes = $embeddedProperty->getAttributes(NullableEmbedded::class);
        if (!empty($nullableAttributes)) {
            return true;
        }

        return false;
    }

    /**
     * @param class-string $embedableClass
     */
    private function hasFilledProperty(object $entity, \ReflectionProperty $property, string $embedableClass): bool
    {
        $value = $property->getValue($entity);
        if (!is_object($value)) {
            throw new \LogicException();
        }

        $reflectionClass = new \ReflectionClass($embedableClass);
        foreach ($reflectionClass->getProperties() as $classProperty) {
            if (null !== $classProperty->getValue($value)) {
                return true;
            }
        }

        return false;
    }
}
