<?php

/**
 * Functions for standard input output (nearly as good as stdio.h)
 */
class IO
{
	/**
	 * Get a $_GET variable
	 *
	 * @param string $var Name of variable
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists
	 */
	public static function GET(string $var, bool $exact = false) : ?string
	{
		if (isset($_GET[$var]))
			if (is_string($_GET[$var]))
				return $exact ? strval($_GET[$var]) : htmlspecialchars(urldecode(strval($_GET[$var])));
		return null;
	}

	/**
	 * Get a $_POST variable
	 *
	 * @param string $var Name of variable
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists
	 */
	public static function POST(string $var, bool $exact = false) : ?string
	{
		if (isset($_POST[$var]))
			if (is_string($_POST[$var]))
				return $exact ? strval($_POST[$var]) : htmlspecialchars(strval($_POST[$var]));
		return null;
	}

	/**
	 * Set or get a $_SESSION variable
	 *
	 * @param string $var Name of variable
	 * @param string|null $value Value of variable - If this is set not null -> get to set
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists and on set
	 */
	public static function SESSION(string $var, string $value = null, bool $exact = false) : ?string
	{
		// Set variable
		if ($value !== null) {
			$_SESSION[$var] = $value;
			return null;
		}

		if (isset($_SESSION[$var]))
			if (is_string($_SESSION[$var]))
				return $exact ? $_SESSION[$var] : htmlspecialchars($_SESSION[$var]);
		return null;
	}

	/**
	 * Set or get a $_COOKIE variable (only HTTPS allowed)
	 *
	 * @param string $var Name of variable
	 * @param string|null $value Value of variable - If this is set not null -> get to set
	 * @param integer $lifetime Standard: 30 days
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists and on set
	 */
	public static function COOKIE(string $var, ?string $value = null, int $lifetime = 2592000, bool $exact = false) : ?string
	{
		if ($value !== null) {
			setcookie($var, $value, time()+$lifetime, "/", self::getDomain(), true);
			return null;
		}

		if (isset($_COOKIE[$var]))
			if (is_string($_COOKIE[$var]))
				return $exact ? strval($_COOKIE[$var]) : htmlspecialchars(strval($_COOKIE[$var]));
		return null;
	}

	/**
	 * Get the authorization headers
	 *
	 * @return string|null The auth headers
	 */
	private static function getAuthorizationHeader() : ?string
	{
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {		// Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {	// Apache
			// @phan-suppress-next-line PhanUndeclaredFunction
			$requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

	/**
	 * Gets the bearer token, if one exists
	 *
	 * @return string|null The bearer token or null, if non is set.
	 */
	public static function getBearerToken() : ?string
	{
		$headers = self::getAuthorizationHeader();
		// HEADER: Get the access token from the header
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}

	/**
	 * @return string The domain (URL without HTTP(S)://)
	 */
	public static function getDomain() : string
	{
		if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] != null)
			return $_SERVER["SERVER_NAME"];
		throw new Exception("Not running on a server");
	}

	/**
	 * Returns the currently Requested URL
	 *
	 * @return string The URL that comes after the Host (domain), starting with a slash ending with the GETs "?"
	 * @throws Exception When no request is set
	 */
	public static function getURL() : string
	{
		if (isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] != null)
			return explode("?", $_SERVER["REQUEST_URI"], 2)[0];
		throw new Exception("No request");
	}

	/**
	 * Returns the full URL with protocol
	 * @return string The full URL with protocol
	 */
	public static function getFullURL() : string
	{
		$protocol = ((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://";
		return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Returns the request method
	 * @return string The request method
	 */
	public static function getRequestMethod() : string
	{
		if (isset($_SERVER["REQUEST_METHOD"]))
			return $_SERVER["REQUEST_METHOD"];
		throw new Exception("No request method");
	}
}
