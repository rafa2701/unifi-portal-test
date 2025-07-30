<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Admin Menu Assets Cleanup
 *
 * Manages WordPress admin assets for the Unifi Portal menu pages
 * by removing Bricks theme assets while preserving core functionality
 */
final class AdminMenuCleanup
{
    /** @var array Bricks theme admin assets to remove */
    protected const BRICKS_ADMIN_ASSETS = [
        'styles' => [
            'bricks-admin',
            'bricks-admin-menu',
            'bricks-admin-settings',
            'bricks-animations',
            'bricks-element-accordion',
            'bricks-element-alert',
            'bricks-element-audio',
            'bricks-element-button',
            'bricks-element-carousel',
            'bricks-element-container',
            'bricks-element-counter',
            'bricks-element-form',
            'bricks-element-icon',
            'bricks-element-image',
            'bricks-element-list',
            'bricks-element-map',
            'bricks-element-nav-menu',
            'bricks-element-pagination',
            'bricks-element-pricing-tables',
            'bricks-element-progress-bar',
            'bricks-element-search',
            'bricks-element-sidebar',
            'bricks-element-slider',
            'bricks-element-social',
            'bricks-element-tabs',
            'bricks-element-team-members',
            'bricks-element-testimonials',
            'bricks-element-video',
            'bricks-tooltips'
        ],
        'scripts' => [
            'bricks-admin',
            'bricks-admin-menu',
            'bricks-admin-settings',
            'bricks-scripts',
            'bricks-frontend',
            'bricks-flatpickr',
            'bricks-photoswipe',
            'bricks-element-accordion',
            'bricks-element-carousel',
            'bricks-element-counter',
            'bricks-element-form',
            'bricks-element-map',
            'bricks-element-nav-menu',
            'bricks-element-slider',
            'bricks-element-tabs'
        ]
    ];

    /**
     * Initialize admin menu cleanup
     */
    public static function init(): void
    {
        $instance = new self();
        $instance->registerHooks();
    }

    /**
     * Register WordPress hooks
     */
    protected function registerHooks(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'cleanupAdminAssets'], 999);
        add_filter('admin_body_class', [$this, 'addCustomBodyClass']);
        add_filter('bricks_admin_load_assets', '__return_false');
    }

    /**
     * Add custom body class to Unifi Portal admin pages
     */
    public function addCustomBodyClass(string $classes): string
    {
        if ($this->isUnifiPortalPage()) {
            $classes .= ' unifi-portal-admin-page';
        }
        return $classes;
    }

    /**
     * Remove Bricks theme admin assets
     */
    public function cleanupAdminAssets(): void
    {
        if (!$this->isUnifiPortalPage()) {
            return;
        }

        foreach (self::BRICKS_ADMIN_ASSETS['styles'] as $handle) {
            wp_deregister_style($handle);
            wp_dequeue_style($handle);
        }

        foreach (self::BRICKS_ADMIN_ASSETS['scripts'] as $handle) {
            wp_deregister_script($handle);
            wp_dequeue_script($handle);
        }
    }

    /**
     * Check if current page is a Unifi Portal admin page
     */
    protected function isUnifiPortalPage(): bool
    {
        return isset($_GET['page']) && strpos($_GET['page'], 'unifi-portal') === 0;
    }
}
