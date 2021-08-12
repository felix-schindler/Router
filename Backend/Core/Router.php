<?php

/**
 * The router, this is where everything is coming to life
 */
class Router
{
  /**
   * Hold all routes and controllers
   * @var array<string,controller>
   */
  private static $routes = [];

  /**
   * @var array<string,string>
   */
  private static array $params = [];

  /**
   * @var string Currently requested route
   */
  private static string $reqRoute = "";

  /**
   * The total main function
   */
  public static function Bazinga() : void
  {
    // Either access via localhost or HTTPS
    if (!isset($_SERVER["HTTPS"]) && !IO::getDomain() === "localhost")
      throw new Exception("Only access over HTTPS allowed");

    // Get the route without GET variables
    self::$reqRoute = explode("?", $_SERVER["REQUEST_URI"], 2)[0];

    // Run the routers execute method or, if no route matches, run the error
    if (self::routeExists(self::$reqRoute)) {
      self::$routes[self::$reqRoute]->runExecute([]);
      return;
    } else {
      $routes = array_keys(self::$routes);                                      // Get all routes as string
      $reqRouteArr = explode("/", self::getRouteNoSlash(self::$reqRoute));      // Split requested route

      foreach ($routes as $route) {
        $route = self::getRouteNoSlash($route);
        if (str_contains($route, ":")) {                                        // Only routes with variables, on direct hit it would have already exited the function
          $routeArr = explode("/", $route);
          if (count($routeArr) == count($reqRouteArr)) {
            $correctRoute = $route;                                             // Warning: Takes the first route that works!

            for ($i=0; $i < count($routeArr); $i++) {
              if ($routeArr[$i] !== "") {
                if ($routeArr[$i][0] === ":") {                                 // If part of URL is a variable
                  self::$params[substr($routeArr[$i], 1)] = $reqRouteArr[$i];   // Set as param (this could be a on-liner)
                }
              }
            }

            self::$routes[$correctRoute]->runExecute(self::$params);            // A route was found, run with the parameters from the URL
            return;
          }
        }
      }

      // No route found
      (new ErrorController)->runExecute([]);
    }
  }

  /**
   * Add a route to routes array
   *
   * @param string $route Route after URL
   * @param Controller $con Controller to handle route
   * @throws Exception When a route already exists with another controller
   */
  public static function addRoute(string $route, Controller $con) : void
  {
    $route = self::getRouteSlash($route);  // Fix route if needed to
    if (self::routeExists($route, $con))
      throw new Exception("Route " . $route . " already used for " . self::$routes[$route]::class);
    self::$routes[$route] = $con;
  }

  /**
   * Checks if a route exists in routes array
   *
   * @param string $route Route (with starting '/')
   * @return boolean True if exists, false otherwise
   */
  private static function routeExists(string $route, Controller $con = null) : bool
  {
    if (isset(self::$routes[$route]))
      if ($con === null)
        return true;
      elseif ($con !== null && self::$routes[$route] !== $con)
        return true;
    return false;
  }

  /**
   * Get the route (makes sure it DOES starts with a '/')
   *
   * @param string $route Route
   * @return string Route, starting with a '/'
   */
  private static function getRouteSlash(string $route) : string
  {
    if ($route[0] !== "/")
      $route = "/" . $route;
    return $route;
  }

  /**
   * Get the route (makes sure it does NOT start with a '/')
   *
   * @param string $route Route
   * @return string Route, not starting with a '/'
   */
  private static function getRouteNoSlash(string $route) : string
  {
    if ($route === "/")
      $route = substr($route, 1);
    return $route;
  }
}
