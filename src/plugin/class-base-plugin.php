<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Base Plugin Class
 *
 * Provides utility methods for URL generation, plugin information, and nonces.
 *
 * @since 1.0.0
 */
class Base_Plugin
{
    /**
     * Cached site URL.
     *
     * @var string|null
     */
    private static $_site_url = null;

    /**
     * Cached assets URL.
     *
     * @var string|null
     */
    private static $_assets_url = null;

    /**
     * Get the site URL with an optional path appended.
     *
     * @param string|null $path Path to append to the site URL.
     * @return string The complete site URL.
     */
    public static function site_url($path = null)
    {
        if (self::$_site_url === null) {
            self::$_site_url = site_url();
        }
        return self::$_site_url . ($path ? "/" . ltrim($path, "/") : "");
    }

    /**
     * Get the admin URL with an optional path appended.
     *
     * @param string|null $path Path to append to the admin URL.
     * @return string The complete admin URL.
     */
    public static function admin_url($path = null)
    {
        return admin_url($path);
    }

    /**
     * Get the assets URL.
     *
     * @return string The base URL for the assets directory.
     */
    public static function assets_url()
    {
        if (self::$_assets_url === null) {
            self::$_assets_url = self::plugin_url() . "assets/";
        }
        return self::$_assets_url;
    }

    /**
     * Get the URL for an image file within the assets directory.
     *
     * @param string $path Path to the image file.
     * @return string The complete URL for the image.
     */
    public static function image_url($path = "")
    {
        return self::assets_url() . "images/" . $path;
    }

    /**
     * Get the URL for a CSS file within the assets directory.
     *
     * @param string $path Path to the CSS file.
     * @return string The complete URL for the CSS file.
     */
    public static function css_url($path = "")
    {
        return self::assets_url() . "css/" . $path;
    }

    /**
     * Get the URL for a JavaScript file within the assets directory.
     *
     * @param string $path Path to the JavaScript file.
     * @return string The complete URL for the JavaScript file.
     */
    public static function js_url($path = "")
    {
        return self::assets_url() . "js/" . $path;
    }

    /**
     * Get the URL for a font file within the assets directory.
     *
     * @param string $path Path to the font file.
     * @return string The complete URL for the font file.
     */
    public static function fonts_url($path = "")
    {
        return self::assets_url() . "fonts/" . $path;
    }

    /**
     * Get the URL for a vendor file within the assets directory.
     *
     * @param string $path Path to the vendor file.
     * @return string The complete URL for the vendor file.
     */
    public static function vendor_url($path = "")
    {
        return self::assets_url() . "vendor/" . $path;
    }

    /**
     * Alias for image_url().
     *
     * @param string $path Path to append to the images URL.
     * @return string The complete URL for the image.
     */
    public static function img($path = "")
    {
        return self::image_url($path);
    }

    /**
     * Get the plugin name.
     *
     * @return string The plugin name.
     */
    public static function name()
    {
        return self::title();
    }

    /**
     * Get the plugin title.
     *
     * @return string The plugin title.
     */
    public static function title()
    {
        return UNI_PLUGIN_TITLE;
    }

    /**
     * Get the plugin URL.
     *
     * @return string The base plugin URL.
     */
    public static function plugin_url()
    {
        return UNI_PLUGIN_URL;
    }

    /**
     * Get the plugin slug.
     *
     * @return string The plugin slug.
     */
    public static function slug()
    {
        return UNI_PLUGIN_SLUG;
    }

    /**
     * Get the URL for admin post handling.
     *
     * @return string The URL for admin-post.php.
     */
    public static function post_url()
    {
        return admin_url("admin-post.php");
    }

    /**
     * Generate a nonce for post requests.
     *
     * @return string The nonce for post requests.
     */
    public static function post_nonce()
    {
        return wp_create_nonce(UNI_PLUGIN_SLUG . "post");
    }

    /**
     * Get the URL for AJAX handling.
     *
     * @return string The URL for admin-ajax.php.
     */
    public static function ajax_url()
    {
        return admin_url("admin-ajax.php");
    }

    /**
     * Generate a nonce for AJAX requests.
     *
     * @return string The nonce for AJAX requests.
     */
    public static function ajax_nonce()
    {
        return wp_create_nonce(UNI_PLUGIN_SLUG . "ajax");
    }

    /**
     * Get the base URL for the plugin's REST API.
     *
     * @return string The REST API endpoint URL.
     */
    public static function rest_url()
    {
        return self::site_url() . "/wp-json/" . UNI_PLUGIN_SLUG . "/v1";
    }

    /**
     * Generate a nonce for REST API requests.
     *
     * @return string The nonce for REST API requests.
     */
    public static function rest_nonce()
    {
        return wp_create_nonce("wp_rest");
    }

    /**
     * Get the prefix for the plugin, optionally prefixed to a value.
     *
     * @param string $value Optional. The value to prefix.
     * @return string The plugin prefix or the prefixed value.
     */
    public static function prefix($value = "")
    {
        $prefix = str_replace("-", "_", strtolower(UNI_PLUGIN_SLUG)) . "_";

        return $value ? $prefix . $value : $prefix;
    }

    /**
     * Get the database prefix for the plugin, optionally prefixed to a value.
     *
     * @param string $value Optional. The value to prefix.
     * @return string The database prefix or the prefixed value.
     */
    public static function db_prefix($value = "")
    {
        $db_prefix = "sfx_unifi_";

        return $value ? $db_prefix . $value : $db_prefix;
    }
}
