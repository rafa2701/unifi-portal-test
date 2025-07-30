<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class WPUtils
 *
 * Utility class for various WordPress functions.
 */
class WPUtils
{
    /**
     * Unscrambles a string that has been slashed by WordPress.
     *
     * @param string $value The value to unslash.
     * @return string The unslashed value.
     */
    public static function unslash($value)
    {
        return wp_unslash($value);
    }

    /**
     * Unscrambles and sanitizes a string as a text field.
     *
     * @param string $value The value to process.
     * @return string The sanitized and unslashed text field.
     */
    public static function sanitize_unslash_text_field($value)
    {
        return WPSanitize::text_field(self::unslash($value));
    }

    /**
     * Unscrambles and sanitizes a string as a URL.
     *
     * @param string $value The value to process.
     * @return string The sanitized and unslashed URL.
     */
    public static function sanitize_unslash_url($value)
    {
        return WPSanitize::url(self::unslash($value));
    }

    /**
     * Generate a hash value for a string using a specific hashing scheme.
     *
     * @param string $string The string to hash.
     * @param string $scheme The hashing scheme to use. Default is 'auth'.
     * @return string The hashed string.
     */
    public static function hash($string, $scheme = 'auth')
    {
        return wp_hash($string, $scheme);
    }

    /**
     * Generate a random password.
     *
     * @param int  $length            The length of the password. Default is 12.
     * @param bool $special_chars     Whether to include special characters in the password. Default is true.
     * @param bool $extra_special_chars Whether to include extra special characters in the password. Default is false.
     * @return string The generated password.
     */
    public static function password($length = 12, $special_chars = true, $extra_special_chars = false)
    {
        return wp_generate_password($length, $special_chars, $extra_special_chars);
    }

    /**
     * Generate a random number within a specified range.
     *
     * @param int $min The minimum value of the range. Default is 1.
     * @param int $max The maximum value of the range. Default is 1000.
     * @return int The generated random number.
     */
    public static function number($min = 1, $max = 1000)
    {
        return wp_rand($min, $max);
    }
}
