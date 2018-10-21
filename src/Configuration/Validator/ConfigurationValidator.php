<?php

namespace Wonderland\Container\Configuration\Validator;

use Wonderland\Container\Configuration\Config\Fields;
use Wonderland\Container\Exception\InvalidConfigFormatException;

/**
 * TODO find a better way to validate later
 * Class ServiceValidator
 * @package Wonderland\Container\Configuration\Validator
 * @author Alice Praud <alice.majere@gmail.com>
 */
class ConfigurationValidator
{
	private const ERROR_PREFIX = 'The service configuration';

	/**
	 * @param array $definitionList
	 * @throws InvalidConfigFormatException
	 */
	public function validateDefinitions(array $definitionList)
	{
		$this->validateRoot($definitionList);

		if (null === $definitionList[Fields::ROOT_CONFIG_NAME]) {
			return;
		}

		foreach ($definitionList[Fields::ROOT_CONFIG_NAME] as $serviceName => $service) {
			$this->validateService($serviceName, $service);
		}
	}

	/**
	 * @param array $definitionList
	 * @throws InvalidConfigFormatException
	 */
	private function validateRoot(array $definitionList)
	{
		if (false === array_key_exists(Fields::ROOT_CONFIG_NAME, $definitionList)) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' need the root config key "' . Fields::ROOT_CONFIG_NAME . '"'
			);
		}

		$field = $definitionList[Fields::ROOT_CONFIG_NAME];
		if (false === is_array($field) && false === is_null($field)) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . Fields::ROOT_CONFIG_NAME . '" must be an associative array'
			);
		}
	}

	/**
	 * @param $serviceName
	 * @param $service
	 * @throws InvalidConfigFormatException
	 */
	private function validateService($serviceName, $service)
	{
		if (false === is_array($service)) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . $serviceName . '" must be an associative array'
			);
		}

		if (false === array_key_exists(Fields::CLASS_CONFIG_NAME, $service)) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . Fields::CLASS_CONFIG_NAME . '" is required'
			);
		}

		if (false === is_string($service[Fields::CLASS_CONFIG_NAME])) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . Fields::CLASS_CONFIG_NAME . '" must be a string'
			);
		}

		if (true === array_key_exists(Fields::CONSTRUCTOR_PARAMS_CONFIG_NAME, $service) &&
			false === is_array($service[Fields::CONSTRUCTOR_PARAMS_CONFIG_NAME])
		) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . Fields::CONSTRUCTOR_PARAMS_CONFIG_NAME .
				'" must be an array or null'
			);
		}

		if (true === array_key_exists(Fields::METHOD_CALLS_CONFIG_NAME, $service) &&
			false === is_array($service[Fields::METHOD_CALLS_CONFIG_NAME])
		) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . Fields::METHOD_CALLS_CONFIG_NAME .
				'" must be an array or null'
			);
		}

		if (false === array_key_exists(Fields::METHOD_CALLS_CONFIG_NAME, $service)) {
			return;
		}

		foreach ($service[Fields::METHOD_CALLS_CONFIG_NAME] as $methodName => $call) {
			$this->validateCalls($methodName, $call);
		}
	}

	/**
	 * @param $methodName
	 * @param $call
	 * @throws InvalidConfigFormatException
	 */
	private function validateCalls($methodName, $call)
	{
		if (false === is_array($call)) {
			throw new InvalidConfigFormatException(
				self::ERROR_PREFIX . ' key "' . $methodName . '" must be be an array'
			);
		}
	}

}
