import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useControllerStore } from "@frontend/stores/controller";
import { useAlerts } from "@frontend/composables/core/useAlerts";
import { api } from "@frontend/services/api/api";
import { UNIFI } from "@frontend/constants";

/**
 * Logic for the UniFi Home component
 */
export default function useUnifiHome() {
  const router = useRouter();
  const controllerStore = useControllerStore();
  const { alert, showAlert, clearAlert } = useAlerts();

  // Controller selection
  const isLoading = ref(false);
  const controllerError = ref("");
  const selectedController = ref("");

  // Site selection
  const sitesLoading = ref(false);
  const siteError = ref("");
  const selectedSite = ref("");

  // Computed properties
  const controllerOptions = computed(() => {
    return controllerStore.controllerOptions;
  });

  const siteOptions = computed(() => {
    if (!selectedController.value) return [];

    const controller = controllerStore.controllers.find(
      (c) => c.key === selectedController.value
    );

    if (!controller) return [];

    return controller.sites.map((site) => ({
      label: site,
      value: site,
    }));
  });

  const canViewReports = computed(() => {
    return selectedController.value && selectedSite.value;
  });

  /**
   * Fetches available controllers from the API
   */
  const fetchControllers = async () => {
    try {
      isLoading.value = true;
      controllerError.value = "";
      showAlert("info", "Loading", "Loading controllers...");

      const response = await api.fetchControllers();

      if (response.status === "success") {
        controllerStore.setControllers(response.data.controllers);
        showAlert("success", "Success", "Controllers loaded successfully");
      } else {
        throw new Error("Failed to load controllers");
      }
    } catch (error) {
      console.error("Error loading controllers:", error);
      controllerError.value = "Failed to load controllers";
      showAlert("error", "Error", "Failed to load controllers");
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Fetches available sites for a given controller
   */
  const fetchSites = async (controllerKey) => {
    try {
      sitesLoading.value = true;
      siteError.value = "";
      showAlert("info", "Loading", "Loading sites...");

      const response = await api.fetchSites(controllerKey);

      if (response.status === "success") {
        const controller = controllerStore.controllers.find(
          (c) => c.key === controllerKey
        );
        if (controller) {
          controller.sites = response.data.sites;
        }
        showAlert("success", "Success", "Sites loaded successfully");
      } else {
        throw new Error("Failed to load sites");
      }
    } catch (error) {
      console.error("Error loading sites:", error);
      siteError.value = "Failed to load sites";
      showAlert("error", "Error", "Failed to load sites");
    } finally {
      sitesLoading.value = false;
    }
  };

  /**
   * Handles controller selection change
   */
  const handleControllerChange = () => {
    selectedSite.value = "";
    controllerStore.setSelectedController(selectedController.value);

    if (selectedController.value) {
      fetchSites(selectedController.value);
    } else {
      controllerStore.clearSelection();
    }
  };

  /**
   * Handles the View Reports button click
   */
  const handleViewButtonClick = async () => {
    if (!canViewReports.value) return;

    const controller = controllerStore.controllers.find(
      (c) => c.key === selectedController.value
    );

    if (!controller) return;

    controllerStore.setSelectedController(selectedController.value);
    controllerStore.setSelectedSite(selectedSite.value);
    controllerStore.setConnectionState("connected");

    // Detect system type and navigate to the correct report
    try {
      const response = await api.detectSystem(selectedController.value);
      if (response.status === "success") {
        const routeName =
          response.data.system_type === "modern"
            ? UNIFI.ROUTES.REPORTS_MODERN
            : UNIFI.ROUTES.REPORTS_LEGACY;
        router.push({ name: routeName });
      } else {
        throw new Error("Failed to detect system type");
      }
    } catch (error) {
      console.error("Error detecting system type:", error);
      showAlert("error", "Error", "Failed to detect system type");
    }
  };

  onMounted(() => {
    controllerStore.clearSelection();
    fetchControllers();
  });

  return {
    // State
    alert,
    isLoading,
    controllerError,
    selectedController,
    sitesLoading,
    siteError,
    selectedSite,

    // Computed
    controllerOptions,
    siteOptions,
    canViewReports,

    // Methods
    clearAlert,
    handleControllerChange,
    handleViewButtonClick,
  };
}
