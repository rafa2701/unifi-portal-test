<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Class Plugin
 *
 * Handles the initialization and execution of the plugin.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
class Plugin extends Base_Plugin
{
    /**
     * Initializes the plugin.
     *
     * Defines constants, loads dependencies, registers hooks, and initializes components.
     *
     * @return void
     */
    public static function run(): void
    {
        self::define_constants();
        self::load_vendor();
        self::load_helpers();
        self::register_hooks();
        self::define_routes();

        add_action('plugins_loaded', [self::class, 'loaded'], 8);
    }

    /**
     * Executes the core functionality of the plugin.
     *
     * Initializes various components and loads a test file if debugging is enabled.
     *
     * @return void
     */
    public static function loaded(): void
    {
        Routes::init();
        PostRegister::init();

        Admin::init();
        Frontend::init();

        ShortcodesRegister::init();

        if (is_debugging()) {
            self::load_test_file();
        }
    }

    /**
     * Defines plugin constants.
     *
     * Loads and defines plugin-related constants from a separate class.
     *
     * @return void
     */
    private static function define_constants(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'constants/class-constants.php';
        Constants::define();
    }

    /**
     * Loads vendor dependencies using Composer's autoloader.
     *
     * @return void
     */
    private static function load_vendor(): void
    {
        require_once UNI_PLUGIN_PATH . 'vendor/autoload.php';
    }

    /**
     * Loads helper functions for the plugin.
     *
     * @return void
     */
    private static function load_helpers(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'helpers/init.php';
    }

    /**
     * Registers WordPress hooks for activation, deactivation, and uninstallation.
     *
     * @return void
     */
    private static function register_hooks(): void
    {
        register_activation_hook(UNI_PLUGIN_FILE, [self::class, 'activation']);
        register_deactivation_hook(UNI_PLUGIN_FILE, [self::class, 'deactivation']);
        register_uninstall_hook(UNI_PLUGIN_FILE, [self::class, 'uninstall']);
    }

    /**
     * Handles plugin activation logic.
     *
     * @return void
     */
    public static function activation(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'hooks/activation/class-activate.php';
        Activate::plugin();
    }

    /**
     * Handles plugin deactivation logic.
     *
     * @return void
     */
    public static function deactivation(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'hooks/deactivation/class-deactivate.php';
        Deactivate::plugin();
    }

    /**
     * Handles plugin uninstallation logic.
     *
     * @return void
     */
    public static function uninstall(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'hooks/uninstall/class-uninstall.php';
        Uninstall::plugin();
    }

    /**
     * Defines plugin routes.
     *
     * @return void
     */
    private static function define_routes(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'api/routes.php';
    }

    /**
     * Loads test functions for debugging or development purposes.
     *
     * @return void
     */
    private static function load_test_file(): void
    {
        require_once UNI_PLUGIN_SRC_PATH . 'debug/test.php';
    }
}
