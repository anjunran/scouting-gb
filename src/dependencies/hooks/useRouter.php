<?php
function useRouter(array $routes, string $page): string
{
    $routeNodes = new RouteNodes(...$routes);

    $errorPage = function () {
        return "not.found.404.php";
    };

    $pageRoutes = $routeNodes->setRouter($page, $errorPage);

    return array_values($pageRoutes)[0][$page];
}