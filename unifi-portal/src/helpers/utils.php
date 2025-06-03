<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Laravel-style tap function implementation
 *
 * Passes the value to the given callback and returns the value
 *
 * @template T
 * @param T $value The value to pass to the callback
 * @param callable(T):mixed $callback The callback to execute
 * @return T The original value
 */
function tap(mixed $value, ?callable $callback = null): mixed
{
    if ($callback === null) {
        return $value;
    }

    $callback($value);
    return $value;
}
