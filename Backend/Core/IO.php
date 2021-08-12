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
				return $exact ? $_GET[$var] : htmlspecialchars(urldecode($_GET[$var]));
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
				return $exact ? $_POST[$var] : htmlspecialchars(urldecode($_POST[$var]));
    return null;
  }

	/**
	 * Set or get a $_SESSION variable
	 *
	 * @param string $var Name of variable
	 * @param string $value Value of variable - If this is set not null -> get to set
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists and on set
	 */
	public static function SESSION(string $var, string $value = null, bool $exact = false) : ?string
	{
		if ($value !== null)
			$_SESSION[$var] = $value;

		if (isset($_SESSION[$var]))
			if (is_string($_SESSION[$var]))
				return $exact ? $_SESSION[$var] : htmlspecialchars(urldecode($_SESSION[$var]));
    return null;
  }

	/**
	 * Set or get a $_COOKIE variable (only HTTPS allowed)
	 *
	 * @param string $var Name of variable
	 * @param string $value Value of variable - If this is set not null -> get to set
	 * @param integer $lifetime Standard: 30 days
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists and on set
	 */
	public static function COOKIE(string $var, ?string $value = null, int $lifetime = 2592000, bool $exact = false) : ?string
	{
    if ($value !== null)
			setcookie($var, $value, time()+$lifetime, "/", self::getDomain(), true);

		if (isset($_GET[$var]))
			if (is_string($_GET[$var]))
				return $exact ? $_COOKIE[$var] : htmlspecialchars(urldecode($_GET[$var]));
		return null;
	}

	/**
	 * @return string URL (without HTTP(S)://)
	 */
  public static function getDomain() : string
  {
    return $_SERVER['SERVER_NAME'];
  }
}
