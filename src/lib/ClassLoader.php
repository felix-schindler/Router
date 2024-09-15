<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Loads all PHP classes Just-In-Time
 */
class ClassLoader
{
	/**
	 * Currently loaded classes
	 *
	 * @var array<string,string> Class name, File path
	 */
	private static array $classes = [];

	/**
	 * Load classes in 'lib' folder and register autoloader
	 */
	public static function 파람(): void
	{
		$logger = new Logger("ClassLoader");
		$logger->pushHandler(new StreamHandler("php://stdout"));

		$directory = new RecursiveDirectoryIterator(__DIR__);
		$iterator = new RecursiveIteratorIterator($directory);
		$phpFiles = new RegexIterator($iterator, '/\.php$/');

		foreach ($phpFiles as $file) {
			$logStr = str_replace(__DIR__, '', $file->getPathname());
			$logger->debug("Found file {$logStr}");

			$filePath = $file->getPathname();
			$className = basename($filePath, '.php');
			self::$classes[$className] = $filePath;
		}

		spl_autoload_register(function ($className): void {
			// Check if class is already loaded
			if (isset(self::$classes[$className]) && file_exists(self::$classes[$className])) {
				require_once self::$classes[$className];
			}
		}, prepend: true);
	}
}
