<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * UniFi Connection Pool
 *
 * Manages UniFi controller connections and their lifecycle with built-in rate limiting
 * and connection state management. Provides connection pooling to minimize
 * redundant connections and ensure proper resource cleanup.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
class UnifiConnectionPool
{
    /** @var array<string, array> Stored active connections */
    private static array $connections = [];

    /** @var int Connection timeout in seconds */
    private const CONNECTION_TIMEOUT = 300;

    /** @var int Delay between connection operations */
    private const CONNECTION_DELAY = 5;

    /**
     * Get or create a controller connection
     *
     * Retrieves an existing valid connection from the pool or creates a new one
     * if needed. Handles connection lifecycle and state management.
     *
     * @param string $key Encrypted controller key
     * @param string $site Site name
     * @return UnifiController
     */
    public static function getConnection(string $key, string $site = 'default'): UnifiController
    {
        $controller_id = Utils::xorDecrypt(WPSanitize::text_field($key));
        $base_connection_id = self::generateBaseConnectionId($key);

        if (self::hasValidBaseConnection($base_connection_id)) {
            $controller = self::$connections[$base_connection_id]['controller'];

            if ($controller->getCurrentSite() !== $site) {
                sleep(self::CONNECTION_DELAY);

                $controller->switchToSite($site);
                if ($controller->isError()) {
                    self::resetConnection($key);
                    return self::createConnection($key, $site);
                }
            }

            return $controller;
        }

        return self::createConnection($key, $site);
    }

    /**
     * Reset a specific controller connection
     *
     * Removes the connection from the pool and ensures proper resource cleanup
     *
     * @param string $key Encrypted controller key
     * @return void
     */
    public static function resetConnection(string $key): void
    {
        $base_connection_id = self::generateBaseConnectionId($key);
        if (isset(self::$connections[$base_connection_id])) {
            $controller = self::$connections[$base_connection_id]['controller'];
            if ($controller && $controller->curl_handle) {
                curl_close($controller->curl_handle);
            }
            unset(self::$connections[$base_connection_id]);
        }
    }

    /**
     * Reset all active connections
     *
     * Cleans up all connections in the pool and their associated resources
     *
     * @return void
     */
    public static function resetAllConnections(): void
    {
        foreach (self::$connections as $connection) {
            if ($connection['controller'] && $connection['controller']->curl_handle) {
                curl_close($connection['controller']->curl_handle);
            }
        }
        self::$connections = [];
    }

    /**
     * Create and store new controller connection
     *
     * Initializes a new controller connection with proper delay handling
     * and error management.
     *
     * @param string $key Encrypted controller key
     * @param string $site Site name
     * @return UnifiController
     */
    private static function createConnection(string $key, string $site): UnifiController
    {
        $controller_id = Utils::xorDecrypt(WPSanitize::text_field($key));
        $base_connection_id = self::generateBaseConnectionId($key);

        self::resetConnection($key);
        sleep(self::CONNECTION_DELAY);

        $controller = new UnifiController();
        $controller
            ->setControllerId($controller_id)
            ->setSite($site)
            ->retrieveControllerDetails()
            ->tryApiConnection();

        if (!$controller->isError() && $site !== 'default') {
            sleep(self::CONNECTION_DELAY);
            $controller->switchToSite($site);
        }

        if (!$controller->isError()) {
            self::$connections[$base_connection_id] = [
                'controller' => $controller,
                'created_at' => time()
            ];
        }

        return $controller;
    }

    /**
     * Check if base connection exists and is still valid
     *
     * Verifies connection age and state to determine if it can be reused
     *
     * @param string $base_connection_id Base connection identifier
     * @return bool
     */
    private static function hasValidBaseConnection(string $base_connection_id): bool
    {
        if (!isset(self::$connections[$base_connection_id])) {
            return false;
        }

        $connection = self::$connections[$base_connection_id];
        $age = time() - $connection['created_at'];

        if ($age > self::CONNECTION_TIMEOUT) {
            self::resetConnection($base_connection_id);
            return false;
        }

        if (!$connection['controller']->isValidConnection()) {
            self::resetConnection($base_connection_id);
            return false;
        }

        return true;
    }

    /**
     * Generate unique base connection identifier
     *
     * @param string $key Controller key
     * @return string
     */
    private static function generateBaseConnectionId(string $key): string
    {
        return md5($key);
    }
}
