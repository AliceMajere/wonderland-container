<?php

namespace Wonderland\Container\Configuration\Parser;

/**
 * Interface ParserInterface
 * @package Wonderland\Container\Configuration\Parser
 * @author Alice Praud <alice.majere@gmail.com>
 */
interface ParserInterface
{

	/**
	 * @param string $filePath
	 * @return array
	 */
	public function loadFile(string $filePath): array;

	/**
	 * @param string $directorPath
	 * @return array
	 */
	public function loadDirectory(string $directorPath): array;

}
