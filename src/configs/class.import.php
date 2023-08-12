<?php
define("PROJECT_ROOT_DIR", dirname(__DIR__));
define("COMPONENTS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "components");
define("DEPENDENCIES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "dependencies");
define("FAVICONS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "favicons");
define("LIBRAIRIES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "libs");
define("MODULES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "modules");
define("PAGES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "pages");
define("ROUTE_FILES_INDEXER", "dependencies" . DIRECTORY_SEPARATOR . "hooks" . DIRECTORY_SEPARATOR . "files.index.php");
define("ROUTERS_MANAGER", "dependencies" . DIRECTORY_SEPARATOR . "hooks" . DIRECTORY_SEPARATOR . "useRouter.php");
define("ROUTES_MANAGER", "dependencies" . DIRECTORY_SEPARATOR . "hooks" . DIRECTORY_SEPARATOR . "useRoutes.php");
define("ROUTES_HANDLER", "pages" . DIRECTORY_SEPARATOR . "routes.handler.php");
define("AUTHENTICATOR", "dependencies" . DIRECTORY_SEPARATOR . "hooks" . DIRECTORY_SEPARATOR . "checkIfAuthenticated.php");
define("ROUTERS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "routers");
define("APP_ROUTER", "routers" . DIRECTORY_SEPARATOR . "router.handler.php");
define("AUTH_NAVBAR", "components" . DIRECTORY_SEPARATOR . "menus" . DIRECTORY_SEPARATOR . "auth.navbar.php");
define("HOME_NAVBAR", "components" . DIRECTORY_SEPARATOR . "menus" . DIRECTORY_SEPARATOR . "home.navbar.php");
define("ROUTE_PARAMS_FETCHER", "modules" . DIRECTORY_SEPARATOR . "ParamGrab.php");
define("APP_INDEX_HTML", "App.index.php");
define("INDEX_HTML_HEAD", "App.head.php");
define("JS_BUNDLES", "libs" . DIRECTORY_SEPARATOR . "SCRIPTS" . DIRECTORY_SEPARATOR . "js.index.php");
class DependencyImport
{
    private $rootDir;
    private $props;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function from($filePath, $payloads)
    {
        $fullPath = $this->rootDir . DIRECTORY_SEPARATOR . $filePath;
        $this->props = $payloads;
        if (file_exists($fullPath)) {

            $props = $this->props;
            require_once $fullPath;
            return true;
        } else {
            throw new Exception("Error [Package import] : Check file paths. Ensure that the file paths in your code match the actual locations of the dependency files.");
        }
    }
}

class MyPackage
{
    private static $dependencyImport = null;

    private static function initializeDependencyImport()
    {
        if (self::$dependencyImport === null) {
            self::$dependencyImport = new DependencyImport(PROJECT_ROOT_DIR);
        }
    }

    public static function getDependencyImport()
    {
        self::initializeDependencyImport();
        return self::$dependencyImport;
    }

    public static function import($path, $payloads = null)
    {
        try {
            self::getDependencyImport()->from($path, $payloads);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}