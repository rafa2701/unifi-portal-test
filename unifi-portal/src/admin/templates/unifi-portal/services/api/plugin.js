import { createApiInstance } from "./api";
import { UNIFI } from "@admin/constants";

const pluginInstance = createApiInstance({
  baseURL: UNIFI.API.REST.URL,
  headers: {
    "X-WP-Nonce": UNIFI.API.REST.NONCE,
  },
});

export const pluginApi = {
  license: {
    validate(key) {
      return pluginInstance.post("/license/validate", { key });
    },
    deactivate() {
      return pluginInstance.post("/license/deactivate");
    },
  },

  settings: {
    get() {
      return pluginInstance.get("/settings");
    },
    update(settings) {
      return pluginInstance.post("/settings", settings);
    },
  },
};
