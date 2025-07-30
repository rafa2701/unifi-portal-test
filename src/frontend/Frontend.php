<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class Frontend
{
    /**
     * Registers frontend hooks.
     */
    public static function init()
    {

        // Initialize frontend-related components
        FrontendWpHtml::init();
        FrontendAssetsRegister::init();
        FrontendAjaxRegister::init();
        FrontendTemplatesRegister::init();
    }
}
