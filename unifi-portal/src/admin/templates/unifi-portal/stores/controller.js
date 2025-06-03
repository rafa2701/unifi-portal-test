import { defineStore } from "pinia";

/**
 * Controller store for managing global controller state
 */
export const useControllerStore = defineStore("controller", {
  state: () => ({
    controllerKey: null,
    controllerName: null,
    selectedSite: null,
  }),

  actions: {
    setController(controller) {
      this.controllerKey = controller?.key || null;
      this.controllerName = controller?.name || null;
    },

    setSite(site) {
      this.selectedSite = site;
    },

    clearController() {
      this.controllerKey = null;
      this.controllerName = null;
      this.selectedSite = null;
    },
  },

  getters: {
    isControllerSelected: (state) => !!state.controllerKey,
    isSiteSelected: (state) => !!state.selectedSite,
  },
});
