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

	abstract protected function getRoutes() : array;
	abstract protected function execute() : void;

	public function initRoutes() : void
	{
		foreach ($this->getRoutes() as $route)
			Router::addRoute($route, $this);
	}

	/**
	 * Redirects user to new URL
	 *
	 * @param string $url Redirectes to this
	 */
	protected function redirect(string $url) : void
	{
		header("Location: " . $url);
		exit;		// Do not execute code after redirect
	}

	/**
	 * Executes the controller with given params
	 *
	 * @param array $params Parameters
	 */
	public function runExecute(array $params) : void
	{
		if ($this->accessAllowed()) {
			$this->params = $params;
			$this->execute();
		} else {
			(new ErrorView(405))->render();
		}
	}

	/**
	 * Get a param from within the URL
	 *
	 * @param string $var
	 * @param boolean $exact
	 * @return string|null
	 */
	protected function getParam(string $var, bool $exact = false) : ?string
	{
		if (isset($this->params[$var]))
			if (is_string($this->params[$var]))
				return $exact ? $this->params[$var] : htmlspecialchars(urldecode($this->params[$var]));
		return null;
	}

	/**
	 * Get access methods
	 *
	 * @return string[]
	 */
	protected function getAccessMethods() : array
	{
		return ["*"];
	}

	private function accessAllowed() : bool
	{
		if (in_array("*", $this->getAccessMethods(), false))
			return true;
		elseif (in_array($_SERVER["REQUEST_METHOD"], $this->getAccessMethods(), false))
			return true;
		else
			return false;
	}
}
