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
		// Access either via localhost or HTTPS
		if (!isset($_SERVER["HTTPS"]) && IO::domain() !== "localhost") {
			throw new Exception("Only access over HTTPS allowed");
		}

		// Get the route without GET variables
		$reqRoute = IO::path();

		// Run the routers execute method or, if no route matches, run the error
		if (self::routeExists($reqRoute)) {
			self::$routes[$reqRoute]->runExecute([]);
			return;
		} else {
			$reqRouteArr = explode("/", $reqRoute);

			$matchingRoutes = array_filter(self::$routes, function ($controller, $route) use ($reqRouteArr) {
				$routeArr = explode("/", $route);
				if (strpos($route, ':') !== false && count($routeArr) === count($reqRouteArr)) {
					return true;
				}
				return false;
			}, ARRAY_FILTER_USE_BOTH);

			if (!empty($matchingRoutes)) {
				// Calculate scores to get the route that fits best
				$hits = [];
				foreach ($matchingRoutes as $route => $controller) {
					$routeArr = explode("/", $route);
					$hits[$route] = 0;
					foreach ($routeArr as $i => $segment) {
						if ($segment === $reqRouteArr[$i]) {	// Prioritise direct routes over variables
							$hits[$route]++;										// Increment hit score
						} elseif ($segment[0] !== ":") {			// Remove route if does not match and not a variable
							unset($hits[$route]);
							break;
						}
					}
				}

				if (!empty($hits)) {
					// At least one route was found
					arsort($hits);				// Sort routes by hit score
					$route = key($hits);	// Get best matching route

					$routeArr = explode("/", $route);
					$params = [];
					foreach ($routeArr as $i => $segment) {
						if (!empty($segment) && $segment[0] === ":") {			// If part of URL is a variable
							$params[substr($segment, 1)] = $reqRouteArr[$i];	// Set as param
						}
					}

					// Execute controller for found route and exit the router
					self::$routes[$route]->runExecute($params);
					return;
				}
			}
		}

		// No route found -> Show 404 error
		(new ErrorView())->render();
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
		if (self::routeExists($route, $con)) {
			throw new Exception("Route " . $route . " already used for " . self::$routes[$route]::class);
		}
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
			if ($con === null || self::$routes[$route]::class !== $con::class) {
				return true;
			}
		}
		return false;
	}
}
