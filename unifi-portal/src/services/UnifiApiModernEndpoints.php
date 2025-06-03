<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * UniFi Modern OS API Endpoints
 *
 * Provides endpoint methods for interacting with modern UniFi OS systems.
 * Used for UniFi controller versions running UniFi OS.
 *
 * @package Sfx\UnifiPortal
 * @since 1.0.0
 */
trait UnifiApiModernEndpoints
{
    /**
     * Retrieve modern OS dashboard data
     *
     * Endpoint: /proxy/network/v2/api/site/{site}/aggregated-dashboard
     *
     * @return array Dashboard information and statistics
     */
    public function getDashboardData(): array
    {
        $this->setApiUrl("/proxy/network/v2/api/site/{$this->getCurrentSite()}/aggregated-dashboard");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve modern OS client data
     *
     * Endpoint: /proxy/network/v2/api/site/{site}/clients/active
     *
     * @return array Connected client information
     */
    public function getClientsData(): array
    {
        $this->setApiUrl("/proxy/network/v2/api/site/{$this->getCurrentSite()}/clients/active");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve modern OS device data
     *
     * Endpoint: /proxy/network/v2/api/site/{site}/device
     *
     * @return array Network device information
     */
    public function getDevicesData(): array
    {
        $this->setApiUrl("/proxy/network/v2/api/site/{$this->getCurrentSite()}/device");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve modern OS settings
     *
     * Endpoint: /proxy/network/api/s/{site}/get/setting
     *
     * @return array System configuration settings
     */
    public function getSettingsData(): array
    {
        $this->setApiUrl("/proxy/network/api/s/{$this->getCurrentSite()}/get/setting");
        $this->run();
        return $this->getResponse();
    }

    /**
     * Retrieve modern OS health data
     *
     * Endpoint: /proxy/network/v2/api/site/{site}/health
     *
     * @return array System health statistics
     */
    public function getHealthData(): array
    {
        $this->setApiUrl("/proxy/network/v2/api/site/{$this->getCurrentSite()}/health");
        $this->run();
        return $this->getResponse();
    }
}
