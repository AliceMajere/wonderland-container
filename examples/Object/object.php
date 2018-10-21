<?php

if ( !is_readable( __DIR__ . '/../../vendor/autoload.php' ) ) {
	die( 'You need to install this package with Composer before you can run the examples' );
}

require_once __DIR__ . '/../../vendor/autoload.php';

$container = new \Wonderland\Container\ServiceContainer();

// Create a new definition with another service as an argument
$definition = new \Wonderland\Container\Service\ServiceDefinition(
	'service.name',
	\Wonderland\Container\Example\Object\SampleClass::class,
	['param1', '@service.name2']
);

// Create a new definition with a method call taking two other services as arguments
$definition2 = new \Wonderland\Container\Service\ServiceDefinition(
	'service.name2',
	\Wonderland\Container\Example\Object\SampleClass::class,
	['param2', 'param3'],
	['callMethod' => ['test', '@instance.name']]
);

// Create a new instance definition
$instance = new \Wonderland\Container\Service\InstanceDefinition(
	'instance.name',
	new \DateTime()
);

// Registering them all
$container->addService($definition);
$container->addService($definition2);
$container->addServiceInstance($instance);
