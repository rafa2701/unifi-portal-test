<?php

namespace Sfx\UnifiPortal;

if (!defined("ABSPATH")) {
    exit;
}

/**
 * UniFi Portal Main Controller
 *
 * Handles primary UniFi portal API endpoints and controller management.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
class UnifiPortalController
{
    use UnifiPortalApiHelpers;

    /**
     * Fetch all available UniFi controllers
     *
     * Endpoint: GET /fetch-controllers
     * Returns encrypted controller IDs and names for security
     *
     * @param \WP_REST_Request $request The request object
     * @return array{
     *     status: string,
     *     data?: array{controllers: array},
     *     error?: string|array,
     *     rate_limited?: bool,
     *     validation_error?: bool
     * }
     */
    public static function fetchControllers(\WP_REST_Request $request): array
    {
        try {
            $controllers = UnifiController::getControllerPosts();
            $controller_data = [];

            foreach ($controllers as $controller) {
                $controller_id = $controller->ID;
                $controller_name = PostMeta::read(
                    $controller_id,
                    Plugin::prefix("controller_name"),
                    true
                );

                $controller_data[] = [
                    "name" => esc_html($controller_name ?: $controller->post_title),
                    "key" => esc_html($controller->post_name),
                ];
            }

            return [
                "status" => "success",
                "data" => ["controllers" => $controller_data],
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Connect to UniFi controller
     *
     * Endpoint: GET /connect
     * Called after controller selection to establish initial connection
     *
     * @param \WP_REST_Request $request The request object containing:
     *                                  - key: Encrypted controller ID
     * @return array{
     *     status: string,
     *     connected?: bool,
     *     error?: string|array,
     *     rate_limited?: bool,
     *     validation_error?: bool
     * }
     */
    public static function connect(\WP_REST_Request $request): array
    {
        try {
            $unifi_controller = self::initializeController($request);

            if ($unifi_controller->isError()) {
                return [
                    "status" => "failed",
                    "error" => $unifi_controller->getErrors()
                ];
            }

            return [
                "status" => "success",
                "connected" => true
            ];
        } catch (\RuntimeException $e) {
            return [
                "status" => "failed",
                "error" => "Rate limit exceeded: " . $e->getMessage(),
                "rate_limited" => true
            ];
        } catch (\InvalidArgumentException $e) {
            return [
                "status" => "failed",
                "error" => "Invalid input: " . $e->getMessage(),
                "validation_error" => true
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Detect UniFi system type
     *
     * Endpoint: GET /detect-unifi
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return array{
     *     status: string,
     *     system_type?: string,
     *     error?: string|array,
     *     rate_limited?: bool,
     *     validation_error?: bool
     * }
     */
    public static function detectUnifi(\WP_REST_Request $request): array
    {
        try {
            $unifi_controller = self::initializeController($request);

            if ($unifi_controller->isError()) {
                return [
                    "status" => "failed",
                    "error" => $unifi_controller->getErrors()
                ];
            }

            $is_unifi_os = $unifi_controller->detectUnifiSystem();

            return [
                "status" => "success",
                "system_type" => $is_unifi_os ? "modern" : "legacy"
            ];
        } catch (\RuntimeException $e) {
            return [
                "status" => "failed",
                "error" => "Rate limit exceeded: " . $e->getMessage(),
                "rate_limited" => true
            ];
        } catch (\InvalidArgumentException $e) {
            return [
                "status" => "failed",
                "error" => "Invalid input: " . $e->getMessage(),
                "validation_error" => true
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Fetch available sites from a UniFi controller
     *
     * Endpoint: GET /fetch-sites
     * Retrieves list of sites available on the specified controller
     *
     * @param \WP_REST_Request $request The request object containing:
     *                                  - key: Encrypted controller ID
     * @return array{
     *     status: string,
     *     sites?: array,
     *     error?: string|array,
     *     rate_limited?: bool,
     *     validation_error?: bool
     * }
     */
    public static function fetchSites(\WP_REST_Request $request): array
    {
        try {
            $unifi_controller = self::initializeController($request);

            if ($unifi_controller->isError()) {
                return [
                    "status" => "failed",
                    "error" => $unifi_controller->getErrors()
                ];
            }

            $sites_list = $unifi_controller->loadAvailableSites();

            if ($unifi_controller->isError()) {
                return [
                    "status" => "failed",
                    "error" => $unifi_controller->getErrors()
                ];
            }

            $sites = array_map(
                fn($site) => $site['name'],
                $sites_list
            );

            return [
                "status" => "success",
                "sites" => $sites
            ];
        } catch (\RuntimeException $e) {
            return [
                "status" => "failed",
                "error" => "Rate limit exceeded: " . $e->getMessage(),
                "rate_limited" => true
            ];
        } catch (\InvalidArgumentException $e) {
            return [
                "status" => "failed",
                "error" => "Invalid input: " . $e->getMessage(),
                "validation_error" => true
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Reset controller connection
     *
     * Endpoint: GET /reset-connection
     * Resets the connection pool for the specified controller
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return array{
     *     status: string,
     *     reset?: bool,
     *     error?: string|array,
     *     rate_limited?: bool,
     *     validation_error?: bool
     * }
     */
    public static function resetConnection(\WP_REST_Request $request): array
    {
        try {
            $reset = self::resetController($request);

            return [
                "status" => "success",
                "reset" => $reset
            ];
        } catch (\RuntimeException $e) {
            return [
                "status" => "failed",
                "error" => "Rate limit exceeded: " . $e->getMessage(),
                "rate_limited" => true
            ];
        } catch (\InvalidArgumentException $e) {
            return [
                "status" => "failed",
                "error" => "Invalid input: " . $e->getMessage(),
                "validation_error" => true
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Reset all controller connections
     *
     * Endpoint: GET /reset-all-connections
     * Resets all connections in the connection pool
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return array{
     *     status: string,
     *     reset?: bool,
     *     error?: string|array
     * }
     */
    public static function resetAllConnections(\WP_REST_Request $request): array
    {
        try {
            UnifiConnectionPool::resetAllConnections();

            return [
                "status" => "success",
                "reset" => true
            ];
        } catch (\Exception $e) {
            return [
                "status" => "failed",
                "error" => "Unexpected error: " . $e->getMessage()
            ];
        }
    }
}
