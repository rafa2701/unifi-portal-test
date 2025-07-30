import { ref, computed } from "vue";
import { useControllerStore } from "@admin/stores/controller";
import { unifiApi } from "@admin/services/api";

export function useSite() {
  const controllerStore = useControllerStore();
  const loading = ref(false);
  const error = ref(null);
  const siteDetails = ref(null);
  const devices = ref([]);

  const selectedSite = computed(() => controllerStore.selectedSite);
  const controllerKey = computed(() => controllerStore.controllerKey);

  const fetchSiteDetails = async () => {
    if (!controllerKey.value || !selectedSite.value) {
      return;
    }

    try {
      loading.value = true;
      error.value = null;
      const response = await unifiApi.sites.getDetails(
        controllerKey.value,
        selectedSite.value
      );
      siteDetails.value = response.data;
    } catch (err) {
      error.value = err.message || "Failed to fetch site details";
      throw new Error(error.value);
    } finally {
      loading.value = false;
    }
  };

  const fetchSiteDevices = async () => {
    if (!controllerKey.value || !selectedSite.value) {
      return;
    }

    try {
      loading.value = true;
      error.value = null;
      const response = await unifiApi.devices.getAll(
        controllerKey.value,
        selectedSite.value
      );
      devices.value = response.data;
    } catch (err) {
      error.value = err.message || "Failed to fetch site devices";
      throw new Error(error.value);
    } finally {
      loading.value = false;
    }
  };

  const refreshSiteData = async () => {
    try {
      await Promise.all([fetchSiteDetails(), fetchSiteDevices()]);
    } catch (err) {
      error.value = err.message || "Failed to refresh site data";
      throw new Error(error.value);
    }
  };

  const clearSiteData = () => {
    siteDetails.value = null;
    devices.value = [];
    error.value = null;
  };

  return {
    // State
    loading,
    error,
    siteDetails,
    devices,

    // Computed
    selectedSite,
    controllerKey,

    // Methods
    fetchSiteDetails,
    fetchSiteDevices,
    refreshSiteData,
    clearSiteData,
  };
}
