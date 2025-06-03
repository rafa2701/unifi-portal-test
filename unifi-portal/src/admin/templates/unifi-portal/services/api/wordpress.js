import { createApiInstance } from "./api";
import { UNIFI } from "@admin/constants";

const wpInstance = createApiInstance({
  baseURL: UNIFI.SITE.URL,
  headers: {
    "X-WP-Nonce": UNIFI.API.AJAX.NONCE,
  },
});

export const wordpressApi = {
  users: {
    getCurrent() {
      return wpInstance.get("/wp-json/wp/v2/users/me");
    },
  },

  settings: {
    get() {
      return wpInstance.get("/wp-json/unifi-portal/v1/settings");
    },
    update(settings) {
      return wpInstance.post("/wp-json/unifi-portal/v1/settings", settings);
    },
  },

  ajax(action, data) {
    const formData = new FormData();
    formData.append("action", action);
    formData.append("nonce", UNIFI.API.AJAX.NONCE);
    if (data) formData.append("data", JSON.stringify(data));
    return wpInstance.post(UNIFI.API.AJAX.URL, formData);
  },
};
