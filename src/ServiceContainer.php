<?php

namespace Wonderland\Container;

use Wonderland\Container\Service\InstanceDefinitionInterface;
use Wonderland\Container\Service\ServiceDefinitionInterface;
use Wonderland\Container\Exception\DuplicatedServiceException;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceContainer
 * @package Wonderland\Container\Container
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ServiceContainer implements ContainerInterface
{
	private const SERVICE_PREFIX = '@';

	/** @var ServiceDefinitionInterface[] */
	private $services;

	/** @var array */
	private $serviceInstances;

	/**
	 * ServiceContainer constructor.
	 */
	public function __construct()
	{
		$this->services = [];
		$this->serviceInstances = [];
	}

	/**
	 * @param ServiceDefinitionInterface $serviceDefinition
	 * @return ServiceContainer
	 * @throws DuplicatedServiceException
	 */
	public function addService(ServiceDefinitionInterface $serviceDefinition)
	{
		if (true === $this->has($serviceDefinition->getServiceName())) {
			throw new DuplicatedServiceException(
				'The service "' . $serviceDefinition->getServiceName() . '" is already registered in the container'
			);
		}

		$this->services[$serviceDefinition->getServiceName()] = $serviceDefinition;

		return $this;
	}

	/**
	 * @param ServiceDefinitionInterface[] $definitionList
	 * @return ServiceContainer
	 * @throws DuplicatedServiceException
	 */
	public function loadServices(array $definitionList)
	{
		foreach ($definitionList as $definition) {
			$this->addService($definition);
		}

		return $this;
	}

	/**
	 * @param InstanceDefinitionInterface $definition
	 * @return ServiceContainer
	 * @throws DuplicatedServiceException
	 */
	public function addServiceInstance(InstanceDefinitionInterface $definition)
	{
		if (true === $this->has($definition->getServiceName())) {
			throw new DuplicatedServiceException(
				'The service "' . $definition->getServiceName() . '" is already registered in the container'
			);
		}

		$this->serviceInstances[$definition->getServiceName()] = $definition->getInstance();

		return $this;
	}

	/**
	 * @param string $index
	 * @param bool $new
	 * @return mixed|null
	 */
	public function get($index, $new = false)
	{
		$index = $this->cleanServiceName($index);

		// if service is shared true and already exists we return it
		if (isset($this->serviceInstances[$index]) && false === $new) {
			return $this->serviceInstances[$index];
		}

		// is service not defined return null
		if (!isset($this->services[$index])) {
			return null;
		}

		// we create a new instance
		$instance = $this->create($this->services[$index]);

		// if shared true we set it in the current instances
		if (false === $new) {
			$this->serviceInstances[$index] = $instance;
		}

		return $instance;
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $index Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($index)
	{
		$index = $this->cleanServiceName($index);

		// if service is shared true and already exists we return it
		if (isset($this->serviceInstances[$index])) {
			return true;
		}

		// is service not defined return null
		if (isset($this->services[$index])) {
			return true;
		}

		return false;
	}

	/**
	 * @param ServiceDefinitionInterface $serviceDefinition
	 * @return mixed
	 */
	private function create(ServiceDefinitionInterface $serviceDefinition)
	{
		// we get the class name in a var for the new later
		$newClass = $serviceDefinition->getClass();

		// we get the construct args
		$args = $this->checkArgsServices($serviceDefinition->getConstructArgs());
		$instance = new $newClass(...$args);

		// we call the injection methods after we instance the object
		$calls = $this->checkCallsServices($serviceDefinition->getCalls());
		foreach ($calls as $call => $params) {
			call_user_func_array([$instance, $call], $params);
		}

		// we create the new instance
		return $instance;
	}

	/**
	 * @param array $args
	 * @return array
	 */
	private function checkArgsServices(array $args)
	{
		// we check if any of the construct args are services themself and we create them
		foreach ($args as $k => $arg) {
			if (false === $this->isServiceName($arg)) {
				continue;
			}

			if (true === $this->has($arg)) {
				$args[$k] = $this->get($arg);
			}
		}

		return $args;
	}

	/**
	 * @param array $calls
	 * @return array
	 */
	private function checkCallsServices(array $calls)
	{
		// we check if any of the calls args are services themself and we create them
		foreach ($calls as $i => $callArgs) {
			foreach ($callArgs as $k => $arg) {
				if (false === $this->isServiceName($arg)) {
					continue;
				}

				if (true === $this->has($arg)) {
					$calls[$i][$k] = $this->get($arg);
				}
			}
		}

		return $calls;
	}

	/**
	 * @param string $str
	 * @return bool
	 */
	private function isServiceName(string $str)
	{
		return self::SERVICE_PREFIX === substr($str, 0, 1);
	}

	/**
	 * @param string $str
	 * @return string
	 */
	private function cleanServiceName(string $str)
	{
		return trim($str, self::SERVICE_PREFIX);
	}

}
