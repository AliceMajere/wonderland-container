<?php

if ( !is_readable( __DIR__ . '/../../vendor/autoload.php' ) ) {
	die( 'You need to install this package with Composer before you can run the examples' );
}

require_once __DIR__ . '/../../vendor/autoload.php';

use Wonderland\Container\Configuration\Parser\YamlParser;

// Load by directory
$container = new \Wonderland\Container\ServiceContainer();
$serviceLoader = new \Wonderland\Container\Configuration\ServiceLoader(new YamlParser());
$container->loadServices($serviceLoader->loadDirectory(__DIR__ . '/Resource/'));

// Load file by file
$container2 = new \Wonderland\Container\ServiceContainer();
$serviceLoader = new \Wonderland\Container\Configuration\ServiceLoader(new YamlParser());
$container2->loadServices($serviceLoader->loadFile(__DIR__ . '/Resource/test.yml'));
$container2->loadServices($serviceLoader->loadFile(__DIR__ . '/Resource/test2.yml'));

