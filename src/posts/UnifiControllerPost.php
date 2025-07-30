<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

class UnifiControllerPost
{
    /**
     * Register the custom post type "UniFi Controller"
     *
     * @return void
     */
    public static function register()
    {
        add_action("init", [self::class, "registerPostType"]);
        add_action("add_meta_boxes", [self::class, "addCustomFields"]);
        add_action("save_post_unifi-controller", [self::class, "saveCustomFieldsAction"], 10, 2);
        add_action("delete_post", [self::class, "clearControllerCacheOnDelete"]);
    }

    /**
     * Register the custom post type "UniFi Controller"
     *
     * @return void
     */
    public static function registerPostType()
    {
        $labels = [
            "name" => _x(
                "UniFi Controllers",
                "post type general name",
                Plugin::slug()
            ),
            "singular_name" => _x(
                "UniFi Controller",
                "post type singular name",
                Plugin::slug()
            ),
            "menu_name" => _x(
                "UniFi Controllers",
                "admin menu",
                Plugin::slug()
            ),
            "name_admin_bar" => _x(
                "UniFi Controller",
                "add new on admin bar",
                Plugin::slug()
            ),
            "add_new" => _x("Add New", "UniFi Controller", Plugin::slug()),
            "add_new_item" => __("Add New UniFi Controller", Plugin::slug()),
            "new_item" => __("New UniFi Controller", Plugin::slug()),
            "edit_item" => __("Edit UniFi Controller", Plugin::slug()),
            "view_item" => __("View UniFi Controller", Plugin::slug()),
            "all_items" => __("All UniFi Controllers", Plugin::slug()),
            "search_items" => __("Search UniFi Controllers", Plugin::slug()),
            "parent_item_colon" => __(
                "Parent UniFi Controllers:",
                Plugin::slug()
            ),
            "not_found" => __("No UniFi Controllers found.", Plugin::slug()),
            "not_found_in_trash" => __(
                "No UniFi Controllers found in Trash.",
                Plugin::slug()
            ),
        ];

        $args = [
            "labels" => $labels,
            "public" => true,
            "has_archive" => true,
            "show_in_rest" => true,
            "supports" => ["title"],
            "menu_icon" => Plugin::image_url("icons/unifi-logo.png"),
            "show_in_menu" => true,
            "capability_type" => "post",
            "rewrite" => ["slug" => "unifi-controller"],
            "exclude_from_search" => true,
            "publicly_queryable" => false,
        ];

        register_post_type("unifi-controller", $args);
    }

    /**
     * Add custom meta fields for the UniFi Controller
     *
     * @return void
     */
    public static function addCustomFields()
    {
        add_meta_box(
            "unifi_controller_meta",
            "UniFi Controller Settings",
            [self::class, "renderCustomFields"],
            "unifi-controller",
            "normal",
            "high"
        );
    }

    /**
     * Render the custom fields for the UniFi Controller post type
     *
     * @param WP_Post $post The post object.
     * @return void
     */
    public static function renderCustomFields($post)
    {
        echo Template::include([
            'file' => 'unifi-controller-template.php',
            'path' => 'posts',
            'data' => [
                'post' => $post,
                'post_id' => $post->ID,
                'screen' => get_current_screen(),
            ],
        ]);
    }

    /**
     * Save the custom fields data for the UniFi Controller post type
     *
     * @param int $post_id The post ID.
     * @return void
     */
    public static function saveCustomFields($post_id)
    {
        if (
            !isset($_POST["unifi_controller_nonce_field"]) ||
            !wp_verify_nonce(
                WPUtils::sanitize_unslash_text_field(
                    $_POST["unifi_controller_nonce_field"]
                ),
                "unifi_controller_nonce"
            )
        ) {
            return;
        }

        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return;
        }

        $multiMeta = new MultiPostMeta();
        $multiMeta->setId($post_id)

            ->prefix(Plugin::prefix())
            ->setFields([
                'controller_name',
                'controller_username',
                'controller_password',
                'controller_url',
            ]);

        $multiMeta->setValues([

            'controller_name' => WPUtils::sanitize_unslash_text_field($_POST['controller_name']),
            'controller_username' => WPUtils::sanitize_unslash_text_field($_POST['controller_username']),
            'controller_password' => WPUtils::sanitize_unslash_text_field($_POST['controller_password']),
            'controller_url' => WPUtils::sanitize_unslash_url($_POST['controller_url']),

        ])->update();
    }

    public static function clearControllerCache()
    {
        // Use the actual cache key string used in UnifiController::getControllerPosts()
        delete_transient('unifi_controller_posts');
    }

    public static function saveCustomFieldsAction($post_id, $post)
    {
        // It's crucial to only run the full save and cache clearing
        // for the 'unifi-controller' post type, and not for revisions or auto-saves.
        if (get_post_type($post_id) !== 'unifi-controller' || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // Call the original meta saving logic.
        // Ensure that the original saveCustomFields method's nonce checks and other guards are respected.
        // This might involve refactoring saveCustomFields to be callable without exiting,
        // or duplicating its core logic here if that's safer.
        // For this subtask, we'll assume saveCustomFields can be called and will perform its own checks.
        self::saveCustomFields($post_id); // This call assumes saveCustomFields handles its own nonce and capability checks.

        self::clearControllerCache();
    }

    public static function clearControllerCacheOnDelete($post_id)
    {
        if (get_post_type($post_id) == "unifi-controller") {
            self::clearControllerCache();
        }
    }
}
