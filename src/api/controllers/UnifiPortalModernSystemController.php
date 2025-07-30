<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Modern UniFi OS System API Controller
 *
 * Handles API endpoints specific to modern UniFi OS systems.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
class UnifiPortalModernSystemController
{
    use UnifiPortalApiHelpers;

    /**
     * Get modern OS dashboard data
     *
     * Endpoint: GET /modern/dashboard
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return array{status: string, data?: array, error?: string|array, rate_limited?: bool, validation_error?: bool}
     */
    public static function getDashboard(\WP_REST_Request $request): array
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
                "data" => $unifi_controller->getModernDashboardData()
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
     * Get modern OS client data
     *
     * Endpoint: GET /modern/clients
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return array{status: string, data?: array, error?: string|array, rate_limited?: bool, validation_error?: bool}
     */
    public static function getClients(\WP_REST_Request $request): array
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
                "data" => $unifi_controller->getModernClientsData()
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
}
