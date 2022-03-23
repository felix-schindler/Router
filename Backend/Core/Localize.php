<?php

class Localize {
	/**
	 * @var string Language setting code (default: en)
	 */
	private static string $locale = 'en';

	/**
	 * @var array Supported languages
	 */
	private static array $supported = ['de', 'en', 'zh-Hans'];

	/**
	 * Finds the best matching language and sets it
	 */
	public static function 君の名は() {
		$accepted = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		for ($i=0; $i < count($accepted); $i++) {
			if (!str_starts_with($accepted[$i], 'zh-'))
				$accepted[$i] = $accepted[$i][0] . $accepted[$i][1];
		}

		if (!empty(($matches = array_intersect(self::$supported, $accepted)))) {
			self::$locale = array_values($matches)[0];
			echo "<p>[Localize] Language set to '" . self::$locale . "'</p>";
		}

		// if (($locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])) !== false) {
		// 	substr($locale, 0, 2);
		// }

		// TODO: Find best supported language : 'en'
		// TODO: If language is supported
		require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Locale' . DIRECTORY_SEPARATOR . self::$locale . '.php');
	}

	/**
	 * Localized strings
	 *
	 * @param string $key Key of localized string
	 * @return string Localized string
	 */
	function loc(string $key): string {
		return "";
	}
}
