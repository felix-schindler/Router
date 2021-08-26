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
				return $exact ? strval($_POST[$var]) : htmlspecialchars(urldecode(strval($_POST[$var])));
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
	 * @return string URL (without HTTP(S)://)
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
}
