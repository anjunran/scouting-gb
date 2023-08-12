<?php
MyPackage::import(ROUTERS_MANAGER);
MyPackage::import(AUTHENTICATOR);

$routes = [
    RouteNodes::route("/", function () {
        return "home.php";
    }),
    RouteNodes::route("/home", function () {
        return "home.php";
    }),
    RouteNodes::route("/scouting", RouteNodes::authenticatedRouter("scouting-admin-app.php", "auth-login.php"))
];

$content = useRouter($routes, $CLEAN_URL);
MyPackage::import(ROUTES_HANDLER);