<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

class Constants
{
    /**
     * Defines necessary constants for the plugin.
     *
     * @since 1.0.0
     */
    public static function define(): void
    {
        defined('UNI_AJAX_PATH') || define('UNI_AJAX_PATH', UNI_PLUGIN_SRC_PATH . 'ajax/');

        defined('UNI_ADMIN_TEMPLATES_PATH') || define('UNI_ADMIN_TEMPLATES_PATH', UNI_PLUGIN_SRC_PATH . 'admin/templates/');

        defined('UNI_FRONTEND_TEMPLATES_PATH') || define('UNI_FRONTEND_TEMPLATES_PATH', UNI_PLUGIN_SRC_PATH . 'frontend/templates/');
    }
}
