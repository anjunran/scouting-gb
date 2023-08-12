<?php
class URLWatcherErrors
{
    private $errorMessages = [
        'invalid_domain' => 'Sorry, the destination of your link isn\'t associated with this domain.',
        'invalid_filepath' => 'Invalid file path',
        'access_denied' => 'Access denied',
        'generic_error' => "An issue has been detected. It's crucial to adhere to the directive provided above to proceed."
    ];
    public function getErrorMessage($key): string
    {
        return $this->errorMessages[$key] ?? 'Unknown error';
    }
}
class URLWatcher
{
    private $fetchPageFunction;

    public function __construct(callable $fetchPageFunction = null)
    {
        $this->fetchPageFunction = $fetchPageFunction;
    }

    public function fetchPage(): void
    {
        $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_URL);
        parse_str($queryString, $params);

        $urlParam = $params['url'] ?? '';
        $urlSegments = explode('/', $urlParam);
        $urlSegments = array_map('sanitizeInput', $urlSegments);

        try {
            call_user_func_array($this->fetchPageFunction, $urlSegments);
        } catch (Throwable $e) {
            die($e->getMessage());
        }
    }
}

$PageRequest = function (...$urlSegments): void {
    $URLErrors = new URLWatcherErrors;
    $absoluteUIPath = '../src/App.Initializer.php';
    $settingsPath = '../src/App.settings.php';
    $requestedFileKey = $urlSegments[0] ?? '';
    require_once($settingsPath);

    if (!is_string($requestedFileKey) || !in_array($requestedFileKey, $allowedFiles, true)) {
        throw new Exception($URLErrors->getErrorMessage('invalid_domain'));
    }

    $absoluteUIPath = realpath($absoluteUIPath);
    if ($absoluteUIPath === false) {
        throw new Exception($URLErrors->getErrorMessage('invalid_filepath'));
    }

    if (!is_file($absoluteUIPath) || !is_readable($absoluteUIPath)) {
        throw new Exception($URLErrors->getErrorMessage('access_denied'));
    }

    try {
        require_once $absoluteUIPath;

        //App initialization
        $appInitializer = new AppInitializerV2(...$urlSegments);
        $appInitializer->addPackage(ROUTE_FILES_INDEXER);
        $appInitializer->addPackage(ROUTERS_MANAGER);
        $appInitializer->addPackage(AUTHENTICATOR);
        $appInitializer->addPackage(ROUTES_MANAGER);
        $appInitializer->addPackage(APP_INDEX_HTML, $appInitializer->getUrl());
        $appInitializer->initialize();

    } catch (Throwable $e) {
        echo $e->getMessage();
        throw new Exception('[Application error]: ' . $URLErrors->getErrorMessage('generic_error'));
    }
};
function sanitizeInput(string $input): string
{
    return htmlspecialchars($input);
}