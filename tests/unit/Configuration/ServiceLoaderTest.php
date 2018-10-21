<?php

namespace Wonderland\Container\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Wonderland\Container\Configuration\Parser\YamlParser;
use Wonderland\Container\Configuration\ServiceLoader;
use Wonderland\Container\Exception\InvalidConfigFormatException;
use Wonderland\Container\Service\ServiceDefinition;

/**
 * Class ServiceLoader
 * @package Wonderland\Container\Tests\Configuration
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ServiceLoaderTest extends TestCase
{
	/** @var ServiceLoader */
	private $loaderYml;

	public function setUp()
	{
		$this->loaderYml = new ServiceLoader(new YamlParser());
	}

	/**
	 * @return array
	 */
	public function ymlProvider()
	{
		return [
			[__DIR__ . '/data/yml/test-empty.yml'],
			[__DIR__ . '/data/yml/test-full.yml']
		];
	}

	/**
	 * @dataProvider ymlProvider
	 * @param string $testFile
	 * @throws \Wonderland\Container\Exception\InvalidConfigFormatException
	 */
	public function test_loadFile($testFile)
	{
		$services = $this->loaderYml->loadFile($testFile);
		$this->assertInternalType('array', $services);
		foreach ($services as $service) {
			$this->assertInstanceOf(ServiceDefinition::class, $service);
		}
	}

	/**
	 * @return array
	 */
	public function ymlErrorProvider()
	{
		return [
			[__DIR__ . '/data/yml-error/empty.yml'],
			[__DIR__ . '/data/yml-error/err1.yml'],
			[__DIR__ . '/data/yml-error/err2.yml'],
			[__DIR__ . '/data/yml-error/err3.yml'],
			[__DIR__ . '/data/yml-error/err4.yml'],
			[__DIR__ . '/data/yml-error/err5.yml'],
			[__DIR__ . '/data/yml-error/err6.yml'],
			[__DIR__ . '/data/yml-error/err7.yml'],
			[__DIR__ . '/data/yml-error/err8.yml']
		];
	}

	/**
	 * @dataProvider ymlErrorProvider
	 * @param string $testFile
	 * @throws \Wonderland\Container\Exception\InvalidConfigFormatException
	 */
	public function test_loadFileError($testFile)
	{
		$this->expectException(InvalidConfigFormatException::class);
		$this->loaderYml->loadFile($testFile);
	}

	/**
	 * @throws InvalidConfigFormatException
	 */
	public function test_loadDirectory()
	{
		$services = $this->loaderYml->loadDirectory(__DIR__ . '/data/yml');
		$this->assertInternalType('array', $services);
		foreach ($services as $service) {
			$this->assertInstanceOf(ServiceDefinition::class, $service);
		}
	}

}
