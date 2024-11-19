<?php

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
	 * Main method of the class loader
	 * Registers the Autoloader
	 */
	public static function 파람(): void
	{
		$cachedClasses = self::loadFromCache();
		if ($cachedClasses !== null) {
			self::$classes = $cachedClasses;
		} else {
			self::loadClasses();
		}

		spl_autoload_register(function ($className): void {
			// Check if class is already loaded
			if (isset(self::$classes[$className]) && file_exists(self::$classes[$className])) {
				require_once self::$classes[$className];
			}
		}, prepend: true);

		self::initControllers();
	}

	/**
	 * Load all classes from the src directory and save cache
	 */
	private static function loadClasses(): void
	{
		$directory = new RecursiveDirectoryIterator(__DIR__ . '/../');
		$iterator = new RecursiveIteratorIterator($directory);
		$phpFiles = new RegexIterator($iterator, '/\.php$/');

		foreach ($phpFiles as $file) {
			assert($file instanceof SplFileInfo);
			$filePath = $file->getPathname();
			$className = basename($filePath, '.php');
			self::$classes[$className] = $filePath;
		}
		self::saveToCache();
	}

	/**
	 * Announce all controller routes to the Router
	 */
	private static function initControllers(): void
	{
		foreach (self::$classes as $name => $path) {
			// Replace \ with / for windows users
			if (str_contains(str_replace('\\', '/', $path), '/controllers/')) {
				$controller = new $name();
				if ($controller instanceof Controller) {
					$controller->initRoutes();
				}
			}
		}
	}

	/**
	 * Uses a file-based cache to retrieve the classes array.
	 *
	 * @return array<string,string>|null Class name, path
	 */
	private static function loadFromCache(): ?array
	{
		if (file_exists(CACHE_PATH) && is_readable(CACHE_PATH)) {
			$cachedData = file_get_contents(CACHE_PATH);

			if ($cachedData !== false) {
				$cachedClasses = unserialize($cachedData);
				return is_array($cachedClasses) ? $cachedClasses : null;
			}
		}

		return null;
	}

	/**
	 * Saves the classes array to a file-based cache.
	 */
	private static function saveToCache(): void
	{
		if (is_writable(dirname(CACHE_PATH))) {
			file_put_contents(CACHE_PATH, serialize(self::$classes));
		}
	}
}
