<?php

/**
 * Base class of all the controllers handling the routes
 */
abstract class Controller
{
	/**
	 * @var array<string,string>
	 */
	protected readonly array $params;

	/**
	 * @var string[]
	 */
	protected array $paths = [];

	/**
	 * @var string[]
	 */
	protected array $methods = ['GET'];

	/**
	 * Defines wheather user authentication is required
	 */
	protected bool $userRequired = false;

	public function initRoutes(): void {
		foreach ($this->paths as $path)
			Router::addRoute($path, $this);
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

	abstract protected function execute(): void;

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
	 * Redirects user to new URL
	 *
	 * @param string $url Redirectes to this
	 */
	protected function redirect(string $url): never {
		header('Location: ' . $url);
		exit;
	}

	/**
	 * Checks if the access to this controller is allowed
	 *
	 * @return int HTTP status code (200 === OK!)
	 */
	private function accessAllowed(): int {
		if (empty(array_intersect(['*', 'OPTIONS', 'HEAD', IO::method()], $this->methods)))
			return 405;
		if ($this->userRequired && Auth::validateToken())
			return 401;
		return 200;
	}
}
