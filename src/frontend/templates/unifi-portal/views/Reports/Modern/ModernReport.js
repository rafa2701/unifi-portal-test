import { useReportData } from "./composables/useReportData";
import { useReportNavigation } from "./composables/useReportNavigation";
import { useReportExport } from "./composables/useReportExport";

/**
 * Main composable for the Modern Report component
 * Combines all report functionality
 */
export default function useModernReport() {
  // Get data and functionality from separate modules
  const { reportData } = useReportData();
  const { goBack, printReport } = useReportNavigation();
  const { generatingPDF, savePDF } = useReportExport();

  // Create a wrapper for savePDF to pass in the system name
  const handleSavePDF = () => {
    savePDF(reportData.value.systemName);
  };

  return {
    reportData,
    generatingPDF,
    printReport,
    savePDF: handleSavePDF,
    goBack,
  };
}
