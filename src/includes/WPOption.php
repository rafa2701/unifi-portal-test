<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class WPOption
 *
 * Provides utility methods for managing WordPress options.
 */
class WPOption
{
    /**
     * Creates a new option in the database.
     *
     * @param string $key The key of the option.
     * @param mixed $value The value of the option.
     * @param bool $autoload Whether to autoload the option. Default is true.
     * @return bool True if the option was created, false otherwise.
     */
    public static function create(string $key, $value, bool $autoload = true): bool
    {
        if (empty($key) || is_null($value)) {
            return false;
        }

        return add_option($key, $value, '', $autoload);
    }

    /**
     * Reads the value of an option from the database.
     *
     * @param string $key The key of the option.
     * @param mixed $default The default value to return if the option does not exist.
     * @return mixed The value of the option, or the default value if the option does not exist.
     */
    public static function read(string $key, $default = null)
    {
        if (empty($key)) {
            return $default;
        }

        return get_option($key, $default);
    }

    /**
     * Updates an option in the database or creates it if it doesn't exist.
     *
     * @param string $key The key of the option to update.
     * @param mixed $value The new value of the option.
     * @param bool $autoload Whether to autoload the option if it needs to be created. Default is true.
     * @return bool True if the option was updated or created, false otherwise.
     */
    public static function update(string $key, $value, bool $autoload = true): bool
    {
        if (empty($key) || is_null($value)) {
            return false;
        }

        if (self::read($key, null) === null) {
            return self::create($key, $value, $autoload);
        }

        return update_option($key, $value);
    }

    /**
     * Deletes an option from the database.
     *
     * @param string $key The key of the option to delete.
     * @return bool True if the option was deleted, false otherwise.
     */
    public static function delete(string $key): bool
    {
        if (empty($key)) {
            return false;
        }

        return delete_option($key);
    }
}
