<?php
class RouteNodes
{
    private $page;
    private $route;
    private $defaultRoute;
    public $filesIndex = [];

    public function __construct(...$routes)
    {
        $this->filesIndex = $routes;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage(string $page)
    {
        $this->page = filter_var($page, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getDefaultRoute()
    {
        return $this->defaultRoute;
    }

    public function setDefaultRoute(string $route)
    {
        $this->defaultRoute = $route;
    }

    public function hasRoute(string $route)
    {
        return in_array($route, $this->filesIndex);
    }

    public function addMiddleware(callable $middleware)
    {
        // Add middleware functionality, for example:
        if ($middleware()) {
            // Middleware returned true, proceed with the route
            return true;
        } else {
            // Middleware returned false, stop routing
            return false;
        }
    }

    public function getRoutes()
    {
        return $this->filesIndex;
    }

    public function clearRoutes()
    {
        $this->filesIndex = [];
    }

    public function countRoutes()
    {
        return count($this->filesIndex);
    }

    public function getRouteByKey(string $key)
    {
        return $this->filesIndex[$key] ?? null;
    }

    public static function route(string $keyRoute, callable $file): array
    {
        $node = [];
        $keyRoute = explode("/", $keyRoute);
        $node[$keyRoute[array_key_exists(1, $keyRoute) ? 1 : 0]] = $file();
        return $node;
    }

    public static function authenticatedRouter(string $authenticatedFile = 'app.php', string $unauthenticatedFile = 'login.php'): callable
    {
        return function () use ($authenticatedFile, $unauthenticatedFile) {
            if (checkIfAuthenticated()) {
                return $authenticatedFile;
            } else {
                return $unauthenticatedFile;
            }
        };
    }

    public function setRouter(string $page, callable $errorPage): array
    {
        $page = filter_var($page, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->page = $page;
        $this->route = array_filter($this->filesIndex, function ($route) {
            return array_key_exists($this->page, $route);
        });

        if (!empty($this->route)) {
            return $this->route;
        } else {
            return [
                [
                    $page => $errorPage()
                ]
            ];
        }
    }
}