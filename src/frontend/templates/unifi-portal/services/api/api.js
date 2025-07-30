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
};
