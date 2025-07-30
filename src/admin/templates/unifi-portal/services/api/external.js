import { createApiInstance } from "./api";

const externalInstance = createApiInstance({
  timeout: 10000,
});

export const externalApi = {
  license: {
    validate(key) {
      return externalInstance.post("https://api.example.com/validate", { key });
    },
  },

  updates: {
    check(version) {
      return externalInstance.get("https://api.example.com/version-check", {
        params: { current: version },
      });
    },
  },
};
