<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class AdminMenuRegister
 *
 * Registers the admin menu for the Unifi Reports plugin in the WordPress admin panel.
 * This class serves as an entry point for initializing the admin menu setup.
 */
class AdminMenuRegister
{
    /**
     * Initialize the admin menu for the plugin.
     *
     * @return void
     */
    public static function init()
    {
        AdminMenuCleanup::init();
        UnifiPortalMenu::init();
    }
}
