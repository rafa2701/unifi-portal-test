<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Boots the plugin, sets up error handling, and runs the main plugin functionality.
 *
 * This function serves as the entry point for the plugin. It registers a shutdown function
 * for error logging, includes the main plugin file, and initiates the plugin's core functionality.
 * It also provides error handling for both Errors and Exceptions.
 *
 * @since 1.0.0
 *
 * @throws \Error|\Exception If an error or exception occurs during plugin initialization.
 *
 * @return void
 */
function boot_plugin(): void
{
    try {

        // Register a shutdown function to log errors
        register_shutdown_function(UNI_PLUGIN_NAMESPACE . 'log_errors');

        // Include the main plugin file
        include_once UNI_PLUGIN_SRC_PATH . 'plugin/plugin.php';

        // Run the plugin
        run_plugin();
    } catch (\Error $e) {

        // Handle errors
        show_error('Error in ' . __FILE__, $e->getFile(), $e->getLine(), $e->getMessage());
    } catch (\Exception $e) {

        // Handle exceptions
        show_error('Exception in ' . __FILE__, $e->getFile(), $e->getLine(), $e->getMessage());
    }
}
