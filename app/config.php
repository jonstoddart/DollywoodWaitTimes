<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Yosymfony\Toml\Toml;

require_once 'vendor/autoload.php';

$configFile = Toml::Parse(__DIR__ . '/config.toml');

$config = [
    'environment' => $configFile['app']['environment'],
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => $configFile['database']['host'],
        'user' => $configFile['database']['username'],
        'password' => $configFile['database']['password'],
        'dbname' => $configFile['database']['database'],
        'port' => $configFile['database']['port']
    ]
];

$entityConfig = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/entities'], $config['environment'] == 'development');
$entityManager = EntityManager::create($config['database'], $entityConfig);

$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
$twig = new Twig_Environment($loader);
