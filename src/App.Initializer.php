<?php
require_once("configs/class.import.php");
class AppInitializerV1 {
    private string $url;

    public function __construct(string $url = "") {
        $this->url = $this->sanitizeUrl($url);
    }

    private function sanitizeUrl(string $url): string {
        return !empty($url) ? filter_var($url, FILTER_SANITIZE_URL) : "";
    }

    public function initialize(): void {
        MyPackage::import(ROUTE_FILES_INDEXER);
        MyPackage::import(ROUTERS_MANAGER);
        MyPackage::import(AUTHENTICATOR);
        MyPackage::import(ROUTES_MANAGER);
        MyPackage::import(APP_INDEX_HTML, $this->url);
    }
}

class AppInitializerV2 {
    private string $url;
    private array $importedPackages = [];

    public function __construct(string $url = "") {
        $this->url = $this->sanitizeUrl($url);
    }

    private function sanitizeUrl(string $url): string {
        return !empty($url) ? filter_var($url, FILTER_SANITIZE_URL) : "";
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function addPackage(string $packagePath, string $arg = null): void {
        $this->importedPackages[$packagePath] = $arg;
    }

    public function hasPackage(string $packageName): bool {
        return isset($this->importedPackages[$packageName]);
    }

    public function removePackage(string $packagePath): void {
        unset($this->importedPackages[$packagePath]);
    }

    public function reset(): void {
        $this->importedPackages = [];
    }

    public function logInitialization(): void {
        error_log("App initialized with URL: " . $this->url);
    }

    public function initialize(): void {
        foreach ($this->importedPackages as $packagePath => $arg) {
            MyPackage::import($packagePath, $arg ?? null);
        }
        $this->logInitialization();
    }
}