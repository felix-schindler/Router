<?php

/**
 * Functions for standard input output (nearly as good as stdio.h)
 */
class IO
{
	/**
	 * Get query variables
	 *
	 * `WARN: When value is an array, values are obviously not escaped, so be CAREFUL`
	 *
	 * @param string $var Name/Key of variable
	 * @return string|array<mixed>|null excaped string OR array with raw values
	 * @example Backend/Core/System/IO.php query("username")
	 * @since 2.0.0
	 */
	public static function query(string $var): string | array | null
	{
		if (isset($_GET[$var]))
			if (is_string($_GET[$var]))
				return htmlspecialchars(urldecode($_GET[$var]));
			elseif (is_array($_GET[$var]))
				return $_GET[$var];
		return null;
	}

	/**
	 * Get x-www-form-urlencoded or JSON encoded variables from the body
	 *
	 * `WARN: When value is an array, values are obviously not escaped, so be CAREFUL`
	 *
	 * @param string $var Name/Key of variable
	 * @return string|array<mixed>|null excaped string OR array with raw values
	 * @example Backend/Core/System/IO.php body("password")
	 * @since 2.0.0
	 */
	public static function body(string $var): string | array | null
	{
		if ($_SERVER['CONTENT_TYPE'] == 'application/json' || $_SERVER['HTTP_CONTENT_TYPE'] == 'application/json') {
			if (($input = file_get_contents('php://input')) !== false) {
				if (($json = json_decode($input, true)) !== null && isset($json[$var])) {
					if (is_string($json[$var]))
						return htmlspecialchars($json[$var]);
					elseif (is_array($json[$var]))
						return $json[$var];
				}
			}
		} elseif (isset($_POST[$var])) {
			if (is_string($_POST[$var]))
				return htmlspecialchars(urldecode($_POST[$var]));
			elseif (is_array($_POST[$var]))
				return $_POST[$var];
		}

		return null;
	}


	/**
	 * Set or get a $_SESSION variable
	 *
	 * @param string $var Name of variable
	 * @param string|null $value Value of variable - If this is set not null -> get to set
	 * @throws Error When there is no session
	 * @return string|null Value of variable or null if not exists and on set
	 * @throws Exception When no php session was started
	 */
	public static function SESSION(string $var, string $value = null): ?string
	{
		if (session_status() !== PHP_SESSION_ACTIVE)
			throw new Exception('You have to start the session first');

		// Set variable
		if ($value !== null)
			$_SESSION[$var] = $value;

		// Return variable
		else if (isset($_SESSION[$var]) && is_string($_SESSION[$var]))
			return htmlspecialchars($_SESSION[$var]);

		return null;
	}

	/**
	 * Set or get a $_COOKIE variable (only HTTPS allowed)
	 *
	 * @param string $var Name of variable
	 * @param string|null $value Value of variable - If this is set not null -> get to set
	 * @param integer $lifetime Standard: 30 days
	 * @return string|null Value of variable or null if not exists and on set
	 * @throws Exception When code is not executed on HTTP server
	 */
	public static function COOKIE(string $var, ?string $value = null, int $lifetime = 2592000): ?string
	{
		if ($value !== null) {
			setcookie($var, $value, time() + $lifetime, '/', self::domain(), true);
		} else if (isset($_COOKIE[$var]) && is_string($_COOKIE[$var])) {
			return htmlspecialchars($_COOKIE[$var]);
		}

		return null;
	}

	/**
	 * Get the authorization header
	 *
	 * @return string|null The auth header, NULL if not set
	 */
	public static function authHeader(): ?string
	{
		if (isset($_SERVER['Authorization']))					// "Normal"
			return trim($_SERVER['Authorization']);
		elseif (isset($_SERVER['HTTP_AUTHORIZATION']))		// Nginx or fast CGI
			return trim($_SERVER['HTTP_AUTHORIZATION']);
		return null;
	}

	/**
	 * @return string The domain (URL without HTTP(S)://)
	 * @throws Exception When not running on a server
	 */
	public static function domain(): string
	{
		if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != null)
			return $_SERVER['SERVER_NAME'];
		throw new Exception('Not running on a server');
	}

	/**
	 * @return string Currently requested path
	 * @throws Exception When no request is set
	 */
	public static function path(): string
	{
		if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != null)
			return explode('?', $_SERVER['REQUEST_URI'], 2)[0];
		throw new Exception('No request');
	}

	/**
	 * @return string Full URL with protocol and request uri
	 */
	public static function fullURL(): string
	{
		return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * @return string HTTP request method
	 * @throws Exception When no request method set
	 */
	public static function method(): string
	{
		if (isset($_SERVER['REQUEST_METHOD']))
			return $_SERVER['REQUEST_METHOD'];
		throw new Exception('No request method set');
	}

	/**
	 * @return string HTTP 'Accept' header
	 * @throws Exception When no accept header set
	 */
	public static function accept(): string
	{
		if (isset($_SERVER['HTTP_ACCEPT']))
			return explode(',', $_SERVER['HTTP_ACCEPT'])[0];
		throw new Exception('No accept header set');
	}
}
