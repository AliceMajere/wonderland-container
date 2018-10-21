<?php

namespace Wonderland\Container\Configuration;

use Wonderland\Container\Configuration\Config\Fields;
use Wonderland\Container\Configuration\Parser\ParserInterface;
use Wonderland\Container\Configuration\Validator\ConfigurationValidator;
use Wonderland\Container\Exception\InvalidConfigFormatException;
use Wonderland\Container\Service\ServiceDefinition;

/**
 * Class ServiceLoader
 * @package Wonderland\Container\Configuration
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ServiceLoader
{
	/** @var ParserInterface  */
	private $parser;

	/** @var ConfigurationValidator  */
	private $validator;

	/**
	 * ServiceLoader constructor.
	 * @param ParserInterface $parser
	 */
	public function __construct(ParserInterface $parser)
	{
		$this->parser = $parser;
		$this->validator = new ConfigurationValidator();
	}

	/**
	 * @param string $filePath
	 * @return ServiceDefinition[]
	 * @throws InvalidConfigFormatException
	 */
	public function loadFile(string $filePath)
	{
		$definitionList = $this->parser->loadFile($filePath);

		return $this->loadDefinitions($definitionList);
	}

	/**
	 * @param string $directoryPath
	 * @return ServiceDefinition[]
	 * @throws InvalidConfigFormatException
	 */
	public function loadDirectory(string $directoryPath)
	{
		$definitionList = $this->parser->loadDirectory($directoryPath);

		$list = [];
		foreach ($definitionList as $item) {
			$list = array_merge($list, $this->loadDefinitions($item));
		}

		return $list;
	}

	/**
	 * @param array $definitionList
	 * @return ServiceDefinition[]
	 * @throws InvalidConfigFormatException
	 */
	private function loadDefinitions(array $definitionList)
	{
		$this->validator->validateDefinitions($definitionList);

		if (null === $definitionList[Fields::ROOT_CONFIG_NAME]) {
			return [];
		}

		$list = [];
		foreach ($definitionList[Fields::ROOT_CONFIG_NAME] as $serviceName => $serviceConfig) {
			$list[] = $this->initServiceDefinition($serviceName, $serviceConfig);
		}

		return $list;
	}

	/**
	 * @param string $serviceName
	 * @param array $serviceConfig
	 * @return ServiceDefinition
	 */
	private function initServiceDefinition(string $serviceName, array $serviceConfig)
	{
		$definition = new ServiceDefinition(
			$serviceName,
			$this->getClass($serviceConfig),
			$this->getConstructorParams($serviceConfig),
			$this->getMethodCalls($serviceConfig)
		);

		return $definition;
	}

	/**
	 * @param $serviceConfig
	 * @return string
	 */
	private function getClass($serviceConfig)
	{
		return $serviceConfig[Fields::CLASS_CONFIG_NAME];
	}

	/**
	 * @param $serviceConfig
	 * @return array
	 */
	private function getConstructorParams($serviceConfig)
	{
		if (false === array_key_exists(Fields::CONSTRUCTOR_PARAMS_CONFIG_NAME, $serviceConfig)) {
			return [];
		}

		return $serviceConfig[Fields::CONSTRUCTOR_PARAMS_CONFIG_NAME];
	}

	/**
	 * @param $serviceConfig
	 * @return array
	 */
	private function getMethodCalls($serviceConfig)
	{
		if (false === array_key_exists(Fields::METHOD_CALLS_CONFIG_NAME, $serviceConfig)) {
			return [];
		}

		return $serviceConfig[Fields::METHOD_CALLS_CONFIG_NAME];
	}

}
