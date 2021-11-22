<?php

/**
 * User authentication
 */
class Auth
{
	/**
	 * Checks the auth credentials and returns an array with name and token of the user
	 *
	 * @param string $email Email of a user
	 * @param string $password Password of a user
	 * @return array<string,string>|null [name, token]
	 */
	public static function login(string $email, string $password) : ?array
	{
		$q = new Query("SELECT `Name`, `UUID`, `Password` FROM User WHERE `EMail`=:email", [":email" => $email]);

		if ($q->count() === 1 && ($user = $q->fetch()) !== null) {					// Just to be extra sure that only one user exists with that email
			if (password_verify($password, $user["Password"])) {
				if (password_needs_rehash($user["Password"], PASSWORD_DEFAULT)) {
					$newPass = password_hash($password, PASSWORD_DEFAULT);
					(new Query("UPDATE `User` SET `Password`=:newPass WHERE `UUID`=:uuid;", [":newPass" => $newPass, ":uuid" => $user["UUID"]]))->success();
					$user["Password"] = (new Query("SELECT `Password` FROM `User` WHERE `UUID`=:uuid;", [":uuid" => $user["UUID"]]))->fetch()["Password"];
				}

				return [
					"name" => $user["Name"],
					"token" => self::generateToken($user["UUID"], $user["Password"]),
					"authorProfile" => $user["AuthorProfile"]
				];
			}
		}

		return null;
	}

	/**
	 * Register a new user
	 *
	 * @param string $email Email of the user
	 * @param string $password Password of the user
	 * @return boolean Wheather the user was registered
	 */
	public static function register(string $email, string $password) : bool
	{
		return false; // TODO implement
	}

	/**
	 * Checks whether the token is valid or not
	 *
	 * @param string|null $token The auth (bearer) token
	 * @return boolean True if valid, otherwise false
	 */
	public static function validateToken(?string $token = null) : bool
	{
		if ($token == null)
			if (($token = IO::getBearerToken()) == null)
				return false;
		if (($decoded = base64_decode($token)) !== false) {
			$decToken = explode(".", $decoded);
			if (count($decToken) === 3) {
				if (($uuid = base64_decode($decToken[0])) != false && ($passHash = base64_decode($decToken[1])) != false && ($validUntil = base64_decode($decToken[2])) != false) {
					$dayDiff = (new DateTime())->diff(new DateTime($validUntil))->format('%r%a');

					// Date is between 1 and 30 days in the future
					if ($dayDiff > 0 && $dayDiff <= 30) {
						$q = new Query("SELECT `Password` FROM User WHERE UUID=:uuid;", [":uuid" => $uuid]);
						if ($q->count() === 1 && ($user = $q->fetch()) !== null) {
							return password_verify($user["Password"], $passHash);
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * Get UUID from bearer token
	 *
	 * @return string|null
	 */
	public static function getTokenUUID() : ?string
	{
		if (($token = IO::getAuthorizationHeader()) != null) {
			if (($decoded = base64_decode($token)) !== false) {
				$decToken = explode(".", $decoded);
				if (($uuid = base64_decode($decToken[0])) !== false)
					return $uuid;
			}
		}
		return null;
	}

	/**
	 * Generates a token with lifespan of 30 days
	 *
	 * @param string $uuid UUID of a user
	 * @param string $pass Password of a user
	 * @return string Valid token for the next 30 days
	 */
	public static function generateToken(string $uuid, string $pass) : string
	{
		$validUntil = (new DateTime())->add(new DateInterval('P30D'))->format("Y-m-d");
		$passHash = password_hash($pass, PASSWORD_DEFAULT);
		return base64_encode(base64_encode($uuid) . "." . base64_encode($passHash) . "." . base64_encode($validUntil));
	}
}
