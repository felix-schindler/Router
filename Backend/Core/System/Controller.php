<?php

/**
 * Base class of all the controllers handling the routes
 */
abstract class Controller
{
	/**
	 * @var array<string,string>
	 */
	private array $params = [];

	/**
	 * @return string[] All routes the controller listens to
	 */
	abstract protected function getRoutes(): array;
	abstract protected function execute(): void;

	public function initRoutes(): void {
		foreach ($this->getRoutes() as $route)
			Router::addRoute($route, $this);
	}

	/**
	 * Redirects user to new URL
	 *
	 * @param string $url Redirectes to this
	 */
	protected function redirect(string $url): void {
		header("Location: " . $url);
		exit;		// Do not execute code after redirect
	}

	/**
	 * Executes the controller with given params
	 *
	 * @param array<string,string> $params Parameters
	 */
	public function runExecute(array $params): void {
		if (($code = $this->accessAllowed()) === 200) {
			$this->params = $params;
			$this->execute();
		} else {
			(new ErrorView($code))->render();
			return;
		}
	}

	/**
	 * Get a param from within the URL
	 *
	 * @param string $var Name of variable
	 * @param boolean $exact Get the exact value
	 * @return string|null Value of variable or null if not exists
	 */
	protected function getParam(string $var, bool $exact = false): ?string {
		if (isset($this->params[$var]))
			if (is_string($this->params[$var]))
				return $exact ? strval($this->params[$var]): htmlspecialchars(urldecode(strval($this->params[$var])));
		return null;
	}

	/**
	 * Defines which access methods are allowed (wildcard works)
	 *
	 * @return string[] Allowed access methods
	 */
	protected function getAccessMethods(): array {
		return ["*"];
	}

	/**
	 * Defines if user authentication is required
	 *
	 * @return boolean True if it is, false otherwise
	 */
	protected function userRequired(): bool {
		return false;
	}

	/**
	 * Checks if the controller is being accessed with an allowed access method
	 *
	 * @return boolean True if it is, false otherwise
	 */
	private function accessMethodAllowed(): bool {
		if (in_array("*", $this->getAccessMethods()) || in_array(IO::getRequestMethod(), $this->getAccessMethods()))
			return true;
		else
			return false;
	}

	/**
	 * Checks the user token, if user is required
	 *
	 * @return boolean
	 */
	private function authAccessAllowed(): bool {
		if (!$this->userRequired())
			return true;
		else
			return Auth::validateToken();
	}

	/**
	 * Checks if the access to this controller is allowed
	 *
	 * @return int HTTP status code (200 === OK!)
	 */
	private function accessAllowed(): int {
		if (!$this->accessMethodAllowed())
			return 405;
		if (!$this->authAccessAllowed())
			return 401;
		return 200;
	}
}
