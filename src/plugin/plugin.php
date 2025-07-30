<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Initializes and runs the plugin.
 *
 * This function serves as the entry point for the plugin. It includes the necessary
 * class files for the base plugin and main plugin classes, then invokes the static
 * run method of the Plugin class to initialize and start the plugin's functionality.
 *
 * @since 1.0.0
 *
 * @throws \Exception If required files are not found or if the Plugin class is not defined.
 *
 * @return void
 */
function run_plugin()
{
    require_once UNI_PLUGIN_SRC_PATH . 'plugin/class-base-plugin.php';
    require_once UNI_PLUGIN_SRC_PATH . 'plugin/class-plugin.php';

    Plugin::run();
}
