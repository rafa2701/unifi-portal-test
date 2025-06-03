<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * UniFi Legacy System API Endpoints
 *
 * Provides endpoint methods for interacting with legacy UniFi controller systems.
 * Used for UniFi controller versions prior to UniFi OS.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
trait UnifiApiLegacyEndpoints
{
    /**
     * Retrieve legacy system dashboard data
     *
     * Endpoint: /api/s/{site}/stat/dashboard
     *
     * @return array Dashboard information and statistics
     */
    public function getDashboardData(): array
    {
        $this->setApiUrl("/api/s/{$this->getCurrentSite()}/stat/dashboard");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve legacy system client data
     *
     * Endpoint: /api/s/{site}/stat/sta
     *
     * @return array Connected client information
     */
    public function getClientsData(): array
    {
        $this->setApiUrl("/api/s/{$this->getCurrentSite()}/stat/sta");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve legacy system device data
     *
     * Endpoint: /api/s/{site}/stat/device
     *
     * @return array Network device information
     */
    public function getDevicesData(): array
    {
        $this->setApiUrl("/api/s/{$this->getCurrentSite()}/stat/device");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve legacy system settings
     *
     * Endpoint: /api/s/{site}/get/setting
     *
     * @return array System configuration settings
     */
    public function getSettingsData(): array
    {
        $this->setApiUrl("/api/s/{$this->getCurrentSite()}/get/setting");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve legacy system health data
     *
     * Endpoint: /api/s/{site}/stat/health
     *
     * @return array System health statistics
     */
    public function getHealthData(): array
    {
        $this->setApiUrl("/api/s/{$this->getCurrentSite()}/stat/health");
        $this->run();
        return $this->getResponse();
    }
}
