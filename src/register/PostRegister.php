<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class PostRegister
 *
 * Registers custom post types for the plugin.
 *
 * This class handles the registration of custom post types used by the plugin,
 * allowing for the management of custom data in the WordPress admin interface.
 */
class PostRegister
{
    /**
     * Initializes the custom post types registration.
     *
     * This method should be called during WordPress initialization to register the
     * custom post types defined within the plugin.
     *
     * @return void
     */
    public static function init()
    {
        UnifiControllerPost::register();
    }
}
