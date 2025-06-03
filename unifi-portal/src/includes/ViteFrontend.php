<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Frontend Vite Asset Manager
 *
 * Handles loading of Vite-bundled assets in WordPress frontend pages.
 */
final class ViteFrontend extends ViteBase
{
    /** @var string Base path for frontend templates */
    private const FRONTEND_TEMPLATE_PATH = 'src/frontend/templates/';
    /**
     * Register assets with WordPress
     */
    public function register(): void
    {
        if (!$this->shouldLoadOnPage()) {
            $this->debugLog('Not loading frontend assets - page check failed');
            return;
        }

        if ($this->isViteServerRunning) {
            $this->registerDevAssets();
        } else {
            $this->registerProdAssets();
        }

        $this->addScriptAttributes();
    }

    /**
     * Register development assets
     */
    private function registerDevAssets(): void
    {
        add_action('wp_enqueue_scripts', function () {
            if (!$this->shouldLoadOnPage()) {
                return;
            }

            wp_register_script(
                $this->enqueueHandle . '-vite-client',
                "{$this->viteUrl}:{$this->vitePort}/@vite/client",
                [],
                null,
                true
            );
            wp_enqueue_script($this->enqueueHandle . '-vite-client');

            wp_register_script(
                $this->enqueueHandle,
                "{$this->viteUrl}:{$this->vitePort}/{$this->entryFile}",
                [],
                null,
                true
            );

            if (!empty($this->localizeData)) {
                wp_localize_script(
                    $this->enqueueHandle,
                    $this->localizeData['name'],
                    $this->localizeData['data']
                );
            }

            wp_enqueue_script($this->enqueueHandle);

            $this->registeredHandles[] = $this->enqueueHandle . '-vite-client';
            $this->registeredHandles[] = $this->enqueueHandle;
        });
    }

    /**
     * Register production assets
     */
    private function registerProdAssets(): void
    {
        $manifest = $this->getManifest();

        if (empty($manifest)) {
            return;
        }

        $entry = $manifest[$this->entryFile] ?? null;
        if (!$entry) {
            return;
        }

        add_action('wp_enqueue_scripts', function () use ($entry, $manifest) {
            if (!$this->shouldLoadOnPage()) {
                return;
            }

            $this->processedDeps = [];
            $this->processManifestEntry($entry, $manifest);

            if (!empty($this->localizeData)) {
                $mainHandle = $this->generateHandle($entry['file']);
                wp_localize_script(
                    $mainHandle,
                    $this->localizeData['name'],
                    $this->localizeData['data']
                );
            }
        });
    }

    /**
     * Set entry file path with frontend template prefix
     */
    public function setEntryFile(string $entryFile): self
    {
        $this->entryFile = self::FRONTEND_TEMPLATE_PATH . $entryFile;
        $this->debugLog("Set frontend entry file: {$this->entryFile}");
        return $this;
    }

    /**
     * Check if current frontend page should load assets
     */
    protected function shouldLoadOnPage(): bool
    {
        if (empty($this->pages)) {
            return true;
        }

        if (is_admin()) {
            return false;
        }

        $current_url = $_SERVER['REQUEST_URI'] ?? '';

        foreach ($this->pages as $page) {
            if (strpos($current_url, "/$page") === 0 || strpos($current_url, "/$page/") === 0) {
                $this->debugLog("Asset loading allowed for frontend page: $page");
                return true;
            }
        }

        $this->debugLog("Asset loading not allowed for frontend URL: $current_url");
        return false;
    }
}
