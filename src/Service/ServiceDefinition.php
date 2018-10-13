<?php

namespace Wonderland\Container\Service;

/**
 * Class Definition
 * @package Wonderland\Container\Container\Service
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ServiceDefinition implements ServiceDefinitionInterface
{
	/** @var string */
	private $serviceName;

	/** @var string */
	private $class;

	/** @var array */
	private $constructArgs;

	/** @var array */
	private $calls;

	/**
	 * RepositoryFactory constructor.
	 *
	 * @param string $serviceName
	 * @param string $class
	 * @param array $args
	 * @param array $calls
	 */
	public function __construct(string $serviceName, string $class, array $args = [], array $calls = [])
	{
		$this->serviceName = $serviceName;
		$this->class = $class;
		$this->constructArgs = $args;
		$this->calls = $calls;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/** @return string */
	public function getServiceName()
	{
		return $this->serviceName;
	}

	/**
	 * @return array
	 */
	public function getConstructArgs()
	{
		return $this->constructArgs;
	}

	/**
	 * @return array
	 */
	public function getCalls()
	{
		return $this->calls;
	}

}
