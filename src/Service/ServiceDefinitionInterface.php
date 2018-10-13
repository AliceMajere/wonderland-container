<?php

namespace Wonderland\Container\Service;

/**
 * Interface ServiceDefinitionInterface
 * @package Wonderland\Container\Container\Service
 * @author Alice Praud <alice.majere@gmail.com>
 */
interface ServiceDefinitionInterface
{

	/** @return string */
	public function getServiceName();

	/** @return string */
	public function getClass();

	/** @return array */
	public function getConstructArgs();

	/** @return array */
	public function getCalls();

}
