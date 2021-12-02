<?php

/**
 * Loads all PHP classes Just-In-Time
 */
class ClassLoader
{
	/**
	 * Currently loaded classes
	 *
	 * @var array<string,string> - Class name, Path
	 */
	private static array $classes = [];

	/**
	 * Registers the Autoloader
	 */
	public static function 파람(): void {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../../Backend'), RecursiveIteratorIterator::SELF_FIRST);
		foreach($files as $file)
			if (pathinfo($file->getFileName(), PATHINFO_EXTENSION) == "php" && !str_contains($file->getPathname(), '/Libraries/vendor/'))
				self::$classes[str_replace(".php", "", $file->getFileName())] = $file->getPathname();

		spl_autoload_register(function($className): void {
			if (isset(self::$classes[$className]) && file_exists(self::$classes[$className]))
				require_once(self::$classes[$className]);
		}, prepend: true);

		self::initControllers();
	}

	/**
	 * Initialize the routes of all controllers
	 */
	private static function initControllers(): void {
		foreach (self::$classes as $name => $path) {
			if (str_contains($path, "/Controllers/")) {
				$controller = new $name();
				if ($controller instanceof Controller)
					$controller->initRoutes();
			}
		}
	}
}

// Run the class loader
ClassLoader::파람();
