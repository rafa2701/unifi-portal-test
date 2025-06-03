import { ref } from "vue";

/**
 * Composable for managing alert states and messages
 */
export function useAlerts() {
  const alert = ref({
    show: false,
    type: "info",
    title: "",
    message: "",
  });

  const showAlert = (type, title, message) => {
    alert.value = {
      show: true,
      type,
      title,
      message,
    };
  };

  const clearAlert = () => {
    alert.value.show = false;
  };

  return {
    alert,
    showAlert,
    clearAlert,
  };
}
