/**
 * Main configuration object for the UniFi Portal application
 */
export const UNIFI = {
  API: {
    AJAX: {
      URL: UnifiPortalData.ajax.url,
      NONCE: UnifiPortalData.ajax.nonce,
    },

    REST: {
      URL: UnifiPortalData.rest.url,
      NONCE: UnifiPortalData.rest.nonce,
      ENDPOINTS: {
        CONTROLLER: {
          FETCH: "fetch-controllers",
        },
      },
    },
  },

  SYSTEM_TYPES: {
    LEGACY: "legacy",
    MODERN: "modern",
  },

  ROUTES: {
    PORTAL: "portal",
    REPORTS: "reports",
    REPORTS_MODERN: "reports-modern",
    REPORTS_LEGACY: "reports-legacy",
  },
};
