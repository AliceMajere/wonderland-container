<?php

namespace Wonderland\Container\Service;

/**
 * Class InstanceDefinition
 * @package Wonderland\Container\Container\Service
 * @author Alice Praud <alice.majere@gmail.com>
 */
class InstanceDefinition implements ServiceInstanceInterface
{
    /** @var string */
    private $serviceName;

    /** @var mixed */
    private $instance;

    public function __construct(string $serviceName, $instance)
    {
        $this->serviceName = $serviceName;
        $this->instance = $instance;
    }

    /** @return string */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /** @return mixed */
    public function getInstance()
    {
        return $this->instance;
    }

}
