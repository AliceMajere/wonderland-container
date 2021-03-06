<?php

namespace Wonderland\Container\Service;

/**
 * @deprecated use InstanceDefinitionInterface instead
 * Interface ServiceInstanceInterface
 * @package Wonderland\Container\Container\Service
 * @author Alice Praud <alice.majere@gmail.com>
 */
interface ServiceInstanceInterface
{

	/** @return string */
	public function getServiceName();

	/** @return mixed */
	public function getInstance();

}
