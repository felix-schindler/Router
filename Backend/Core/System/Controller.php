<?php

/**
 * Base class of all the controllers handling the routes
 */
abstract class Controller
{
	/**
	 * @var array<string,string> param name, value
	 */
	private array $params;

	/**
	 * @var string[] Paths the controller listens to
	 */
	protected array $paths = [];

	/**
	 * @var string[] Allowed methods to access controller
	 */
	protected array $methods = ['GET'];

	/**
	 * @var array<string,string[]> Required variables in the body of the request
	 * @example location defined like this: `['POST' => ['param1', 'param2'], 'PATCH' => ['param3']]`
	 */
	protected array $reqVar = [];

	/**
	 * Defines whether user authentication is required
	 */
	protected bool $userRequired = false;

	/**
	 * Adds the paths to the router
	 */
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
		if (($code = $this->checkAccess()) === 200) {
			$this->params = $params;
			$this->execute()->render();
		} else {
			(new ErrorView($code, str_contains(IO::path(), '/api/')))->render();
		}
	}

	/**
	 * Main method of the controller
	 */
	abstract protected function execute(): View;

	/**
	 * Get a param from within the URL
	 *
	 * @param string $var Name of variable
	 * @return string|null Value of variable or null if not exists
	 */
	protected function param(string $var): ?string {
		if (isset($this->params[$var]) && is_string($this->params[$var]))
			return htmlspecialchars(urldecode(strval($this->params[$var])));
		return null;
	}

	/**
	 * Redirects user to new URL
	 *
	 * @param string $url Redirectes to this
	 * @return never No code execution after this
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
	private function checkAccess(): int {
		$method = IO::method();
		header('Access-Control-Allow-Methods: ' . implode(', ', array_merge(['OPTIONS', 'HEAD'], $this->methods)));
		if (empty(array_intersect(['*', 'OPTIONS', 'HEAD', $method], $this->methods)))
			return 405;
		if ($this->userRequired)
			if (!Auth::validateToken())
				return 401;
		if (!in_array($method, ['GET', 'HEAD', 'DELETE', 'OPTIONS']))
			foreach ($this->reqVar[$method] as $var)
				if (IO::body($var) === null)
					return 400;
		return 200;
	}
}
