import { useRouter } from "vue-router";

/**
 * Handles report navigation functions
 */
export function useReportNavigation() {
  const router = useRouter();

  /**
   * Navigate back to the home page
   */
  const goBack = () => {
    router.push({ path: "/" });
  };

  /**
   * Print the report using the browser's print functionality
   */
  const printReport = () => {
    window.print();
  };

  return {
    goBack,
    printReport,
  };
}
