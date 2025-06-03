<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Handles WordPress admin HTML modifications and asset injections
 *
 * Manages custom styles and scripts for the UniFi Portal admin interface,
 * including menu modifications and loading states.
 */
class AdminWpHtml
{
    /** @var string Plugin menu identifier */
    protected const MENU_ID = 'toplevel_page_unifi-portal';

    /** @var string Controller post type menu identifier */
    protected const CONTROLLER_MENU_ID = 'menu-posts-unifi-controller';

    /**
     * Initializes admin area hooks
     */
    public static function init(): void
    {
        if (!is_admin()) {
            return;
        }

        add_action('admin_head', [self::class, 'head']);
        add_action('admin_footer', [self::class, 'footer']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
    }

    /**
     * Registers and enqueues admin assets
     */
    public static function enqueueAssets(): void
    {
        $handle = Plugin::prefix('admin-custom');

        wp_register_style(
            $handle,
            false,
            [],
            UNI_PLUGIN_VERSION
        );

        wp_add_inline_style($handle, self::getCustomStyles());
        wp_enqueue_style($handle);

        wp_add_inline_script(
            Plugin::prefix('admin'),
            self::getMenuScripts(),
            'after'
        );
    }

    /**
     * Outputs admin head content
     */
    public static function head(): void
    {
        if (!self::shouldLoadCustomizations()) {
            return;
        }

        echo "<!-- UniFi Portal Admin Customizations -->\n";
    }

    /**
     * Outputs admin footer content
     */
    public static function footer(): void
    {
        if (!self::shouldLoadCustomizations()) {
            return;
        }

        echo "<!-- UniFi Portal Admin Footer -->\n";
    }

    /**
     * Generates custom admin styles
     */
    protected static function getCustomStyles(): string
    {
        return "
            #" . self::MENU_ID . " .wp-menu-image.dashicons-before img,
            #" . self::CONTROLLER_MENU_ID . " .wp-menu-image.dashicons-before img {
                margin-top: -4px;
                margin-right: 0;
                margin-left: 4px;
                width: 23px;
            }

            #" . self::CONTROLLER_MENU_ID . " {
                display: none !important;
            }

            #unifi-portal-admin {
                min-height: 400px;
                position: relative;
            }

            #unifi-portal-admin .loading-text {
                text-align: center;
                padding-top: 100px;
                color: #666;
                font-weight: 500;
                font-family: Inter, sans-serif;
            }
        ";
    }

    /**
     * Generates menu manipulation scripts
     */
    protected static function getMenuScripts(): string
    {
        return "
            document.addEventListener('DOMContentLoaded', function() {
                const hideMenuSeparator = (menuId) => {
                    const menu = document.querySelector('#' + menuId);
                    if (!menu) return;

                    const separator = menu.nextElementSibling;
                    if (separator?.classList.contains('wp-menu-separator')) {
                        separator.style.display = 'none';
                    }
                };

                hideMenuSeparator('" . self::MENU_ID . "');
                hideMenuSeparator('" . self::CONTROLLER_MENU_ID . "');
            });
        ";
    }

    /**
     * Determines if admin customizations should be loaded
     */
    protected static function shouldLoadCustomizations(): bool
    {
        $screen = get_current_screen();
        if (!$screen) {
            return false;
        }

        return in_array($screen->id, [
            'toplevel_page_unifi-portal',
            'unifi-controller'
        ]);
    }
}
