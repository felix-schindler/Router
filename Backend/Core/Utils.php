<?php

/**
 * Global utility functions
 */
class Utils
{
	/**
	 * Return the file extension of a given filename
	 *
	 * @param string $filename
	 * @return string Extension of file
	 */
	public static function getFileExtension(string $filename) : string
	{
		return pathinfo($filename, PATHINFO_EXTENSION);
	}

	/**
	 * Takes a path, looks if it is a url, if not -> makes a CDN URL out of it
	 *
	 * @param string $path Filename or URL
	 * @return string Valid url
	 */
	public static function getCdnUrl(string $path) : string
	{
		if (filter_var($path, FILTER_VALIDATE_URL) === false)
			return CDN_DOMAIN . "/" . CDN_SUFFIX . $path;
		return $path;
	}

	/**
	 * Hashes the given data string with a chosen hash algorythm (Standard: SHA256)
	 *
	 * @param string $data The data to be hashed
	 * @param string $algo Hashing algorithmn - Standard: SHA256
	 * @return string Hashed data
	 */
	public static function encrypt(string $data, string $algo = "sha256") : string
	{
		return hash($algo, $data);
	}

	/**
	 * Generates a random string with latin characters and decimal numbers
	 *
	 * @param integer $length
	 * @return string Random string
	 */
	public static function randomString(int $length = 64) : string
	{
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$randStr = "";

		for ($i = 0; $i < $length; $i++)
			$randStr .= $characters[rand(0, strlen($characters)-1)];

		return $randStr;
	}

	/**
	 * Generates a random GUID (Version 4)
	 *
	 * @param string|null $data Random bytes
	 * @return string A random GUID
	 */
	public static function guid(?string $data = null) : string
	{
		// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
		$data ??= random_bytes(16);
		assert(strlen($data) == 16);

		// Set version to 0100
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		// Set bits 6-7 to 10
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

		// Output the 36 character UUID.
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
