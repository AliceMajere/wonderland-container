<?php

namespace Wonderland\Container\Service;

/**
 * Interface ServiceInstanceInterface
 * @package Wonderland\Container\Container\Service
 * @author Alice Praud <alice.majere@gmail.com>
 */
interface InstanceDefinitionInterface
{

	/** @return string */
	public function getServiceName();

	/** @return mixed */
	public function getInstance();

}
