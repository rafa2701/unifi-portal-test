<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class Admin
{
    /**
     * Registers admin hooks.
     */
    public static function init()
    {

        // Initialize admin-related components
        AdminWpHtml::init();
        AdminAssetsRegister::init();
        AdminAjaxRegister::init();
        AdminMenuRegister::init();
    }
}
