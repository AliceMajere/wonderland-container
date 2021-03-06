<?php

namespace Wonderland\Container\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wonderland\Container\Exception\DuplicatedServiceException;
use Wonderland\Container\Service\InstanceDefinition;
use Wonderland\Container\Service\ServiceDefinition;
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
	private const SERVICE_NAME = 'service.name';
	private const DEFAULT_SERVICE_NANE = 'default.service.name';

	/** @var ServiceContainer|MockObject */
	private $container;

	/** @var ServiceContainer */
	private $realContainer;

	/**
	 * @throws DuplicatedServiceException
	 */
	protected function setUp()
	{
		$this->container = $this->getMockBuilder(ServiceContainer::class)->getMock();
		$this->realContainer = new ServiceContainer();
		$this->realContainer->addService(new ServiceDefinition(self::DEFAULT_SERVICE_NANE, \DateTime::class));
	}

	public function testConstructor()
	{
		$this->assertAttributeEquals([], 'serviceInstances', $this->container);
	}

	/**
	 * @covers \Wonderland\Container\ServiceContainer::addService
	 * @throws DuplicatedServiceException
	 */
	public function test_AddService()
	{
		$definition = $this->getMockBuilder(ServiceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME, \DateTime::class])
			->getMock();
		;

		$definition->expects($this->exactly(2))
			->method('getServiceName')
			->willReturn(self::SERVICE_NAME);

		$this->assertSame($this->realContainer, $this->realContainer->addService($definition));
	}

	/**
	 * @throws DuplicatedServiceException
	 */
	public function test_AddServiceException()
	{
		$this->expectException(DuplicatedServiceException::class);

		$definition = $this->getMockBuilder(ServiceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME, \DateTime::class])
			->getMock();
		;

		$definition->expects($this->exactly(4))
			->method('getServiceName')
			->willReturn(self::SERVICE_NAME);

		$this->realContainer->addService($definition);
		$this->realContainer->addService($definition);
	}

	public function test_loadServices()
	{
		$definition = $this->getMockBuilder(ServiceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME . '10', \DateTime::class])
			->getMock();
		;
		$definition2 = $this->getMockBuilder(ServiceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME . '20', \DateTime::class])
			->getMock();
		;

		$list = [
			$definition,
			$definition2
		];

		$definition->expects($this->exactly(2))
			->method('getServiceName')
			->willReturn(self::SERVICE_NAME . '10');
		$definition2->expects($this->exactly(2))
			->method('getServiceName')
			->willReturn(self::SERVICE_NAME . '20');

		$this->realContainer->loadServices($list);
	}

	/**
	 * @throws DuplicatedServiceException
	 */
	public function test_AddServiceInstance()
	{
		$definition = $this->getMockBuilder(InstanceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME, new \DateTime()])
			->getMock();

		$definition->expects($this->atLeast(2))
			->method('getServiceName')
			->willReturn(self::SERVICE_NAME);

		$this->container->expects($this->once())
			->method('addServiceInstance')
			->willReturn($this->container);

		$this->assertSame($this->container, $this->container->addServiceInstance($definition));
		$this->assertSame($this->realContainer, $this->realContainer->addServiceInstance($definition));
	}

	/**
	 * @throws DuplicatedServiceException
	 */
	public function test_AddServiceInstanceException()
	{
		$this->expectException(DuplicatedServiceException::class);

		$definition = $this->getMockBuilder(InstanceDefinition::class)
			->setConstructorArgs([self::SERVICE_NAME, new \DateTime()])
			->getMock();

		$definition->expects($this->exactly(2))
			->method('getServiceName')
			->willReturn(self::DEFAULT_SERVICE_NANE);

		$this->realContainer->addServiceInstance($definition);
	}

	/**
	 * @throws DuplicatedServiceException
	 */
	public function test_get()
	{
		$this->assertSame(null, $this->realContainer->get('test'));
		$this->assertSame(\DateTime::class, get_class($this->realContainer->get(self::DEFAULT_SERVICE_NANE)));
		$instance = $this->realContainer->get(self::DEFAULT_SERVICE_NANE);
		$this->assertSame(\DateTime::class, get_class($instance));
		$this->assertSame($instance, $this->realContainer->get(self::DEFAULT_SERVICE_NANE));

		$this->realContainer->addServiceInstance(
			new InstanceDefinition('call.format', 'd-m-Y')
		);
		$this->realContainer->addServiceInstance(
			new InstanceDefinition('call.date', '1970-01-01')
		);

		$this->realContainer->addService(
			new ServiceDefinition(
				'service.call',
				\DateTime::class,
				['@call.date'],
				['format' => ['@call.format']]
			)
		);

		$this->realContainer->addService(
			new ServiceDefinition(
				'service.call2',
				\DateTime::class,
				['1970-01-01'],
				['format' => ['Y-m-d']]
			)
		);

		$date = new \DateTime('1970-01-01');
		$this->assertSame($date->format('m-d-Y'), $this->realContainer->get('service.call')->format('m-d-Y'));
		$this->assertSame($date->format('m-d-Y'), $this->realContainer->get('service.call2')->format('m-d-Y'));
	}

	public function test_has()
	{
		$this->assertSame(false, $this->realContainer->has('fake'));
		$this->assertSame(true, $this->realContainer->has(self::DEFAULT_SERVICE_NANE));
		$this->assertSame(true, $this->realContainer->has(self::DEFAULT_SERVICE_NANE));

		$this->realContainer->addServiceInstance(new InstanceDefinition('instance.name', \DateTime::class));
		$this->assertSame(true, $this->realContainer->has('instance.name'));
	}

}
