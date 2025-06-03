<?php

namespace Sfx\UnifiPortal;

if (!defined("ABSPATH")) {
    exit;
}

trait UnifiPortalApiHelpers
{
    /**
     * Initialize UniFi controller connection
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return UnifiController Initialized controller instance
     * @throws \InvalidArgumentException If controller key is missing
     */
    protected static function initializeController(\WP_REST_Request $request): UnifiController
    {
        $key = $request->get_param("key");
        $site = $request->get_param("site") ?? 'default';

        if (empty($key)) {
            throw new \InvalidArgumentException("Controller key is required");
        }

        return UnifiConnectionPool::getConnection($key, $site);
    }

    /**
     * Reset UniFi controller connection
     *
     * @param \WP_REST_Request $request WordPress REST request object
     * @return bool Reset success status
     */
    protected static function resetController(\WP_REST_Request $request): bool
    {
        $key = $request->get_param("key");

        if (empty($key)) {
            return false;
        }

        UnifiConnectionPool::resetConnection($key);
        return true;
    }
}
