<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class FrontendTemplatesRegister
{

    public static function init()
    {
        add_filter('theme_page_templates', [__CLASS__, 'registerTemplate']);
        add_filter('template_include', [__CLASS__, 'loadTemplate']);
    }

    public static function registerTemplate($templates)
    {
        $templates['template-unifi-portal'] = 'Unifi Portal Template';
        return $templates;
    }

    public static function loadTemplate($template)
    {
        $post = get_post();
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);

        if ($page_template === 'template-unifi-portal') {

            $plugin_template = UNI_FRONTEND_TEMPLATES_PATH . 'unifi-portal/template-unifi-portal.php';

            if (is_readable($plugin_template)) {
                return $plugin_template;
            }
        }

        return $template;
    }
}
