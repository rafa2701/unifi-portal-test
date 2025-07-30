<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

if (!is_user_logged_in() || !current_user_can('manage_options')) {

    wp_redirect(Plugin::site_url());
    exit;
}

echo FrontendTemplateBuilder::html('<div id="unifi-portal-render">Loading UniFi Portal...</div>');
