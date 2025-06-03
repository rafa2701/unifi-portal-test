<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Admin Vite Asset Manager
 *
 * Handles loading of Vite-bundled assets in WordPress admin pages.
 */
final class ViteAdmin extends ViteBase
{
    private const ADMIN_ENTRY_PREFIX = 'unifi-portal-admin';
    private const ADMIN_TEMPLATE_PATH = 'src/admin/templates/';
    private const SCRIPTS_HOOK = 'admin_enqueue_scripts';

    private array $pageHooks = [];

    /**
     * Register assets with WordPress
     */
    public function register(): void
    {
        add_action(self::SCRIPTS_HOOK, function () {
            if (!$this->shouldLoadOnPage()) {
                return;
            }

            if ($this->isViteServerRunning) {
                $this->loadDevAssets();
            } else {
                $this->loadProdAssets();
            }

            $this->addScriptAttributes();
        });
    }

    /**
     * Set entry file path for admin
     *
     * @param string $entryFile Path to entry file
     * @return self
     */
    public function setEntryFile(string $entryFile): self
    {
        if ($this->isViteServerRunning) {
            $this->entryFile = self::ADMIN_TEMPLATE_PATH . $entryFile;
        } else {
            $this->entryFile = $entryFile;
        }

        $this->debugLog("Set admin entry file: {$this->entryFile}");
        return $this;
    }

    /**
     * Load development assets
     */
    private function loadDevAssets(): void
    {
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
    }

    /**
     * Load production assets
     */
    private function loadProdAssets(): void
    {
        $manifest = $this->getManifest();

        if (empty($manifest)) {
            $this->debugLog("Admin Manifest Path: " . $this->manifestPath);
            return;
        }

        $entryPath = self::ADMIN_TEMPLATE_PATH . $this->entryFile;
        $this->debugLog("Looking for manifest entry: " . $entryPath);
        $this->debugLog("Available manifest keys:", array_keys($manifest));

        $entry = $manifest[$entryPath] ?? null;
        $this->debugLog("Entry found:", $entry);

        if (!$entry) {
            return;
        }

        $this->processedDeps = [];
        $deps = $this->processManifestEntry($entry, $manifest);

        if (!empty($this->localizeData)) {
            $mainHandle = $this->generateHandle($entry['file']);
            wp_localize_script(
                $mainHandle,
                $this->localizeData['name'],
                $this->localizeData['data']
            );
        }
    }

    /**
     * Check if current admin page should load assets
     *
     * @return bool True if assets should be loaded
     */
    protected function shouldLoadOnPage(): bool
    {
        if (empty($this->pages)) {
            $this->debugLog("No page restrictions - allowing all admin pages");
            return true;
        }

        if (!is_admin()) {
            $this->debugLog("Not in admin context - blocking load");
            return false;
        }

        global $pagenow;
        $screen = get_current_screen();
        $current_page = $_GET['page'] ?? '';

        foreach ($this->pages as $page) {
            if ($current_page === $page) {
                $this->debugLog("Asset loading allowed for admin page: $page");
                return true;
            }

            if (isset($this->pageHooks[$page]) && $screen && $screen->id === $this->pageHooks[$page]) {
                $this->debugLog("Asset loading allowed for admin hook: {$this->pageHooks[$page]}");
                return true;
            }
        }

        $this->debugLog("Current admin page ($current_page) not in allowed pages");
        return false;
    }

    /**
     * Set page hooks for specific admin pages
     *
     * @param array $hooks Page hooks mapping
     * @return self
     */
    public function setPageHooks(array $hooks): self
    {
        $this->pageHooks = $hooks;
        $this->debugLog("Set page hooks: " . json_encode($hooks));
        return $this;
    }
}
