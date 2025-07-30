<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Base Vite Asset Manager
 *
 * Provides core functionality for Vite asset management in WordPress.
 * Extended by specific context managers (admin/frontend).
 */
abstract class ViteBase
{
    /** @var string Base dist directory path */
    protected const DIST_PATH = 'dist/';

    /** @var bool Enable debug output */
    protected bool $debug = false;

    /** @var array Allowed pages for asset loading */
    protected array $pages = [];

    /** @var string|null Entry file path */
    protected ?string $entryFile = null;

    /** @var string|null WordPress enqueue handle */
    protected ?string $enqueueHandle = null;

    /** @var bool|null Vite server status */
    protected ?bool $isViteServerRunning = null;

    /** @var array Processed dependencies */
    protected array $processedDeps = [];

    /** @var array Registered script handles */
    protected array $registeredHandles = [];

    /** @var string Vite development server URL */
    protected string $viteUrl;

    /** @var int Vite development server port */
    protected int $vitePort;

    /** @var string Path to manifest file */
    protected string $manifestPath;

    /** @var array Data to be localized */
    protected array $localizeData = [];

    /**
     * Initialize Vite instance
     */
    public function __construct()
    {
        $this->viteUrl = defined('UNI_PLUGIN_VITE_URL') ? UNI_PLUGIN_VITE_URL : 'http://localhost';
        $this->vitePort = defined('UNI_PLUGIN_VITE_PORT') ? UNI_PLUGIN_VITE_PORT : 6173;
        $this->manifestPath = defined('UNI_PLUGIN_VITE_MANIFEST') ? UNI_PLUGIN_VITE_MANIFEST : UNI_PLUGIN_PATH . self::DIST_PATH . 'assets.json';

        $this->isViteServerRunning = $this->checkViteServer();
    }

    /**
     * Enable debug mode
     */
    public function debug(bool $enable = false): self
    {
        $this->debug = $enable;
        $this->debugLog('Debug mode ' . ($enable ? 'enabled' : 'disabled'));
        return $this;
    }

    /**
     * Set allowed pages
     */
    public function page(array $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * Set entry file path
     */
    abstract public function setEntryFile(string $entryFile): self;

    /**
     * Set WordPress enqueue handle
     */
    public function setEnqueueHandle(string $handle): self
    {
        $this->enqueueHandle = $handle;
        return $this;
    }

    /**
     * Set data to be localized
     */
    public function setLocalizeData(string $name, array $data): self
    {
        $this->localizeData = [
            'name' => $name,
            'data' => $data
        ];
        return $this;
    }

    /**
     * Register assets with WordPress
     */
    abstract public function register(): void;

    /**
     * Check if page should load assets
     */
    abstract protected function shouldLoadOnPage(): bool;

    /**
     * Process manifest entry and dependencies
     */
    protected function processManifestEntry(array $entry, array $manifest, array $parentDeps = []): array
    {
        $deps = $parentDeps;
        $file = $entry['file'] ?? '';

        if (empty($file) || isset($this->processedDeps[$file])) {
            return $deps;
        }

        $this->processedDeps[$file] = true;
        $handle = $this->generateHandle($file);

        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                $cssHandle = $this->generateHandle($cssFile);
                wp_enqueue_style(
                    $cssHandle,
                    $this->getAssetUrl($cssFile),
                    [],
                    null
                );
                $this->registeredHandles[] = $cssHandle;
            }
        }

        if (str_ends_with($file, '.js')) {
            wp_enqueue_script(
                $handle,
                $this->getAssetUrl($file),
                $deps,
                null,
                true
            );
            $this->registeredHandles[] = $handle;
            $deps[] = $handle;

            if (!empty($entry['imports'])) {
                foreach ($entry['imports'] as $import) {
                    if (isset($manifest[$import])) {
                        $deps = $this->processManifestEntry($manifest[$import], $manifest, $deps);
                    }
                }
            }
        }

        return $deps;
    }

    /**
     * Generate WordPress handle for asset
     */
    protected function generateHandle(string $file): string
    {
        $base = pathinfo($file, PATHINFO_FILENAME);
        $key = str_replace(['@', '+', '-', '.'], '_', $base);
        $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
        return "{$this->enqueueHandle}_{$key}";
    }

    /**
     * Get full URL for asset
     */
    protected function getAssetUrl(string $file): string
    {
        $file = ltrim($file, '/');
        $url = UNI_PLUGIN_URL . self::DIST_PATH . $file;
        $this->debugLog("Generated asset URL for {$file}: {$url}");
        return $url;
    }

    /**
     * Add required script attributes
     */
    protected function addScriptAttributes(): void
    {
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if (!in_array($handle, $this->registeredHandles)) {
                return $tag;
            }

            if (str_ends_with($src, '.js')) {
                $newTag = sprintf(
                    '<script type="module" crossorigin="anonymous" src="%s"></script>',
                    esc_url($src)
                );
                $this->debugLog("Modified script tag for {$handle}: {$newTag}");
                return $newTag;
            }

            return $tag;
        }, 10, 3);
    }

    /**
     * Check if Vite dev server is running
     */
    protected function checkViteServer(): bool
    {
        $serverAddress = parse_url($this->viteUrl, PHP_URL_HOST);
        $socket = @fsockopen($serverAddress, $this->vitePort, $errno, $errstr, 1);

        if ($socket) {
            fclose($socket);
            $this->debugLog("Dev server running at {$this->viteUrl}:{$this->vitePort}");
            return true;
        }

        return false;
    }

    /**
     * Get manifest contents
     */
    protected function getManifest(): array
    {
        if (!file_exists($this->manifestPath)) {
            $this->debugLog("Manifest file not found at: {$this->manifestPath}");
            return [];
        }

        $manifest = json_decode(file_get_contents($this->manifestPath), true);

        if (!is_array($manifest)) {
            $this->debugLog('Invalid manifest format');
            return [];
        }

        return $manifest;
    }

    /**
     * Log debug message
     */
    protected function debugLog(string $message): void
    {
        if ($this->debug) {
            pr("Vite: $message");
        }
    }
}
