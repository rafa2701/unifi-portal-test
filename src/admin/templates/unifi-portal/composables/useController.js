import { ref, computed } from "vue";
import { useControllerStore } from "@admin/stores/controller";
import { unifiApi } from "@admin/services/api";

export function useController() {
  const controllerStore = useControllerStore();
  const loading = ref(false);
  const error = ref(null);
  const controllers = ref([]);
  const sites = ref([]);
  const testingConnection = ref(false);

  const selectedController = computed(() => controllerStore.controllerKey);
  const selectedSite = computed(() => controllerStore.selectedSite);
  const isControllerSelected = computed(
    () => controllerStore.isControllerSelected
  );
  const isSiteSelected = computed(() => controllerStore.isSiteSelected);

  const fetchControllers = async () => {
    try {
      loading.value = true;
      error.value = null;
      const response = await unifiApi.controllers.getAll();
      controllers.value = response.data;
    } catch (err) {
      error.value = err.message || "Failed to fetch controllers";
      throw new Error(error.value);
    } finally {
      loading.value = false;
    }
  };

  const testControllerConnection = async (controllerKey) => {
    if (!controllerKey) {
      throw new Error("Controller key is required");
    }

    try {
      testingConnection.value = true;
      error.value = null;
      const response = await unifiApi.controllers.test(controllerKey);
      return response.success;
    } catch (err) {
      error.value = err.message || "Failed to test controller connection";
      throw new Error(error.value);
    } finally {
      testingConnection.value = false;
    }
  };

  const fetchSites = async (controllerKey) => {
    if (!controllerKey) {
      throw new Error("Controller key is required");
    }

    try {
      loading.value = true;
      error.value = null;
      const response = await unifiApi.sites.getAll(controllerKey);
      sites.value = response.data;
      return response.data;
    } catch (err) {
      error.value = err.message || "Failed to fetch sites";
      throw new Error(error.value);
    } finally {
      loading.value = false;
    }
  };

  const selectController = async (controller) => {
    try {
      error.value = null;

      if (!controller?.key) {
        controllerStore.clearController();
        return;
      }

      const isConnected = await testControllerConnection(controller.key);
      if (!isConnected) {
        throw new Error("Failed to connect to controller");
      }

      controllerStore.setController({
        key: controller.key,
        name: controller.name,
      });

      return await fetchSites(controller.key);
    } catch (err) {
      error.value = err.message || "Failed to select controller";
      controllerStore.clearController();
      throw new Error(error.value);
    }
  };

  const selectSite = (site) => {
    try {
      error.value = null;
      controllerStore.setSite(site);
    } catch (err) {
      error.value = err.message || "Failed to select site";
      throw new Error(error.value);
    }
  };

  const clearSelection = () => {
    controllerStore.clearController();
    controllers.value = [];
    sites.value = [];
    error.value = null;
  };

  return {
    // State
    loading,
    error,
    controllers,
    sites,
    testingConnection,

    // Computed
    selectedController,
    selectedSite,
    isControllerSelected,
    isSiteSelected,

    // Methods
    fetchControllers,
    testControllerConnection,
    fetchSites,
    selectController,
    selectSite,
    clearSelection,
  };
}
