<?php

namespace Wonderland\Container\Tests;

use PHPUnit\Framework\TestCase;
use Wonderland\Container\Exception\DuplicatedServiceException;
use Wonderland\Container\Service\ServiceInstanceInterface;
use Wonderland\Container\ServiceContainer;
use Wonderland\Container\Service\ServiceDefinitionInterface;

/**
 * Class ContainerTest
 * @package Wonderland\Container\Tests
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ServiceContainerTest extends TestCase
{
	public function test_addService()
	{
		$definition = $this->getMockBuilder(ServiceDefinitionInterface::class)->getMock();

		$definition->expects($this->once())
			->method('getServiceName')
			->willReturn('service.name');

		$this->assertSame('service.name', $definition->getServiceName());
	}

//	 * @expectedException DuplicatedServiceException
	/**
	 */
	public function test_addServiceInstance()
	{
		$instance = $this->getMockBuilder(ServiceInstanceInterface::class)->getMock();

		$instance->expects($this->once())
			->method('getServiceName')
			->willReturn('service.name');


		$this->assertSame('service.name', $instance->getServiceName());
	}

	public function test_addServiceInstance2()
	{
		$instance = $this->getMockBuilder(ServiceInstanceInterface::class)->getMock();

		$object = new \DateTime();
		$instance->expects($this->once())
			->method('getInstance')
			->willReturn($object);

		$this->assertSame($object, $instance->getInstance());
	}
}
