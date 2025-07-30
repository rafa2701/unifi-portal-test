<?php

namespace Sfx\UnifiPortal;

use Closure;
use ArrayAccess;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class Arr
{
    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|array $keys
     * @return bool
     */
    public static function has($array, $keys)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array)$keys;

        if (!$array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return static::value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return static::value($default);
            }
        }

        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        $keys = (array)$keys;

        $results = [];

        foreach ($keys as $key) {
            static::set($results, $key, static::get($array, $key));
        }

        return $results;
    }

    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function except($array, $keys)
    {
        $keys = (array)$keys;

        static::forget($array, $keys);

        return $array;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array $array
     * @param array|string $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array)$keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            if (static::exists($array, $key)) {
                unset($array[$key]);
                continue;
            }

            $parts = explode('.', $key);

            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array $array
     * @param \Closure $callback
     * @param mixed $default
     * @return mixed
     */
    public static function first($array, $callback, $default = null)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) return $value;
        }

        return static::value($default);
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess|array $array
     * @param string|int $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array $array
     * @param string $prepend
     *
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Check if a key is true or truthy.
     *
     * @param array $array
     * @param string $key
     * @return bool
     */
    public static function isTrue($array, $key)
    {
        $value = self::get($array, $key);
        $isFalse = !$value || $value == 'false' || $value == '0';

        return !$isFalse;
    }

    /**
     * Convert an object to an array recursively.
     *
     * This method checks if the input is an object and converts it to an array.
     * If the input is an array, it recursively converts any nested objects to arrays.
     *
     * @param mixed $object The object or array to convert.
     * @return mixed The converted array.
     */
    public static function objectToArray($object)
    {
        if (is_object($object)) {
            $object = (array) $object;
        }

        if (is_array($object)) {
            foreach ($object as $key => $value) {
                $object[$key] = self::objectToArray($value);
            }
        }

        return $object;
    }

    /**
     * Convert an array to an object recursively.
     *
     * This method checks if the input is an array and converts it to an object.
     * If the input is an object, it recursively converts any nested arrays to objects.
     *
     * @param mixed $array The array or object to convert.
     * @return mixed The converted object.
     */
    public static function arrayToObject($array)
    {
        if (is_array($array)) {
            $array = (object) $array;
        }

        if (is_object($array)) {
            foreach ($array as $key => $value) {
                $array->$key = self::arrayToObject($value);
            }
        }

        return $array;
    }
}
