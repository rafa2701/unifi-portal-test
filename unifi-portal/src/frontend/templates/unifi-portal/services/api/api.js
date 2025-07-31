import axios from "axios";
import { UNIFI } from "@frontend/constants";

/**
 * Creates a basic API instance configured with UniFi Portal settings
 */
const createApiInstance = () => {
  return axios.create({
    baseURL: UNIFI.API.REST.URL,
    headers: {
      "X-WP-Nonce": UNIFI.API.REST.NONCE,
      "Content-Type": "application/json",
    },
    withCredentials: true,
  });
};

/**
 * UniFi Portal API service
 */
export const api = {
  /**
   * Fetches all available controllers
   *
   * @returns {Promise<Object>} Controllers data
   */
  fetchControllers: async () => {
    try {
      const instance = createApiInstance();
      const response = await instance.get("fetch-controllers");
      return response.data;
    } catch (error) {
      console.error("Failed to fetch controllers:", error);
      throw error;
    }
  },

  /**
   * Fetches all available sites for a given controller
   *
   * @param {string} controllerKey
   * @returns {Promise<Object>} Sites data
   */
  fetchSites: async (controllerKey) => {
    try {
      const instance = createApiInstance();
      const response = await instance.get(`fetch-sites?key=${controllerKey}`);
      return response.data;
    } catch (error) {
      console.error("Failed to fetch sites:", error);
      throw error;
    }
  },

  /**
   * Detects the system type for a given controller
   *
   * @param {string} controllerKey
   * @returns {Promise<Object>} System type data
   */
  detectSystem: async (controllerKey) => {
    try {
      const instance = createApiInstance();
      const response = await instance.get(`detect-unifi?key=${controllerKey}`);
      return response.data;
    } catch (error) {
      console.error("Failed to detect system type:", error);
      throw error;
    }
  },
};
