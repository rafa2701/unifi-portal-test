import { ref, computed } from "vue";
import { debounce } from "lodash";
import { useControllerStore } from "@frontend/stores/controller";
import { createUnifiApi } from "@frontend/services/api/unifi";

export function useController() {
  const store = useControllerStore();
  const abortControllers = new Map();
  const activeRequests = new Set();

  const status = ref({
    loading: false,
    message: "",
    error: null,
    retrying: false,
    stage: null,
  });

  const updateStatus = (stage, message, error = null) => {
    status.value = {
      loading: true,
      message,
      error,
      retrying: false,
      stage,
    };
  };

  const cancelAllRequests = () => {
    abortControllers.forEach((controller) => {
      controller.abort();
    });
    abortControllers.clear();
    handleControllerChange.cancel();
    activeRequests.clear();
  };

  const fetchControllers = async () => {
    if (abortControllers.has("fetch")) {
      abortControllers.get("fetch").abort();
    }

    const fetchAbortController = new AbortController();
    abortControllers.set("fetch", fetchAbortController);

    const tempApi = createUnifiApi(fetchAbortController.signal);

    try {
      updateStatus("controllers", "Fetching available controllers...");
      const response = await tempApi.controllers.getAll();

      if (response.status === "failed") {
        throw new Error(response.error || "Failed to fetch controllers");
      }

      store.controllers = response?.data?.controllers || [];

      status.value = {
        loading: false,
        message: store.controllers.length
          ? "Controllers loaded successfully"
          : "No controllers available",
        error: null,
        stage: null,
      };

      return response;
    } catch (error) {
      if (error.name !== "AbortError") {
        console.error("Failed to fetch controllers:", error);
        store.error = error.message;

        status.value = {
          loading: false,
          message: error.message,
          error: error.message,
          stage: "controllers",
          retrying: false,
        };
      }
      throw error;
    } finally {
      abortControllers.delete("fetch");
    }
  };

  const finalizeConnection = async (controllerKey, site) => {
    if (!controllerKey || !site) return;

    const connectAbortController = new AbortController();
    abortControllers.set("finalConnect", connectAbortController);
    const api = createUnifiApi(connectAbortController.signal);

    try {
      store.setConnectionState("connecting");
      updateStatus("finalizing", "Establishing connection...");

      const response = await api.controllers.connect(controllerKey, site);

      if (response.status === "failed") {
        throw new Error(response.error || "Connection failed");
      }

      store.setConnectionState("connected");
      status.value = {
        loading: false,
        message: "Connected successfully",
        error: null,
        stage: null,
      };
    } catch (error) {
      if (error.name !== "AbortError") {
        store.setConnectionState("disconnected");
        status.value = {
          loading: false,
          message: "Connection failed: " + error.message,
          error: error.message,
          stage: "finalizing",
          retrying: false,
        };
        throw error;
      }
    } finally {
      abortControllers.delete("finalConnect");
    }
  };

  const handleConnectionFlow = async (controllerKey) => {
    if (!controllerKey) {
      throw new Error("Controller key is required");
    }

    if (abortControllers.has("connectionFlow")) {
      abortControllers.get("connectionFlow").abort();
    }

    const flowAbortController = new AbortController();
    abortControllers.set("connectionFlow", flowAbortController);
    const flowApi = createUnifiApi(flowAbortController.signal);

    try {
      status.value.error = null;
      status.value.retrying = false;
      store.setConnectionState("connecting");

      updateStatus("connect", "Initializing connection...");
      const connectResponse = await flowApi.controllers.connect(controllerKey);

      if (connectResponse.status === "failed") {
        throw new Error(connectResponse.error || "Connection failed");
      }

      updateStatus("system", "Detecting system type...");
      const systemResponse = await flowApi.controllers.detectSystem(
        controllerKey
      );

      if (systemResponse.status === "failed") {
        throw new Error(systemResponse.error || "System detection failed");
      }
      store.setSystemType(systemResponse.system_type);

      updateStatus("sites", "Fetching available sites...");
      const sitesResponse = await flowApi.controllers.getSites(controllerKey);

      if (sitesResponse.status === "failed") {
        throw new Error(sitesResponse.error || "Failed to fetch sites");
      }

      store.setSites(sitesResponse.sites || sitesResponse.data?.sites || []);

      if (store.selectedSite) {
        await finalizeConnection(controllerKey, store.selectedSite);
      } else {
        store.setConnectionState("waiting");
        status.value = {
          loading: false,
          message: "Please select a site to continue",
          error: null,
          stage: null,
        };
      }
    } catch (error) {
      if (error.name !== "AbortError") {
        status.value = {
          loading: false,
          message: error.message,
          error: error.message,
          stage: status.value.stage,
          retrying: false,
        };

        store.setConnectionState("disconnected");
        store.setSystemType(null);
        store.setSites([]);
        throw error;
      }
    } finally {
      abortControllers.delete("connectionFlow");
      status.value.loading = false;
    }
  };

  const handleControllerChange = debounce(async (controller) => {
    if (!store.canModifySelection) return;

    const requestId = Symbol("controllerChange");
    activeRequests.add(requestId);

    if (!controller || !controller.value) {
      store.clearController();
      return;
    }

    store.setController({
      key: controller.value,
      name: controller.label,
    });

    try {
      await handleConnectionFlow(controller.value);
    } catch (error) {
      if (error.name !== "AbortError") {
        console.error("Connection flow error:", error);
      }
    } finally {
      status.value.loading = false;
      activeRequests.delete(requestId);
    }
  }, 300);

  const handleSiteChange = async (site) => {
    if (!store.canModifySelection) return;

    store.setSite(site);
    if (store.controllerKey && site) {
      await finalizeConnection(store.controllerKey, site.value);
    }
  };

  const handleRetry = async () => {
    if (!store.controllerKey) return;

    if (abortControllers.has("retry")) {
      abortControllers.get("retry").abort();
    }

    const retryAbortController = new AbortController();
    abortControllers.set("retry", retryAbortController);

    status.value.retrying = true;

    try {
      await handleConnectionFlow(store.controllerKey);
    } catch (error) {
      if (error.name !== "AbortError") {
        console.error("Retry failed:", error);
      }
    } finally {
      status.value.retrying = false;
      abortControllers.delete("retry");
    }
  };

  const cleanup = () => {
    cancelAllRequests();
  };

  const fullCleanup = () => {
    cancelAllRequests();
    store.resetConnection();
  };

  return {
    status,
    controllerOptions: computed(() => store.controllerOptions),
    siteOptions: computed(() => store.siteOptions),
    selectedController: computed(() => store.controllerKey),
    selectedSite: computed(() => store.selectedSite),
    controllerName: computed(() => store.controllerName),
    systemType: computed(() => store.systemType),
    handleControllerChange,
    handleSiteChange,
    handleRetry,
    fetchControllers,
    cancelAllRequests,
    cleanup,
    fullCleanup,
  };
}
