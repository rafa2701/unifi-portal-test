<?php

/**
 * Unifi Portal
 *
 * @package   unifi-portal
 * @author    S-FX.com Small Business Solutions.
 * @copyright 2024 December @ S-FX.com Small Business Solutions. All rights reserved.
 * @license   Proprietary
 * @version   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Unifi Portal
 * Plugin URI:        https://s-fx.com
 * Description:       A secure client portal for accessing UniFi network controllers, reports, and management tools.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            S-FX.com Small Business Solutions.
 * Author URI:        https://s-fx.com
 * Text Domain:       unifi-portal
 * Domain Path:       /languages
 * License:           Proprietary
 */

namespace Sfx\UnifiPortal;

/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initializes the plugin.
 *
 * @since 1.0.0
 */
function initialize_plugin(): void
{
    include_required_files();
    define_plugin_constants();
    include_startup_files();

    boot_plugin();
}

/**
 * Includes required WordPress core files.
 *
 * @since 1.0.0
 */
function include_required_files(): void
{
    if (!function_exists('is_plugin_active') || !function_exists('get_plugin_data')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if (!function_exists('wp_get_current_user')) {
        include_once ABSPATH . 'wp-includes/pluggable.php';
    }
}

/**
 * Defines plugin constants if they are not already defined.
 *
 * @since 1.0.0
 */
function define_plugin_constants(): void
{
    define_core_constants();
    define_plugin_info_constants();
    define_environment_constants();
}

/**
 * Defines core plugin constants.
 *
 * @since 1.0.0
 */
function define_core_constants(): void
{
    !defined('UNI_PLUGIN_FILE') && define('UNI_PLUGIN_FILE', __FILE__);

    !defined('UNI_PLUGIN_PATH') && define('UNI_PLUGIN_PATH', plugin_dir_path(UNI_PLUGIN_FILE));

    !defined('UNI_PLUGIN_URL') && define('UNI_PLUGIN_URL', plugin_dir_url(UNI_PLUGIN_FILE));

    !defined('UNI_PLUGIN_SRC_PATH') && define('UNI_PLUGIN_SRC_PATH', UNI_PLUGIN_PATH . 'src/');

    !defined('UNI_PLUGIN_NAMESPACE') && define('UNI_PLUGIN_NAMESPACE', __NAMESPACE__ . '\\');
}

/**
 * Defines constants related to plugin information.
 *
 * @since 1.0.0
 */
function define_plugin_info_constants(): void
{
    $plugin_data = get_plugin_data(UNI_PLUGIN_FILE);

    !defined('UNI_PLUGIN_TITLE') && define('UNI_PLUGIN_TITLE', $plugin_data['Title']);

    !defined('UNI_PLUGIN_SLUG') && define('UNI_PLUGIN_SLUG', $plugin_data['TextDomain']);

    !defined('UNI_PLUGIN_VERSION') && define('UNI_PLUGIN_VERSION', $plugin_data['Version']);
}

/**
 * Defines constants related to the plugin's environment.
 *
 * @since 1.0.0
 */
function define_environment_constants(): void
{
    // Set to 'PROD' for production or 'DEV' for local development
    !defined('UNI_PLUGIN_ENV_MODE') && define('UNI_PLUGIN_ENV_MODE', 'DEV');
    // !defined('UNI_PLUGIN_ENV_MODE') && define('UNI_PLUGIN_ENV_MODE', 'PROD');

    // Set to false for production or true to enable debug mode
    !defined('UNI_PLUGIN_DEBUG') && define('UNI_PLUGIN_DEBUG', true);
    // !defined('UNI_PLUGIN_DEBUG') && define('UNI_PLUGIN_DEBUG', false);
}

/**
 * Includes startup files for the plugin.
 *
 * @since 1.0.0
 */
function include_startup_files(): void
{

    include_once UNI_PLUGIN_SRC_PATH . 'debug/debug.php';

    include_once UNI_PLUGIN_SRC_PATH . 'boot/boot.php';
}

// Initialize the plugin
initialize_plugin();
