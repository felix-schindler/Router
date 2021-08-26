<?php

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
define("DOMAIN", "https://blog.schindlerfelix.de");			// Hosted on this domain
define("CDN_DOMAIN", "https://cdn.schindlerfelix.de");		// Domain of the CDN
define("CDN_SUFFIX", "sample/");							// https://cdn.schindlerfelix.de/blog/

require_once("./Backend/Core/ClassLoader.php");				// Load classes
// require_once("./Backend/Libraries/vendor/autoload.php");	// Composer autoloader

// Application start
session_start();      // Start PHP session
Router::Bazinga();    // Run router
