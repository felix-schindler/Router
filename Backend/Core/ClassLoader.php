<?php

/**
 * Loads all PHP classes Just-In-Time
 */
class ClassLoader
{
	/**
	 * Currently loaded classes
	 *
	 * @var array<string,string>
	 */
	private static array $classes = [];

	/**
	 * Registers the Autoloader
	 */
	public static function register() : void
	{
		self::loadClasses("Backend");
		spl_autoload_register("ClassLoader::LoadClass", prepend: true);
		self::initControllers();
	}

	/**
	 * Require the file of a specific class
	 *
	 * @param string $className Name of class to require
	 */
	public static function LoadClass(string $className) : void
	{
		if (isset(self::$classes[$className]))
			if (file_exists(self::$classes[$className]))
				require_once(self::$classes[$className]);
	}

	/**
	 * Initialize the routes of all controllers
	 */
	private static function initControllers() : void
	{
		foreach (self::$classes as $name => $path) {
			if (str_contains($path, "/Controllers/")) {
				$controller = new $name();
				if ($controller instanceof Controller)
					$controller->initRoutes();
			}
		}
	}

	/**
	 * Iterate recursively over a directory and add to classes array
	 *
	 * @param string $dir - Directory to check for php files
	 */
	private static function loadClasses(string $dir) : void
	{
		if (($handle = opendir($dir)) !== false) {
			while (false !== ($file = readdir($handle))) {
				if ($file !== "." && $file !== "..") {
					$path = $dir . "/" . $file;
					if (is_file($path)) {
						if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
							self::$classes[str_replace(".php", "", $file)] = $path;
						}
					} elseif (is_dir($path)) {
						self::loadClasses($path);
					}
				}
			}
			closedir($handle);
		}
	}
}

// Run the class loader
ClassLoader::register();
