<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Legacy UniFi System API Controller
 *
 * Handles API endpoints specific to legacy UniFi systems.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
class UnifiPortalLegacySystemController
{
    use UnifiPortalApiHelpers;

    /**
     * Get legacy system dashboard data
     *
     * Endpoint: GET /legacy/dashboard
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
                "data" => $unifi_controller->getLegacyDashboardData()
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
     * Get legacy system client data
     *
     * Endpoint: GET /legacy/clients
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
                "data" => $unifi_controller->getLegacyClientsData()
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
