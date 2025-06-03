import axios from "axios";

/**
 * Creates and configures an axios instance with common settings and interceptors
 * @param {Object} config - Additional axios configuration options
 * @returns {AxiosInstance} Configured axios instance
 */
export const createApiInstance = (config = {}) => {
  const instance = axios.create({
    headers: {
      "Content-Type": "application/json",
      ...config.headers,
    },
    ...config,
  });

  instance.interceptors.response.use(
    (response) => {
      if (!response.data) {
        throw new Error("No data received from server");
      }
      return response.data;
    },
    (error) => {
      const errorMessage =
        error.response?.data?.message || error.message || "An error occurred";
      console.error("API Error:", errorMessage);
      return Promise.reject(new Error(errorMessage));
    }
  );

  return instance;
};
