<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

class FrontendWpHtml
{
    /**
     * Initializes the hooks for frontend actions.
     */
    public static function init()
    {
        add_action("wp_head", [self::class, "head"]);
        add_action("wp_footer", [self::class, "footer"]);
    }

    /**
     * Outputs code in the <head> section for the frontend area.
     */
    public static function head()
    {
        echo "<!-- " . UNI_PLUGIN_SLUG . " Frontend WP Head Hook -->";
    }

    /**
     * Outputs code in the footer section for the frontend area.
     */
    public static function footer()
    {
        echo "<!-- " . UNI_PLUGIN_SLUG . " Frontend WP Footer Hook -->";
    }
}
