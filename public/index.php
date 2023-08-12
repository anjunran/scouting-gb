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
        'fetchpage' => '../src/App.linker.php'
    ];

    $requestedFileKey = $urlSegments[0] ?? '';
    if (!isset($allowedFiles[$requestedFileKey])) {
        die('Invalid file requested');
    }

    require_once $allowedFiles[$requestedFileKey];
    fetchLink(...$urlSegments);
};

$URLWatcher = new URLWatcher($fetchPageFunction);
$URLWatcher->fetchPage();

function sanitizeInput($input): string
{
    return htmlspecialchars($input);
}