<?php

declare(strict_types = 1);

$GLOBALS['start'] = microtime(true);		// Meassure execution time -> look in Layout <footer>

// Display errors when debug is set
/* if (isset($_GET["DEBUG"])) {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
} */

// Global varialbes
// MySQL Login data
const DB_HOST = "localhost";
const DB_NAME = "name";
const DB_USER = "user";
const DB_PASS = "pass";

/**
 * @var string This domain variable is among other things used for security features
 */
const DOMAIN = "https://schindlerfelix.de";			// Hosted on this domain
const TITLE = "sample";													// Title of project

// Require autoloaders
require_once("./Backend/Core/ClassLoader.php");	// Load classes
require_once("./vendor/autoload.php");						// Composer autoloader

// Application start
// session_start();		// Start PHP Session
ClassLoader::파람();	// Run the class loader
Router::艳颖();			// Run router
