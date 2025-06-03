<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class ShortcodesRegister
{
    /**
     * Registers the shortcode and its handler.
     */
    public static function init()
    {
        // add_shortcode('unifi_browser', [self::class, 'render_unifi_browser_template']);
    }

    /**
     * Renders the 'unifi_browser' shortcode template.
     *
     * @param array $atts Shortcode attributes passed by the user.
     * @return string The rendered content of the template.
     */
    public static function render_unifi_browser_template($atts)
    {
        // return Template::include([
        //     'file' => 'unifi-browser/shortcode-unifi-browser.php',
        //     'path' => 'shortcodes',
        //     'data' => $atts,
        // ]);
    }
}
