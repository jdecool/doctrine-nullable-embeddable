DoctrineNullableEmbeddable
==========================

[![Build Status](https://github.com/jdecool/doctrine-nullable-embeddable/actions/workflows/ci.yml/badge.svg)](https://actions-badge.atrox.dev/jdecool/doctrine-nullable-embeddable/goto?ref=main)
[![Latest Stable Version](https://poser.pugx.org/jdecool/doctrine-nullable-embeddable/v/stable.png)](https://packagist.org/packages/jdecool/doctrine-nullable-embeddable)

Implement nullable embeddables in Doctrine entities

## Install it

Install the extension using [Composer](https://getcomposer.org):

```bash
$ composer require jdecool/doctrine-nullable-embeddable
```

## Available attributes

* `JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbeddable`
* `JDecool\DoctrineNullableEmbeddable\Attribute\NullableEmbedded`

## Register the mapping listener

Nullable Doctrine embeddables can be automatically managed by reigster the `JDecool\DoctrineNullableEmbeddable\Listener\MappingListener` listener class.

```php
$configuration = new Configuration();
// ...

$entityManager = EntityManager::create(['driver' => 'pdo_sqlite', 'memory' => true], $configuration);

$eventManager = $entityManager->getEventManager();
$eventManager->addEventListener(['loadClassMetadata'], new MappingListener());
```

## Register entity listener manually

Or you can manually register the `JDecool\DoctrineNullableEmbeddable\Listener\NullableEmbeddableListener` on an entity.
