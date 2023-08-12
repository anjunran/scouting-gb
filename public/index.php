class URLWatcher
{
    private $fetchPageFunction;
    private $errorMessages = [
        'invalid_domain' => 'Sorry, the destination of your link isn\'t associated with this domain.',
        'invalid_filepath' => 'Invalid file path',
        'access_denied' => 'Access denied',
        'generic_error' => "An issue has been detected. It's crucial to adhere to the directive provided above to proceed."
    ];

    public function __construct(callable $fetchPageFunction)
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

        call_user_func_array($this->fetchPageFunction, $urlSegments);
    }

    public function getErrorMessage($key): string
    {
        return $this->errorMessages[$key] ?? 'Unknown error';
    }
}

$fetchPageFunction = function (...$urlSegments) use ($URLWatcher): void {
    $allowedFiles = [
        '',
        'home',
        'dashboard'
    ];
    $absoluteUIPath = '../src/App.linker.php';
    $requestedFileKey = $urlSegments[0] ?? '';

    if (!is_string($requestedFileKey) || !in_array($requestedFileKey, $allowedFiles, true)) {
        die($URLWatcher->getErrorMessage('invalid_domain'));
    }

    $absoluteUIPath = realpath($absoluteUIPath);
    if ($absoluteUIPath === false) {
        die($URLWatcher->getErrorMessage('invalid_filepath'));
    }

    if (!is_file($absoluteUIPath) || !is_readable($absoluteUIPath)) {
        die($URLWatcher->getErrorMessage('access_denied'));
    }

    try {
        require_once $absoluteUIPath;
        fetchLink(...$urlSegments);
    } catch (Throwable $e) {
        die('<span style="font-size:small; color:blue">[Application error] :</span> <i style="color:red; font-size:small">' . htmlspecialchars($URLWatcher->getErrorMessage('generic_error')) . '</i>');
    }
};

$URLWatcher = new URLWatcher($fetchPageFunction);
$URLWatcher->fetchPage();

function sanitizeInput($input): string
{
    return htmlspecialchars($input);
}