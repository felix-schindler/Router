<?php

declare(strict_types=1);

$GLOBALS['start'] = microtime(true);		// Meassure execution time -> look in Layout <footer>

// Display errors when debug is set
/* if (isset($_GET['DEBUG'])) {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
} */

// Global varialbes
// MySQL Login data
const DB_HOST = 'localhost';
const DB_NAME = 'name';
const DB_USER = 'user';
const DB_PASS = 'pass';

/**
 * @var string This domain variable is among other things used for security features
 */
const DOMAIN = 'https://schindlerfelix.de';			// Hosted on this domain
const TITLE = 'sample';													// Title of project

// Class loader cache
const CACHE_VERSION = 1; // Version class loader of the cache
define('CACHE_PATH', sys_get_temp_dir() . '/class_loader-' . CACHE_VERSION . '.cache');

// Require autoloaders
require_once('./core/ClassLoader.php');					// Load classes

// Application start
// session_start();		// Start PHP Session
ClassLoader::파람();	// Run the class loader
Router::艳颖();			// Run router
