<?php

/**
 * The router, this is where everything is coming to life
 */
class Router
{
	/**
	 * Hold all paths and controllers
	 * @var array<string,Controller> Path, Controller
	 */
	private static array $routes = [];

	/**
	 * The total main function
	 *
	 * @throws Exception When not on server and not accessing via HTTPS or localhost
	 */
	public static function 艳颖(): void
	{
		// Either access via localhost or HTTPS
		if (!isset($_SERVER["HTTPS"]) && !(IO::domain() === "localhost"))
			throw new Exception("Only access over HTTPS allowed");

		// Get the route without GET variables
		$reqRoute = IO::path();

		// Run the routers execute method or, if no route matches, run the error
		if (self::routeExists($reqRoute)) {												// Direct hit (no variables in path)
			self::$routes[$reqRoute]->runExecute([]);
			return;
		} else {
			$routes = array_keys(self::$routes);										// Get all routes as string
			$reqRouteArr = explode("/", $reqRoute);		        			// Split requested route

			$routes = array_filter($routes, function ($route) use ($reqRouteArr): bool {	// Filter out all routes that don't match
				$routeArr = explode("/", $route);
				if (str_contains($route, ':'))												// Only routes with variables, on direct hit it would have already exited
					if (count($routeArr) == count($reqRouteArr))				// Routes have to same length to be a match
						return true;
				return false;
			});

			if (!empty($routes)) {
				$hits = [];
				foreach ($routes as $route) {													// Calculate scores to get the route that fits best
					$routeArr = explode("/", $route);
					$hits[$route] = 0;
					for ($i = 0; $i < count($routeArr); $i++) {
						if ($routeArr[$i] == $reqRouteArr[$i])						// Prioritise direct routes over variables
							$hits[$route]++;																// Increment hit score
						elseif ($routeArr[$i][0] != ":") {								// Remove route if does not match and not a variable
							unset($hits[$route]);
							break;
						}
					}
				}

				if (!empty($hits)) {																	// At least one route was found
					arsort($hits);																			// Sort routes by hit score
					$routes = array_keys($hits);
					$route = $routes[0];																// Get best matching route

					$routeArr = explode("/", $route);
					$params = [];
					for ($i = 0; $i < count($routeArr); $i++)
						if (isset($routeArr[$i][0]) && $routeArr[$i][0] === ":")		// If part of URL is a variable
							$params[substr($routeArr[$i], 1)] = $reqRouteArr[$i];			// Set as param (this could be a on-liner)
					self::$routes[$route]->runExecute($params);										// Execute controller for found route
					return;
				}
			}
		}
		(new ErrorView())->render();									// No route found -> ErrorController
	}

	/**
	 * Add a route to routes array
	 *
	 * @param string $route Route after URL
	 * @param Controller $con Controller to handle route
	 * @throws Exception When a route already exists with another controller
	 */
	public static function addRoute(string $route, Controller $con): void
	{
		if (self::routeExists($route, $con))
			throw new Exception("Route " . $route . " already used for " . self::$routes[$route]::class);
		self::$routes[$route] = $con;
	}

	/**
	 * Checks if a route exists in routes array
	 *
	 * @param string $route Route (with starting '/')
	 * @param Controller|null $con Controller to be routed to
	 * @return boolean True if exists, false otherwise
	 */
	private static function routeExists(string $route, ?Controller $con = null): bool
	{
		if (isset(self::$routes[$route])) {
			if ($con === null)
				return true;
			elseif (self::$routes[$route]::class !== $con::class)
				return true;
		}
		return false;
	}
}
