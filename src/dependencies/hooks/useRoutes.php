<?php
class RoutesHandler {
    private string $projectRootDir;
    private array $routes = [];

    public function __construct(string $projectRootDir) {
        $this->projectRootDir = $projectRootDir;
    }

    public function handleFile(string $content, string $noContent = "error.404.php"): void {
        $filePath = $this->projectRootDir . DIRECTORY_SEPARATOR . "pages/routes/{$content}";
        $fileNotFoundPath = $this->projectRootDir . DIRECTORY_SEPARATOR . "pages/routes/{$noContent}";

        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            require_once $fileNotFoundPath;
        }
    }
    public function redirect(string $location): void
    {
        header("Location: {$location}");
        exit;
    }

    public function isFileExist(string $route): bool
    {
        return file_exists($route);
    }

    private function generateFullPath(string $relativePath): string
    {
        return $this->projectRootDir . DIRECTORY_SEPARATOR . $relativePath;
    }

    public function listAllRoutes(): array
    {
        $dir = $this->generateFullPath("pages/routes/");
        return array_diff(scandir($dir), ['..', '.']);
    }

    public function getCurrentRoute(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function setHeader(string $header): void
    {
        header($header);
    }

    public function getResponseCode(): int
    {
        return http_response_code();
    }

    public function setResponseCode(int $code): void
    {
        http_response_code($code);
    }

    public function getProjectRootDir(): string
    {
        return $this->projectRootDir;
    }

    public function setProjectRootDir(string $dir): void
    {
        $this->projectRootDir = $dir;
    }

    public function getAllRoutes(): array {
        return $this->routes;
    }

    public function getRouteForPath(string $path): ?string {
        foreach ($this->routes as $route => $file) {
            if (strpos($path, $route) === 0) {
                return $file;
            }
        }
        return null;
    }

    public function addRoute(string $route, string $file): void {
        $this->routes[$route] = $file;
    }

    public function removeRoute(string $route): void {
        if (isset($this->routes[$route])) {
            unset($this->routes[$route]);
        }
    }

    public function updateRoute(string $route, string $file): void {
        if (isset($this->routes[$route])) {
            $this->routes[$route] = $file;
        }
    }
}