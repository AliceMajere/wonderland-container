<?php

namespace Wonderland\Container\Example\Yml;

/**
 * Class SampleClass
 * @package Wonderland\Container\Example\Yml
 * @author Alice Praud <alice.majere@gmail.com>
 */
class SampleClass
{
	private $first;
	private $second;

	public function __construct($param1, $param2)
	{
		$this->first = $param1;
		$this->second = $param2;
	}

	public function callMethod($param1, $param2)
	{
		$this->first = $param1;
		$this->second = $param2;
	}
}
