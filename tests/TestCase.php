<?php

namespace JDecool\DoctrineNullableEmbeddable\Tests;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use JDecool\DoctrineNullableEmbeddable\Listener\MappingListener;
use JDecool\DoctrineNullableEmbeddable\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase as BaseTestCaseAlias;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

abstract class TestCase extends BaseTestCaseAlias
{
    protected Configuration $configuration;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = $this->createConfiguration();

        if (!method_exists(EntityManager::class, 'create')) {
            $this->entityManager = new EntityManager(
                DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $this->configuration),
                $this->configuration,
            );
        } else {
            $this->entityManager = EntityManager::create(['driver' => 'pdo_sqlite', 'memory' => true], $this->configuration);
        }

        $eventManager = $this->entityManager->getEventManager();
        $eventManager->addEventListener(['loadClassMetadata'], new MappingListener());

        $this->createDatabase();
        $this->loadFixtures();
    }

    private function createConfiguration(): Configuration
    {
        $configuration = new Configuration();
        $configuration->setProxyDir(__DIR__.'/Proxies');
        $configuration->setProxyNamespace('JDecool\DoctrineNullableEmbeddable\Tests\Proxies');
        $configuration->setAutoGenerateProxyClasses(true);

        if (method_exists($configuration, 'setMetadataCache')) {
            // doctrine/orm v3
            $configuration->setMetadataCache(new ArrayAdapter());
        } else {
            // doctrine/orm v2
            $configuration->setMetadataCacheImpl(new ArrayCache());
        }

        if (method_exists($configuration, 'setQueryCache')) {
            // doctrine/orm v3
            $configuration->setQueryCache(new ArrayAdapter());
        } else {
            // doctrine/orm v2
            $configuration->setQueryCacheImpl(new ArrayCache());
        }

        $configuration->setMetadataDriverImpl(new AttributeDriver([__DIR__.'/Entity']));

        return $configuration;
    }

    private function createDatabase(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Foo::class),
        ]);
    }

    private function loadFixtures(): void
    {
        $foo = new Foo();
        $foo->id = 1;
        $this->entityManager->persist($foo);

        $this->entityManager->flush();

        $this->entityManager->clear();
    }
}
