import { defineStore } from "pinia";

/**
 * Store for managing UniFi controller data
 */
export const useControllerStore = defineStore("controller", {
  state: () => ({
    controllers: [],
    selectedController: null,
    selectedSite: "",
    connectionState: "disconnected", // For compatibility with header
  }),

  getters: {
    isControllerSelected: (state) => !!state.selectedController,
    isSiteSelected: (state) => !!state.selectedSite,

    controllerOptions: (state) => {
      return state.controllers.map((controller) => ({
        label: controller.name,
        value: controller.key,
      }));
    },

    siteOptions: (state) => {
      if (!state.selectedController) return [];

      return state.selectedController.sites.map((site) => ({
        label: site,
        value: site,
      }));
    },

    hasSelection: (state) => {
      return state.selectedController && state.selectedSite;
    },
  },

  actions: {
    setControllers(controllers) {
      this.controllers = controllers;
    },

    setSelectedController(key) {
      this.selectedController =
        this.controllers.find((c) => c.key === key) || null;
      this.selectedSite = "";
    },

    setSelectedSite(site) {
      this.selectedSite = site;
    },

    clearController() {
      this.selectedController = null;
      this.selectedSite = "";
    },

    clearSelection() {
      this.selectedController = null;
      this.selectedSite = "";
    },

    // Additional methods for compatibility with header
    setConnectionState(state) {
      this.connectionState = state;
    },
  },
});
