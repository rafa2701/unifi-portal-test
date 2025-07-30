import { createApiInstance } from "./api";
import { UNIFI } from "@admin/constants";

const unifiInstance = createApiInstance({
  baseURL: UNIFI.API.REST.URL,
  headers: {
    "X-WP-Nonce": UNIFI.API.REST.NONCE,
  },
});

/**
 * UniFi API service for interacting with UniFi controllers and devices
 */
export const unifiApi = {
  controllers: {
    /**
     * Get all available UniFi controllers
     * @returns {Promise<Array>} List of controllers
     */
    getAll() {
      return unifiInstance.get(UNIFI.API.REST.ENDPOINTS.CONTROLLERS);
    },

    /**
     * Test connection to a specific controller
     * @param {string} key - Controller API key
     * @returns {Promise<boolean>} Connection test result
     */
    test(key) {
      return unifiInstance.get(UNIFI.API.REST.ENDPOINTS.CONTROLLER_TEST, {
        params: { key },
      });
    },

    /**
     * Get all sites for a specific controller
     * @param {string} key - Controller API key
     * @returns {Promise<Array>} List of sites
     */
    getSites(key) {
      return unifiInstance.get(UNIFI.API.REST.ENDPOINTS.SITES, {
        params: { key },
      });
    },
  },

  sites: {
    /**
     * Get site details
     * @param {string} controllerKey - Controller API key
     * @param {string} siteId - Site identifier
     * @returns {Promise<Object>} Site details
     */
    getDetails(controllerKey, siteId) {
      return unifiInstance.get(UNIFI.API.REST.ENDPOINTS.SITE_DETAILS, {
        params: { key: controllerKey, site: siteId },
      });
    },
  },

  reports: {
    /**
     * Generate a new report
     * @param {string} controllerKey - Controller API key
     * @param {string} siteId - Site identifier
     * @param {Object} config - Report configuration
     * @returns {Promise<Object>} Generated report
     */
    generate(controllerKey, siteId, config) {
      return unifiInstance.post(UNIFI.API.REST.ENDPOINTS.REPORT_GENERATE, {
        key: controllerKey,
        site: siteId,
        ...config,
      });
    },

    /**
     * Download a specific report
     * @param {string} reportId - Report identifier
     * @returns {Promise<Blob>} Report file blob
     */
    download(reportId) {
      return unifiInstance.get(
        `${UNIFI.API.REST.ENDPOINTS.REPORT_DOWNLOAD}/${reportId}`,
        {
          responseType: "blob",
        }
      );
    },
  },
};
