<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * UniFi Controller API Client
 *
 * Handles high-level UniFi Controller operations including:
 * - WordPress integration for controller management
 * - Site management and switching
 * - Connection handling
 * - Configuration management
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
final class UnifiController extends UnifiControllerBase
{
    use UnifiApiLegacyEndpoints, UnifiApiModernEndpoints {
        UnifiApiModernEndpoints::getDashboardData insteadof UnifiApiLegacyEndpoints;
        UnifiApiModernEndpoints::getClientsData insteadof UnifiApiLegacyEndpoints;
        UnifiApiModernEndpoints::getDevicesData insteadof UnifiApiLegacyEndpoints;
        UnifiApiModernEndpoints::getSettingsData insteadof UnifiApiLegacyEndpoints;
        UnifiApiModernEndpoints::getHealthData insteadof UnifiApiLegacyEndpoints;

        UnifiApiLegacyEndpoints::getDashboardData as getLegacyDashboardData;
        UnifiApiLegacyEndpoints::getClientsData as getLegacyClientsData;
        UnifiApiLegacyEndpoints::getDevicesData as getLegacyDevicesData;
        UnifiApiLegacyEndpoints::getSettingsData as getLegacySettingsData;
        UnifiApiLegacyEndpoints::getHealthData as getLegacyHealthData;

        UnifiApiModernEndpoints::getDashboardData as getModernDashboardData;
        UnifiApiModernEndpoints::getClientsData as getModernClientsData;
        UnifiApiModernEndpoints::getDevicesData as getModernDevicesData;
        UnifiApiModernEndpoints::getSettingsData as getModernSettingsData;
        UnifiApiModernEndpoints::getHealthData as getModernHealthData;
    }

    /** @var int|null WordPress post ID for this controller */
    protected ?int $controller_id = null;

    /** @var array Required configuration fields */
    protected const REQUIRED_FIELDS = ['user', 'password', 'baseurl'];

    /** @var string Cache key for controller posts */
    protected const CACHE_KEY = 'unifi_controller_posts';

    /** @var int Delay between API calls in seconds */
    protected const API_CALL_DELAY = 2;

    /** @var array Cache for available sites */
    protected array $available_sites = [];

    /** @var array API endpoints for different UniFi versions */
    protected const API_ENDPOINTS = [
        'sites' => [
            'unifi_os' => ['/proxy/network/api/self/sites', '/api/sites'],
            'legacy' => ['/api/self/sites']
        ]
    ];

    /**
     * Get all UniFi controller posts from WordPress
     *
     * Uses transient caching for improved performance
     *
     * @return array Array of WP_Post objects
     */
    public static function getControllerPosts(): array
    {
        $cached_posts = get_transient(self::CACHE_KEY);

        if ($cached_posts !== false) {
            return $cached_posts;
        }

        $posts = get_posts([
            'post_type' => 'unifi-controller',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        set_transient(self::CACHE_KEY, $posts, HOUR_IN_SECONDS);

        return $posts;
    }

    /**
     * Set the WordPress post ID for this controller
     *
     * @param int $controller_id Post ID
     * @return self
     */
    public function setControllerId(int $controller_id): self
    {
        $this->controller_id = $controller_id;
        $this->debugLog("Set controller ID: $controller_id");
        return $this;
    }

    /**
     * Set site for the controller
     *
     * @param string $site_name Site name to set
     * @return self
     */
    public function setSite(string $site_name): self
    {
        $this->controller_details['site'] = !empty($site_name) ? $site_name : 'default';
        return $this;
    }

    /**
     * Get current site name
     *
     * @return string Current site name
     */
    public function getCurrentSite(): string
    {
        return $this->controller_details['site'] ?? 'default';
    }

    /**
     * Load controller details from WordPress post meta
     *
     * @return self
     */
    public function retrieveControllerDetails(): self
    {
        if (empty($this->controller_id)) {
            $this->addError('Controller ID must be set before fetching details', [
                'controller_id' => $this->controller_id
            ]);
            return $this;
        }

        $meta = (new MultiPostMeta)
            ->setId($this->controller_id)
            ->prefix(Plugin::prefix())
            ->setFields([
                'controller_username',
                'controller_password',
                'controller_url',
            ])
            ->read();

        $this->debugLog('Retrieved controller meta:', $meta);

        return $this->setControllerDetails([
            'user' => $meta['controller_username'],
            'password' => $meta['controller_password'],
            'baseurl' => $meta['controller_url'] ?: 'https://127.0.0.1:8443',
        ]);
    }

    /**
     * Set controller connection details manually
     *
     * @param array $details Connection configuration
     * @return self
     */
    public function setControllerDetails(array $details): self
    {
        $this->controller_details = $details;

        $missing = array_filter(
            self::REQUIRED_FIELDS,
            fn($field) => empty($this->controller_details[$field])
        );

        if (!empty($missing)) {
            $this->addError('Missing required fields: ' . implode(', ', $missing));
        }

        $this->debugLog('Controller details set:', $this->controller_details);
        return $this;
    }

    /**
     * Try to establish API connection (single attempt)
     *
     * @return self
     */
    public function tryApiConnection(): self
    {
        if (empty($this->controller_details)) {
            $this->addError('Controller details must be set before connecting');
            return $this;
        }

        $this->debugLog("Attempting API connection");

        if ($this->login()) {
            return $this;
        }

        $this->addError("Failed to connect to UniFi controller");
        return $this;
    }

    /**
     * Load and cache available sites for this controller
     * Implements delay between API calls
     *
     * @param bool $force Force refresh of sites cache
     * @return array Array of site information
     */
    public function loadAvailableSites(bool $force = false): array
    {
        if (!empty($this->available_sites) && !$force) {
            return $this->available_sites;
        }

        if (!$this->is_logged_in) {
            $this->tryApiConnection();
        }

        if ($this->isError()) {
            return [];
        }

        $endpoints = $this->is_unifi_os
            ? self::API_ENDPOINTS['sites']['unifi_os']
            : self::API_ENDPOINTS['sites']['legacy'];

        foreach ($endpoints as $endpoint) {
            $this->debugLog("Trying sites endpoint: $endpoint");

            sleep(self::API_CALL_DELAY);

            $this->setApiUrl($endpoint);

            if ($this->run() && !$this->isError()) {
                $response = $this->getResponse();
                $sites = $response['data'] ?? [];

                if (!empty($sites)) {
                    $this->available_sites = array_map(function ($site) {
                        return [
                            'name' => $site['name'] ?? $site['desc'] ?? 'Unknown',
                            'desc' => $site['desc'] ?? $site['name'] ?? 'Unknown',
                            'role' => $site['role'] ?? 'Unknown'
                        ];
                    }, $sites);

                    $this->debugLog('Available sites loaded:', $this->available_sites);
                    return $this->available_sites;
                }
            }
        }

        $this->addError('No sites found or could not fetch sites', [
            'is_unifi_os' => $this->is_unifi_os,
            'endpoints_tried' => $endpoints
        ]);

        return [];
    }

    /**
     * Check if controller has multiple sites
     *
     * @return bool
     */
    public function hasMultipleSites(): bool
    {
        return count($this->loadAvailableSites()) > 1;
    }

    /**
     * Get number of available sites
     *
     * @return int
     */
    public function getSiteCount(): int
    {
        return count($this->loadAvailableSites());
    }

    /**
     * Get list of site names
     *
     * @return array
     */
    public function getSiteNames(): array
    {
        return array_column($this->loadAvailableSites(), 'name');
    }

    /**
     * Switch to a different UniFi site
     *
     * @param string $site_name Site name to switch to
     * @return self
     */
    public function switchToSite(string $site_name): self
    {
        if (!$this->is_logged_in) {
            $this->tryApiConnection();
        }

        if ($this->isError()) {
            return $this;
        }

        if ($this->getSiteCount() <= 1 && $site_name === 'default') {
            $this->controller_details['site'] = 'default';
            $this->debugLog("Single site controller, using 'default'");
            return $this->tryApiConnection();
        }

        if (!$this->siteExists($site_name)) {
            $available = implode(', ', $this->getSiteNames());
            $this->addError("Site '$site_name' not found", [
                'available_sites' => $available,
                'site_count' => $this->getSiteCount()
            ]);
            return $this;
        }

        $this->controller_details['site'] = $site_name;
        $this->debugLog("Switched to site: $site_name");

        return $this->tryApiConnection();
    }

    /**
     * Check if a site exists in the controller
     *
     * @param string $site_name Site name to check
     * @return bool
     */
    protected function siteExists(string $site_name): bool
    {
        $sites = $this->loadAvailableSites();
        return !empty(array_filter(
            $sites,
            fn($site) => $site['name'] === $site_name
        ));
    }

    /**
     * Get current controller configuration
     *
     * @return array
     */
    public function getControllerDetails(): array
    {
        $details = $this->controller_details;
        unset($details['password']);
        return $details;
    }

    /**
     * Test connection to UniFi Controller and output debug information
     *
     * Attempts to establish connection and verify site availability.
     * Debug information is logged when debug mode is enabled.
     *
     * @param bool $enable_debug Enable debug logging for this test
     * @return bool Connection success status
     */
    public function testConnection(bool $enable_debug = false): bool
    {
        $this->debug($enable_debug);

        if ($enable_debug) {
            $this->debugLog('Starting UniFi connection test...');
            $safe_details = $this->getControllerDetails();
            $this->debugLog('Controller Details:', $safe_details);
        }

        if (empty($this->controller_details)) {
            $this->addError('Controller details must be set before testing connection');
            return false;
        }

        if (!$this->tryApiConnection()->isError()) {
            if ($enable_debug) {
                $this->debugLog('Connection successful');
            }

            try {
                $sites = $this->loadAvailableSites(true);

                if ($enable_debug) {
                    $this->debugLog('Sites available:', $sites);
                }

                return true;
            } catch (\Exception $e) {
                $this->addError('Connected but failed to load sites: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }
}
