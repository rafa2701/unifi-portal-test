<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Include debugger class.
 */
require_once UNI_PLUGIN_SRC_PATH . 'debug/class-debugger.php';

/**
 * Checks if debugging is enabled.
 *
 * @return bool True if debugging is enabled, false otherwise.
 */
function is_debugging()
{
    return Debugger::is_debugging();
}

/**
 * Prints debugging information using print_r.
 *
 * @param mixed $echo The data to print.
 *
 * @return void
 */
function pr($echo = '')
{
    Debugger::debug($echo);
}

/**
 * Prints debugging information and then terminates the script.
 *
 * @param mixed  $echo  The data to print.
 * @param string $title The title for the debug output.
 *
 * @return void
 */
function pdie($echo = '', $title = 'Debug')
{
    Debugger::debug($echo, $title);
    die();
}

/**
 * Displays debugging information for application constants.
 *
 * @return void
 */
function show_constants()
{
    Debugger::constants();
}

/**
 * Logs any errors that occurred during script execution.
 *
 * @return void
 */
function log_errors()
{
    Debugger::log_errors();
}

/**
 * Displays an error message with details.
 *
 * @param string $from    The source of the error.
 * @param string $file    The file where the error occurred.
 * @param string $line    The line number where the error occurred.
 * @param string $message The error message.
 *
 * @return void
 */
function show_error($from = 'Default', $file = '', $line = '', $message = '')
{
    Debugger::show_error($from, $file, $line, $message);
}
