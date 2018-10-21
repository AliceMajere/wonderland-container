<?php

namespace Wonderland\Container\Configuration\Parser;

use Symfony\Component\Yaml\Yaml;
use Wonderland\Container\Exception\InvalidResourceException;
use Wonderland\Container\Exception\ResourceNotFoundException;

/**
 * Class Yaml
 * @package Wonderland\Container\Configuration\Parser
 * @author Alice Praud <alice.majere@gmail.com>
 */
class YamlParser implements ParserInterface
{

	/**
	 * @param string $filePath
	 * @return array
	 * @throws ResourceNotFoundException
	 * @throws InvalidResourceException
	 */
	public function loadFile(string $filePath): array
	{
		if (false === file_exists($filePath)) {
			throw new ResourceNotFoundException('The file "' . $filePath . '" does not exists');
		}

		if (false === is_file($filePath)) {
			throw new InvalidResourceException('The resource "' . $filePath . '" is not a file');
		}

		$parse = Yaml::parseFile($filePath);

		return null === $parse ? [] : $parse;
	}

	/**
	 * @param string $directorPath
	 * @return array
	 * @throws InvalidResourceException
	 * @throws ResourceNotFoundException
	 */
	public function loadDirectory(string $directorPath): array
	{
		if (false === file_exists($directorPath)) {
			throw new ResourceNotFoundException('The directory "' . $directorPath . '" does not exists');
		}

		if (false === is_dir($directorPath)) {
			throw new InvalidResourceException('The resource "' . $directorPath . '" is not a directory');
		}

		$config = [];

		$files = array_diff(scandir($directorPath), array('.', '..'));
		foreach ($files as $file) {
			if (false !== preg_match('/^.+(yml|yaml)$/i', $file)) {
				$config[$file] = Yaml::parseFile(realpath($directorPath) . DIRECTORY_SEPARATOR . $file);
			}
		}

		return $config;
	}

}
