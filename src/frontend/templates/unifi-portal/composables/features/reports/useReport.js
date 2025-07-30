import { ref } from "vue";
import { useControllerStore } from "@frontend/stores/controller";
import { useReportStore } from "@frontend/stores/report";
import { createUnifiApi } from "@frontend/services/api/unifi";
import { useAlerts } from "@frontend/composables/core/useAlerts";

/**
 * Composable for managing network report data fetching and state
 */
export function useReport() {
  const controllerStore = useControllerStore();
  const reportStore = useReportStore();
  const { showAlert, clearAlert } = useAlerts();

  const retrying = ref({
    dashboard: false,
    clients: false,
  });

  const abortControllers = new Map();

  const cancelRequest = (type) => {
    if (abortControllers.has(type)) {
      abortControllers.get(type).abort();
      abortControllers.delete(type);
    }
  };

  const fetchDashboard = async () => {
    if (reportStore.loading.dashboard) return;

    cancelRequest("dashboard");

    const abortController = new AbortController();
    abortControllers.set("dashboard", abortController);

    reportStore.setLoading("dashboard", true);
    showAlert("info", "Fetching Data", "Retrieving dashboard data...");

    try {
      const api = createUnifiApi(abortController.signal);
      const response = await (controllerStore.systemType === "modern"
        ? api.modern.dashboard()
        : api.legacy.dashboard());

      reportStore.setDashboard(response);
      showAlert("success", "Success", "Dashboard data retrieved successfully");
    } catch (error) {
      if (error.name === "AbortError") return;

      const errorMsg = error.message || "Failed to fetch dashboard data";
      reportStore.setError("dashboard", errorMsg);
      showAlert("error", "Dashboard Error", errorMsg);
    } finally {
      if (abortControllers.get("dashboard") === abortController) {
        abortControllers.delete("dashboard");
      }
      reportStore.setLoading("dashboard", false);
      retrying.value.dashboard = false;
    }
  };

  const fetchClients = async () => {
    if (reportStore.loading.clients) return;

    cancelRequest("clients");

    const abortController = new AbortController();
    abortControllers.set("clients", abortController);

    reportStore.setLoading("clients", true);
    showAlert("info", "Fetching Data", "Retrieving client data...");

    try {
      const api = createUnifiApi(abortController.signal);
      const response = await (controllerStore.systemType === "modern"
        ? api.modern.clients()
        : api.legacy.clients());

      reportStore.setClients(response);
      showAlert("success", "Success", "Client data retrieved successfully");
    } catch (error) {
      if (error.name === "AbortError") return;

      const errorMsg = error.message || "Failed to fetch client data";
      reportStore.setError("clients", errorMsg);
      showAlert("error", "Clients Error", errorMsg);
    } finally {
      if (abortControllers.get("clients") === abortController) {
        abortControllers.delete("clients");
      }
      reportStore.setLoading("clients", false);
      retrying.value.clients = false;
    }
  };

  const fetchAllData = async () => {
    clearAlert();
    await fetchDashboard();
    await fetchClients();
  };

  const retryDashboard = async () => {
    retrying.value.dashboard = true;
    await fetchDashboard();
  };

  const retryClients = async () => {
    retrying.value.clients = true;
    await fetchClients();
  };

  const refresh = async () => {
    await fetchAllData();
  };

  const clearReportData = () => {
    for (const type of abortControllers.keys()) {
      cancelRequest(type);
    }
    reportStore.clearData();
    clearAlert();
  };

  return {
    retrying,
    reportStore,
    fetchDashboard,
    fetchClients,
    fetchAllData,
    retryDashboard,
    retryClients,
    refresh,
    clearReportData,
  };
}
