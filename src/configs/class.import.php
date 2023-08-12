<?php
define("PROJECT_ROOT_DIR", dirname(__DIR__));
define("COMPONENTS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "components");
define("DEPENDENCIES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "dependencies");
define("FAVICONS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "favicons");
define("LIBRAIRIES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "libs");
define("MODULES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "modules");
define("PAGES_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "pages");
define("ROUTE_FILES_INDEXER", "dependencies/hooks/files.index.php");
define("ROUTERS_MANAGER", "dependencies/hooks/useRouter.php");
define("ROUTES_MANAGER", "dependencies/hooks/useRoutes.php");
define("ROUTES_HANDLER", "pages/routes/routes.handler.php");
define("AUTHENTICATOR", "dependencies/hooks/checkIfAuthenticated.php");
define("ROUTERS_ROOT_DIR", PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "routers");
define("APP_ROUTER", "routers/router.handler.php");
define("AUTH_NAVBAR", "components/menus/auth.navbar.php");
define("HOME_NAVBAR", "components/menus/home.navbar.php");
define("APP_INDEX_HTML", "App.index.php");
define("INDEX_HTML_HEAD", "App.head.php");
define("JS_BUNDLES", "libs/SCRIPTS/js.index.php");
class DependencyImport
{
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function from($filePath)
    {
        $fullPath = $this->rootDir . DIRECTORY_SEPARATOR . $filePath;
        if (file_exists($fullPath)) {
            require_once $fullPath;
            return true;
        } else {
            throw new Exception("Error: Check file paths. Ensure that the file paths in your code match the actual locations of the dependency files.");
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

    public static function import($path)
    {
        try {
            self::getDependencyImport()->from($path);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
