<?php

namespace Wonderland\Container\Tests\Configuration\Parser;

use PHPUnit\Framework\TestCase;
use Wonderland\Container\Configuration\Parser\YamlParser;
use Wonderland\Container\Exception\InvalidResourceException;
use Wonderland\Container\Exception\ResourceNotFoundException;

/**
 * Class YamlParserTest
 * @package Wonderland\Container\Tests\Configuration\Parser
 * @author Alice Praud <alice.majere@gmail.com>
 */
class YamlParserTest extends TestCase
{
	/** @var YamlParser */
	private $parser;

	public function setUp()
	{
		$this->parser = new YamlParser();
	}

	/**
	 * @throws \Wonderland\Container\Exception\InvalidResourceException
	 * @throws \Wonderland\Container\Exception\ResourceNotFoundException
	 */
	public function test_load()
	{
		$this->assertInternalType('array', $this->parser->loadFile(__DIR__ . '/../data/yml/test-full.yml'));
		$this->assertInternalType('array', $this->parser->loadDirectory(__DIR__ . '/../data/yml/'));
	}

	/**
	 * @return array
	 */
	public function loadErrorNotFoundProvider()
	{
		return [
			['dontexists']
		];
	}

	/**
	 * @dataProvider loadErrorNotFoundProvider
	 * @param string $filePath
	 * @throws \Wonderland\Container\Exception\InvalidResourceException
	 * @throws \Wonderland\Container\Exception\ResourceNotFoundException
	 */
	public function test_loadFileErrorNotFound($filePath)
	{
		$this->expectException(ResourceNotFoundException::class);
		$this->parser->loadFile($filePath);
	}

	/**
	 * @dataProvider loadErrorNotFoundProvider
	 * @param string $filePath
	 * @throws \Wonderland\Container\Exception\InvalidResourceException
	 * @throws \Wonderland\Container\Exception\ResourceNotFoundException
	 */
	public function test_loadDirErrorNotFound($filePath)
	{
		$this->expectException(ResourceNotFoundException::class);
		$this->parser->loadDirectory($filePath);
	}

	/**
	 * @throws \Wonderland\Container\Exception\InvalidResourceException
	 * @throws \Wonderland\Container\Exception\ResourceNotFoundException
	 */
	public function test_loadFileErrorInvalid()
	{
		$this->expectException(InvalidResourceException::class);
		$this->parser->loadFile(__DIR__ . '/../data/yml');
	}

	/**
	 * @throws \Wonderland\Container\Exception\InvalidResourceException
	 * @throws \Wonderland\Container\Exception\ResourceNotFoundException
	 */
	public function test_loadDirErrorInvalid()
	{
		$this->expectException(InvalidResourceException::class);
		$this->parser->loadDirectory(__DIR__ . '/../data/yml/test-full.yml');
	}

}
