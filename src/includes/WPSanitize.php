<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Utility class for WordPress sanitization functions.
 * Provides methods to sanitize various types of data.
 */
class WPSanitize
{
    /**
     * Sanitize a plain text field.
     *
     * @param string $text The text to sanitize.
     * @return string The sanitized text.
     */
    public static function text_field($text)
    {
        return sanitize_text_field($text);
    }

    /**
     * Sanitize a textarea field.
     *
     * @param string $textarea The textarea value to sanitize.
     * @return string The sanitized value.
     */
    public static function textarea_field($textarea)
    {
        return sanitize_textarea_field($textarea);
    }

    /**
     * Sanitize an email address.
     *
     * @param string $email The email address to sanitize.
     * @return string The sanitized email address.
     */
    public static function email($email)
    {
        return sanitize_email($email);
    }

    /**
     * Sanitize a file name by stripping invalid characters.
     *
     * @param string $file_name The file name to sanitize.
     * @return string The sanitized file name.
     */
    public static function file_name($file_name)
    {
        return sanitize_file_name($file_name);
    }

    /**
     * Sanitize a hexadecimal color code (with #).
     *
     * @param string $color The color code to sanitize.
     * @return string The sanitized color code.
     */
    public static function hex_color($color)
    {
        return sanitize_hex_color($color);
    }

    /**
     * Sanitize a hexadecimal color code (without #).
     *
     * @param string $color The color code to sanitize.
     * @return string The sanitized color code.
     */
    public static function hex_color_no_hash($color)
    {
        return sanitize_hex_color_no_hash($color);
    }

    /**
     * Sanitize a CSS class name.
     *
     * @param string $class The class name to sanitize.
     * @param string $fallback The fallback value if the class name is invalid.
     * @return string The sanitized class name.
     */
    public static function html_class($class, $fallback = '')
    {
        return sanitize_html_class($class, $fallback);
    }

    /**
     * Sanitize a key (e.g., a variable name).
     *
     * @param string $key The key to sanitize.
     * @return string The sanitized key.
     */
    public static function key($key)
    {
        return sanitize_key($key);
    }

    /**
     * Sanitize an SQL `ORDER BY` clause.
     *
     * @param string $orderby The `ORDER BY` value to sanitize.
     * @return string The sanitized `ORDER BY` value.
     */
    public static function sql_orderby($orderby)
    {
        return sanitize_sql_orderby($orderby);
    }

    /**
     * Sanitize a meta field value.
     *
     * @param string $meta_key The meta key.
     * @param mixed $meta_value The meta value to sanitize.
     * @param string $object_type The object type (e.g., post, user).
     * @param string $object_subtype The object subtype (optional).
     * @return mixed The sanitized meta value.
     */
    public static function meta($meta_key, $meta_value, $object_type, $object_subtype = '')
    {
        return sanitize_meta($meta_key, $meta_value, $object_type, $object_subtype);
    }

    /**
     * Sanitize a term (taxonomy) object or array.
     *
     * @param mixed $term The term to sanitize (either an array or an object).
     * @param string $taxonomy The taxonomy.
     * @param string $context The context in which to sanitize the term.
     * @return mixed The sanitized term.
     */
    public static function term($term, $taxonomy, $context = 'display')
    {
        return sanitize_term($term, $taxonomy, $context);
    }

    /**
     * Sanitize a specific term field value.
     *
     * @param string $field The field value to sanitize.
     * @param string $value The value to sanitize.
     * @param int $term_id The term ID.
     * @param string $taxonomy The taxonomy.
     * @param string $context The context in which to sanitize the field.
     * @return mixed The sanitized field value.
     */
    public static function term_field($field, $value, $term_id, $taxonomy, $context)
    {
        return sanitize_term_field($field, $value, $term_id, $taxonomy, $context);
    }

    /**
     * Sanitize a title string.
     *
     * @param string $title The title to sanitize.
     * @param string $fallback_title The fallback title (optional).
     * @param string $context The context in which to sanitize the title.
     * @return string The sanitized title.
     */
    public static function title($title, $fallback_title = '', $context = 'save')
    {
        return sanitize_title($title, $fallback_title, $context);
    }

    /**
     * Sanitize a title with dashes.
     *
     * @param string $title The title to sanitize.
     * @param string $raw_title The raw title (optional).
     * @param string $context The context in which to sanitize the title.
     * @return string The sanitized title.
     */
    public static function title_with_dashes($title, $raw_title = '', $context = 'display')
    {
        return sanitize_title_with_dashes($title, $raw_title, $context);
    }

    /**
     * Sanitize a user string.
     *
     * @param string $username The username to sanitize.
     * @param bool $strict Whether to use strict mode for sanitization (optional).
     * @return string The sanitized username.
     */
    public static function user($username, $strict = false)
    {
        return sanitize_user($username, $strict);
    }

    /**
     * Sanitize a URL.
     *
     * @param string $url The URL to sanitize.
     * @param array $protocols The allowed protocols (optional).
     * @return string The sanitized URL.
     */
    public static function url($url, $protocols = null)
    {
        return sanitize_url($url, $protocols);
    }

    /**
     * Sanitize content for safe HTML output.
     *
     * @param string $content The content to sanitize.
     * @param array|string $allowed_html An array or string of allowed HTML tags and attributes.
     * @param array $allowed_protocols The allowed protocols for links (optional).
     * @return string The sanitized content.
     */
    public static function kses($content, $allowed_html = [], $allowed_protocols = [])
    {
        return wp_kses($content, $allowed_html, $allowed_protocols);
    }

    /**
     * Sanitize content for safe HTML output with limited tags.
     *
     * @param string $data The content to sanitize.
     * @return string The sanitized content.
     */
    public static function kses_post($data)
    {
        return wp_kses_post($data);
    }
}
