/**
 * Main configuration object for the UniFi Portal Admin
 */
export const UNIFI = {
  API: {
    ADMIN: {
      URL: UnifiPortalData.admin.url,
    },
    AJAX: {
      URL: UnifiPortalData.ajax.url,
      NONCE: UnifiPortalData.ajax.nonce,
    },
  },
};
