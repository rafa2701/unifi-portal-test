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

  /**
   * Shows an alert with the specified type, title and message
   *
   * @param {string} type - Alert type (info, success, warning, error)
   * @param {string} title - Alert title
   * @param {string} message - Alert message
   */
  const showAlert = (type, title, message) => {
    alert.value = {
      show: true,
      type,
      title,
      message,
    };
  };

  /**
   * Clears the currently displayed alert
   */
  const clearAlert = () => {
    alert.value.show = false;
  };

  return {
    alert,
    showAlert,
    clearAlert,
  };
}
