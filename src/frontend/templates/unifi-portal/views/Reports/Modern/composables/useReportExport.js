import { ref } from "vue";
import html2pdf from "html2pdf.js";

/**
 * Handles report PDF export functionality
 */
export function useReportExport() {
  const generatingPDF = ref(false);

  /**
   * Save the report as a PDF file
   */
  const savePDF = async (systemName) => {
    generatingPDF.value = true;

    // Find the container element to convert
    const element = document.querySelector(".container");
    if (!element) {
      generatingPDF.value = false;
      return;
    }

    // Hide the action buttons during PDF generation
    const actionButtonsElement = document.querySelector(".action-buttons");
    if (actionButtonsElement) {
      actionButtonsElement.style.display = "none";
    }

    try {
      // Create a clone of the element for PDF
      const clone = element.cloneNode(true);
      const tempDiv = document.createElement("div");
      tempDiv.appendChild(clone);
      document.body.appendChild(tempDiv);
      tempDiv.style.position = "absolute";
      tempDiv.style.left = "-9999px";

      // Replace SVG logo with an image in the clone
      const logoContainer = clone.querySelector(".logo-container");
      if (logoContainer) {
        try {
          // Save the logo text
          const logoText = logoContainer.querySelector(".logo-text");
          const logoTextContent = logoText ? logoText.outerHTML : "";

          // Replace the logo-container content
          logoContainer.innerHTML = `
            <img src="${window.UnifiPortalData.image.url}/icons/unifi-logo.svg"
                 alt="UniFi Logo"
                 style="width: 54px; height: 54px; margin-right: 15px;">
            ${logoTextContent}
          `;
        } catch (logoError) {
          console.error("Error replacing logo:", logoError);
        }
      }

      // Adjust chart lines to ensure they render correctly
      const svgPolylines = clone.querySelectorAll("svg polyline");
      svgPolylines.forEach((polyline) => {
        // Add stroke-linecap and increase stroke-width to make lines more visible
        polyline.setAttribute("stroke-linecap", "round");
        polyline.setAttribute("stroke-linejoin", "round");

        // Get current stroke-width and increase it slightly
        const currentWidth = polyline.getAttribute("stroke-width") || "1";
        const newWidth = parseFloat(currentWidth) + 0.5;
        polyline.setAttribute("stroke-width", newWidth.toString());
      });

      // Add inline CSS to ensure PDF styling
      const styleEl = document.createElement("style");
      styleEl.textContent = `
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        h1 { font-size: 20px; color: #0559c9; margin-bottom: 10px; }
        h2 { font-size: 16px; color: #0559c9; margin-top: 25px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #e0e0e0; }
        h3 { font-size: 14px; margin-top: 20px; margin-bottom: 10px; }
        p { margin-bottom: 10px; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #0559c9; }
        .logo-container { display: flex; justify-content: center; align-items: center; margin-bottom: 15px; }
        .logo-text { font-size: 22px; font-weight: bold; color: #0559c9; }
        .info-box { display: flex; flex-wrap: wrap; margin: 0 -8px 20px -8px; }
        .info-item { flex: 1; min-width: 200px; background-color: #f8fafd; border-radius: 6px; padding: 12px; margin: 8px; border: 1px solid #e6e9f0; }
        .info-label { font-size: 12px; color: #666; margin-bottom: 6px; font-weight: 500; }
        .info-value { font-size: 16px; font-weight: 600; color: #333; }
        .network-stats { display: flex; flex-wrap: wrap; margin-bottom: 20px; }
        .stat-item { flex: 1; min-width: 150px; text-align: center; padding: 12px; background-color: #f8fafd; border-radius: 6px; margin: 5px; border: 1px solid #e6e9f0; }
        .stat-value { font-size: 18px; font-weight: 600; color: #0559c9; margin-bottom: 5px; }
        .stat-label { font-size: 12px; color: #666; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #e0e0e0; padding: 8px; font-size: 12px; text-align: left; }
        .table th { background-color: #f0f4f9; font-weight: 600; color: #333; }
        .table tr:nth-child(even) { background-color: #f9fafc; }
        .metric { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .metric-label { color: #555; font-weight: 500; }
        .metric-value { font-weight: 600; }
        .chart { height: 200px; background-color: #f8fafd; border-radius: 6px; margin-bottom: 20px; padding: 15px; border: 1px solid #e6e9f0; }
        .chart-title { font-size: 14px; font-weight: 600; margin-bottom: 10px; color: #333; text-align: center; }
        .alert { background-color: #fff8f8; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 3px solid #ef4444; }
        .alert-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid rgba(239, 68, 68, 0.2); }
        .alert-title { font-weight: 600; margin-bottom: 3px; }
        .alert-time { font-size: 11px; color: #666; }
        .status-ok { color: #10b981; }
        .status-warning { color: #f59e0b; }
        .status-error { color: #ef4444; }
        .tag { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600; margin-left: 5px; }
        .tag-success { background-color: #e6f7ed; color: #10b981; }
        .tag-warning { background-color: #fff8e6; color: #f59e0b; }
        .tag-danger { background-color: #feebee; color: #ef4444; }
        .progress-bar { width: 100%; background-color: #eef1f5; border-radius: 50px; height: 6px; margin-top: 6px; overflow: hidden; }
        .progress-fill { height: 100%; background-color: #0559c9; }
        .progress-fill.warning { background-color: #f59e0b; }
        .progress-fill.danger { background-color: #ef4444; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e0e0e0; font-size: 11px; color: #666; text-align: center; }

        /* Enhance SVG rendering */
        svg { shape-rendering: geometricPrecision; text-rendering: optimizeLegibility; }
        svg polyline { stroke-linecap: round; stroke-linejoin: round; }
        svg circle { fill-opacity: 1; }
      `;

      clone.prepend(styleEl);

      // Make sure progress bars have correct widths
      const progressFills = clone.querySelectorAll(".progress-fill");
      progressFills.forEach((fill) => {
        if (fill.style.width) {
          const width = fill.style.width;
          fill.setAttribute("style", `width: ${width} !important`);
        }
      });

      // Create PDF options with better image quality
      const opt = {
        filename: `${systemName.replace(/\s+/g, "_")}_Report.pdf`,
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: {
          scale: 2,
          useCORS: true,
          allowTaint: true,
          logging: false,
          letterRendering: true,
          scrollX: 0,
          scrollY: 0,
        },
        jsPDF: {
          unit: "mm",
          format: "a4",
          orientation: "portrait",
          compress: true,
        },
        margin: [10, 10, 10, 10],
        pagebreak: {
          mode: ["avoid-all", "css"],
          before: ".page-break-before",
          after: ".page-break-after",
          avoid: ".page-break-avoid",
        },
      };

      // Generate PDF
      await html2pdf().from(clone).set(opt).save();

      // Clean up
      document.body.removeChild(tempDiv);
    } catch (error) {
      console.error("Error generating PDF:", error);
    } finally {
      // Show the action buttons again
      if (actionButtonsElement) {
        actionButtonsElement.style.display = "flex";
      }
      generatingPDF.value = false;
    }
  };

  return {
    generatingPDF,
    savePDF,
  };
}
