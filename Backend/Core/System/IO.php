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
	 * @return string|array<mixed>|null array with !RAW! values OR htmlspecialchars, urldecoded string
	 */
	public static function query(string $var): string | array | null {
		if (isset($_GET[$var]))
			if (is_string($_GET[$var]))
				return htmlspecialchars(urldecode(strval($_GET[$var])));
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
	 * @return string|array<mixed>|null array with !RAW! values OR htmlspecialchars, urldecoded string
	 */
	public static function body(string $var): string | array | null {
		if (isset($_POST[$var])) {
			if (is_string($_POST[$var]))
				return htmlspecialchars(urldecode(strval($_POST[$var])));
			elseif (is_array($_POST[$var]))
				return $_POST[$var];
		} elseif (($json = json_decode(($inputData = file_get_contents('php://input')), true)) !== null && isset($json[$var])) {
			if (is_string($json[$var]))
				return htmlspecialchars(strval($json[$var]));
			elseif (is_array($json[$var]))
				return $json[$var];
		} elseif (IO::method() != 'POST') {
			if ($inputData != null) {
				$arr = [];
				foreach (explode('&', $inputData) as $chunk) {
					$param = explode("=", $chunk);
					$key = urldecode($param[0]);
					if (isset($param[1])) {
						if (!isset($arr[$key]))
							$arr[$key] = urldecode($param[1]);
						else {
							if (is_string($arr[$key])) {
								$arr[$key] = [$arr[$key], urldecode($param[1])];
							} else {
								$arr[$key][count($arr[$key])] = urldecode($param[1]);
							}
						}
					}
				}
				if (isset($arr[$var])) {
					if (is_string($arr[$var]))
						return htmlspecialchars(urldecode(strval($arr[$var])));
					elseif (is_array($arr[$var]))
						return $arr[$var];
				}
			}
		}

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
	public static function SESSION(string $var, string $value = null, bool $exact = false): ?string {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();

		// Set variable
		if ($value !== null) {
			$_SESSION[$var] = $value;
			return null;
		}

		if (isset($_SESSION[$var]))
			if (is_string($_SESSION[$var]))
				return $exact ? $_SESSION[$var]: htmlspecialchars($_SESSION[$var]);
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
	public static function COOKIE(string $var, ?string $value = null, int $lifetime = 2592000, bool $exact = false): ?string {
		if ($value !== null) {
			setcookie($var, $value, time()+$lifetime, "/", self::domain(), true);
			return null;
		}

		if (isset($_COOKIE[$var]))
			if (is_string($_COOKIE[$var]))
				return $exact ? strval($_COOKIE[$var]): htmlspecialchars(strval($_COOKIE[$var]));
		return null;
	}

	/**
	 * Get the authorization header
	 *
	 * @return string|null The auth header, NULL if not set
	 */
	public static function authHeader(): ?string {
		if (isset($_SERVER['Authorization']))					// "Normal"
			return trim($_SERVER['Authorization']);
		elseif (isset($_SERVER['HTTP_AUTHORIZATION']))		// Nginx or fast CGI
			return trim($_SERVER['HTTP_AUTHORIZATION']);
		return null;
	}

	/**
	 * @return string The domain (URL without HTTP(S)://)
	 */
	public static function domain(): string {
		if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] != null)
			return $_SERVER["SERVER_NAME"];
		throw new Exception("Not running on a server");
	}

	/**
	 * Returns the currently Requested URL
	 *
	 * @return string The requested path
	 * @throws Exception When no request is set
	 */
	public static function path(): string {
		if (isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] != null)
			return explode("?", $_SERVER["REQUEST_URI"], 2)[0];
		throw new Exception("No request");
	}

	/**
	 * Returns the full URL with protocol and request uri
	 * @return string The full URL with protocol
	 */
	public static function fullURL(): string {
		return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Returns the request method
	 * @return string The request method
	 */
	public static function method(): string {
		if (isset($_SERVER["REQUEST_METHOD"]))
			return $_SERVER["REQUEST_METHOD"];
		throw new Exception("No request method set");
	}
}
