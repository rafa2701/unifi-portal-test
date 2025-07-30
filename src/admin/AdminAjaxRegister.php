<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

class AdminAjaxRegister
{
    /**
     * List of AJAX actions and their corresponding handler files.
     *
     * @var array
     */
    private static $_ajaxActions = [];

    /**
     * Initialize the AJAX actions by registering them.
     *
     * This method hooks the AJAX actions into WordPress to handle authenticated requests.
     *
     * @return void
     */
    public static function init()
    {
        foreach (self::$_ajaxActions as $action => $file) {
            add_action("wp_ajax_{$action}", [self::class, "handleRequest"]);
        }
    }

    /**
     * Handle the AJAX request and map it to the respective file.
     *
     * This method sanitizes the incoming action, verifies if the action is valid,
     * and then delegates the request to the appropriate handler file.
     *
     * @return void
     */
    public static function handleRequest()
    {
        $action = WPUtils::sanitize_unslash_text_field($_POST["action"] ?? "");

        if (!isset(self::$_ajaxActions[$action])) {
            wp_send_json_error(["message" => "Invalid action"]);
        }

        $file = self::$_ajaxActions[$action];

        Ajax::handleRequest($file);
    }
}
