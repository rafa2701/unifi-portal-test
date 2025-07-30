<?php

namespace Sfx\UnifiPortal;

if (!defined("UNI_PLUGIN_PATH")) {
    exit;
}

/**
 * Class Routes
 *
 * Handles the registration of REST API routes for the Unifi Reports plugin.
 */
class Routes
{
    /**
     * Define GET routes.
     */
    private const GET_ROUTES = [

        // INITIAL SETUP FLOW
        'fetch-controllers' => [
            'controller' => "UnifiPortalController",
            'method' => "fetchControllers",
        ],
        'connect' => [
            'controller' => "UnifiPortalController",
            'method' => "connect",
        ],
        'detect-unifi' => [
            'controller' => "UnifiPortalController",
            'method' => "detectUnifi"
        ],
        'fetch-sites' => [
            'controller' => "UnifiPortalController",
            'method' => "fetchSites",
        ],
        'reset-connection' => [
            'controller' => "UnifiPortalController",
            'method' => "resetConnection"
        ],
        'reset-all-connections' => [
            'controller' => "UnifiPortalController",
            'method' => "resetAllConnections"
        ],

        // LEGACY.
        'legacy/dashboard' => [
            'controller' => "UnifiPortalLegacySystemController",
            'method' => "getDashboard"
        ],
        'legacy/clients' => [
            'controller' => "UnifiPortalLegacySystemController",
            'method' => "getClients"
        ],

        // MODERN.
        'modern/dashboard' => [
            'controller' => "UnifiPortalModernSystemController",
            'method' => "getDashboard"
        ],
        'modern/clients' => [
            'controller' => "UnifiPortalModernSystemController",
            'method' => "getClients"
        ],

    ];

    /**
     * Define POST routes.
     */
    private const POST_ROUTES = [];

    /**
     * Initialize the routes by hooking into the `rest_api_init` action.
     *
     * @return void
     */
    public static function init()
    {
        add_action("rest_api_init", [self::class, "register_routes"]);
    }

    /**
     * Register the REST API routes for the plugin.
     *
     * @return void
     */
    public static function register_routes()
    {
        $namespace = UNI_PLUGIN_SLUG . "/v1/";
        $permissions = ["manage_options"];
        $router = new Router($namespace);

        self::register_get_routes($router, $permissions);
        self::register_post_routes($router, $permissions);
    }

    /**
     * Register GET routes.
     *
     * @param Router $router
     * @param array $permissions
     * @return void
     */
    private static function register_get_routes(Router $router, array $permissions)
    {
        foreach (self::GET_ROUTES as $endpoint => $details) {
            $router->get(
                $endpoint,
                [
                    UNI_PLUGIN_NAMESPACE . $details['controller'],
                    $details['method'],
                ],
                $permissions
            );
        }
    }

    /**
     * Register POST routes.
     *
     * @param Router $router
     * @param array $permissions
     * @return void
     */
    private static function register_post_routes(Router $router, array $permissions)
    {
        foreach (self::POST_ROUTES as $endpoint => $details) {
            $router->post(
                $endpoint,
                [
                    UNI_PLUGIN_NAMESPACE . $details['controller'],
                    $details['method'],
                ],
                $permissions
            );
        }
    }
}
