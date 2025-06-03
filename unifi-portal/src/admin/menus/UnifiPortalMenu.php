<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class UnifiPortalMenu
 *
 * Registers the "Unifi Portal" menu in the WordPress admin dashboard.
 */
class UnifiPortalMenu
{
    /**
     * Initialize the admin menu for the Unifi Portal and register filters.
     *
     * This method adds the main menu item, registers the filter for script tag modification, and hooks it into WordPress.
     *
     * @return void
     */
    public static function init()
    {
        add_menu_page(
            'Unifi Portal',
            'Unifi Portal',
            'manage_options',
            'unifi-portal',
            [self::class, 'display_page'],
            Plugin::image_url("icons/unifi-logo.svg"),
            55,
        );

        add_filter('script_loader_tag', [self::class, 'add_id_to_script'], 10, 3);
    }

    /**
     * Add custom attributes to the script tag.
     *
     * @param string $tag    The script tag.
     * @param string $handle The handle of the script.
     * @param string $src    The source URL of the script.
     * @return string Modified script tag.
     */
    public static function add_id_to_script($tag, $handle, $src) {}

    /**
     * Display the content for the Unifi Portal admin page.
     *
     * This method is called when the user clicks on the "Unifi Portal" menu item.
     *
     * @return void
     */
    public static function display_page()
    {

        echo sprintf(
            '<div id="unifi-portal-admin"><h3 class="loading-text">%s</h3></div>',
            esc_html__('Loading UniFi Portal...', UNI_PLUGIN_SLUG)
        );
    }
}
