<?php

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

define("TITLE", "Felix");                       // Title for website
define("DOMAIN", "https://schindlerfelix.de");  // Hosted on this domain
define("CDN_SUFFIX", "suf");                    // https://cdn.schindlerfelix.de/CDN_SUFFIX/

require_once("./Backend/Core/ClassLoader.php"); // Load classes

session_start();      // Start PHP session
Router::Bazinga();    // Run router
