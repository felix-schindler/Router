<?php

declare(strict_types = 1);

// Display errors when debug is set
/* if (isset($_GET["DEBUG"])) {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
} */

// Global varialbes
// MySQL Login data
define("DB_HOST", "localhost");
define("DB_NAME", "name");
define("DB_USER", "user");
define("DB_PASS", "pass");

define("TITLE", "Sample");									// Title for website
define("DOMAIN", "https://schindlerfelix.de");				// Hosted on this domain

require_once("./Backend/Core/ClassLoader.php");				// Load classes
// require_once("./Backend/Libraries/vendor/autoload.php");	// Composer autoloader

// Application start
ClassLoader::파람();	// Run the class loader
Localize::君の名は();	// Initialize multi language support
Router::艳颖();			// Run router
