<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

// Crear el directori var/ si no existeix
$dbPath = __DIR__ . '/../var/database.sqlite';
if (!is_dir(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0777, true);
}

// Directori de proxies
$proxyDir = __DIR__ . '/../var/proxies';
if (!is_dir($proxyDir)) {
    mkdir($proxyDir, 0777, true);
}

$config = new Configuration();

// Driver d'atributs PHP 8
$config->setMetadataDriverImpl(
    new AttributeDriver([__DIR__ . '/../src/Domain'])
);

// Si cap dels dos, Doctrine 2.x gestiona la caché internament sense error

$config->setProxyDir($proxyDir);
$config->setProxyNamespace('DoctrineProxies');
$config->setAutoGenerateProxyClasses(true);

$connection = [
    'driver' => 'pdo_sqlite',
    'path'   => $dbPath,
];

return EntityManager::create($connection, $config);
