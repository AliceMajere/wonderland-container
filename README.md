[![Build Status](https://travis-ci.org/AliceMajere/wonderland-container.svg?branch=master)](https://travis-ci.org/AliceMajere/wonderland-container) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AliceMajere/wonderland-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AliceMajere/wonderland-container/?branch=master) 
[![Code Coverage](https://scrutinizer-ci.com/g/AliceMajere/wonderland-container/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AliceMajere/wonderland-container/?branch=master) 
[![Code Intelligence Status](https://scrutinizer-ci.com/g/AliceMajere/wonderland-container/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Latest Stable Version](https://poser.pugx.org/alicemajere/wonderland-container/v/stable.png)](https://packagist.org/packages/alicemajere/wonderland-container)
[![Total Downloads](https://poser.pugx.org/alicemajere/wonderland-container/downloads)](https://packagist.org/packages/alicemajere/wonderland-container)


# Wonderland Container

A small simple service container to include in projects

## Recent Update

The project just got released on packagist with release 1.0.0. 

## Installation

```
composer require alicemajere/wonderland-container
```

## Usage

### Initializing the container

Create a new instance of the container
```php
$container = new Wonderland\Container\Container();
```

Create a new service definition and register it in the container with the following parameters
- [STRING] service name
- [STRING] class name
- [ARRAY] constructor parameters
- [ARRAY] methods calls with their arguments. The index is the method name, the value is the array of the method 
parameters
```php
// creating a service definition
$definition = new Wonderland\Container\Service\ServiceDefinition(
    'service.name',
    MyClass::class,
    ['args1', 'service.name2', ['array_args']],
    ['methodCall1' => ['args1', 'service.name3']]
);

// register the definition into the container
$container->addService($definition);
```

You can also create a new service by directly injecting an object instance
```php
$instance = new MyClass();
$serviceInstance = new Wonderland\Container\Service\InstanceDefinition(
    'service.name2',
    $instance
);

// register the instance definition into the container
$container->addServiceInstance(serviceInstance);

```

### Using the container

To check if a service exists into the container
```php
if ($container->has('service.name')) {
    // code here
}
```

To retrieve the service instance
```php
// retrieve the instance. Will create a new one if not created yet. Shared by default
$instance = $container->get('service.name');

// retrieve a new instance of the service. Setting the second parameters to true will not retrieve the shared service
 instance
$newInstance = $container->get('service.name', true);

// retrieve the instance of the instance service definition. Setting the second parameter to true will do nothing if 
// the service is not a definition service but a instance definition service; only the shared instance will be retrieved
$instance = $container->get('service.name2');
```

## Prerequisites

PHP >= 7.2

## Getting help

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

## Authors

* **Alice Praud** - *Initial work* - [AliceMajere](https://github.com/AliceMajere/wonderland-container/)
