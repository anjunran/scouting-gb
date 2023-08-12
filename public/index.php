<?php


class URLWatcher
{
    private $fetchPageFunction;

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
}

$fetchPageFunction = function (...$urlSegments): void {
    $allowedFiles = [
        '',
        'home'
    ];
    $absoluteUIPath = '../src/App.linker.php';
    $requestedFileKey = $urlSegments[0] ?? '';

    if (!is_string($requestedFileKey) || !in_array($requestedFileKey, $allowedFiles, true)) {
        die('Invalid file requested');
    }

    $absoluteUIPath = realpath($absoluteUIPath);
    if ($absoluteUIPath === false) {
        die('Invalid file path');
    }

    if (!is_file($absoluteUIPath) || !is_readable($absoluteUIPath)) {
        die('Access denied');
    }

    try {
        require_once $absoluteUIPath;
        fetchLink(...$urlSegments);
    } catch (Throwable $e) {
        die('An error occurred');
    }
};

$URLWatcher = new URLWatcher($fetchPageFunction);
$URLWatcher->fetchPage();

function sanitizeInput($input): string
{
    return htmlspecialchars($input);
}