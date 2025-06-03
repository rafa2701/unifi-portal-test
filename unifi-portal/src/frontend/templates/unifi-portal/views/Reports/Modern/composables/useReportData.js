import { ref, onMounted } from "vue";
import { useControllerStore } from "@frontend/stores/controller";

/**
 * Provides report data and state management
 */
export function useReportData() {
  const store = useControllerStore();

  // Generate current date/time string in the format "February 28, 2025 14:04:51"
  const now = new Date();
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false,
  };
  const currentDateTime = now.toLocaleDateString("en-US", options);

  // Report data - would come from API in real application
  const reportData = ref({
    systemName: "Thundering Surf UDM-Pro",
    generatedDate: currentDateTime,
    reportPeriod: "Jan 28, 2025 - Feb 26, 2025 (30 days)",
    controllerVersion: "8.0.28",

    executiveSummary:
      "This report provides a comprehensive overview of the Thundering Surf network's performance over the past 30 days. The network shows good overall health with 100% WAN uptime, though there have been several connectivity issues including high latency events and packet loss. WiFi performance varies across access points with some showing high retry rates that should be investigated.",

    networkStats: [
      { label: "Active Clients", value: "84" },
      { label: "Total Download", value: "98.21 GB" },
      { label: "Total Upload", value: "48.73 GB" },
      { label: "WAN Uptime", value: "100.0%" },
    ],

    systemInfo: [
      { label: "System Name", value: "Thundering Surf UDM-Pro" },
      { label: "Controller Version", value: "8.0.28" },
      { label: "System Version", value: "9.0.108" },
      { label: "Uptime", value: "24d 5h 49m 34s" },
    ],

    networkHealth: [
      {
        label: "WAN Status",
        value: "Connected (Comcast Business)",
        statusClass: "status-ok",
      },
      {
        label: "WLAN Status",
        value: "Operational (4/4 APs Online)",
        statusClass: "status-ok",
      },
      {
        label: "LAN Status",
        value: "Operational (69 Devices)",
        statusClass: "status-ok",
      },
      {
        label: "System Load",
        value: "CPU: 20.5% | Memory: 76.2%",
        progressBar: true,
        progressValue: "76.2%",
        progressClass: "",
      },
    ],

    alerts: [
      {
        title: "NETWORK WAN FAILED MULTIPLE TIMES",
        time: "February 26, 2025 01:33:50",
      },
      {
        title: "ISP HIGH LATENCY",
        time: "February 26, 2025 01:27:13",
      },
      {
        title: "ISP PACKET LOSS",
        time: "February 26, 2025 01:26:28",
      },
    ],

    wanInfo: [
      { label: "WAN IP", value: "50.198.231.10" },
      { label: "ISP", value: "Comcast Business" },
      { label: "Interface", value: "eth8" },
      { label: "Gateway IP", value: "10.0.0.1" },
    ],

    speedTests: [
      {
        label: "Download Speed",
        value: "941 Mbps",
        progressBar: true,
        progressValue: "94%",
        progressClass: "",
        subLabel: "ISP Plan: 286 Mbps",
      },
      {
        label: "Upload Speed",
        value: "311 Mbps",
        progressBar: true,
        progressValue: "90%",
        progressClass: "",
        subLabel: "ISP Plan: 30 Mbps",
      },
      {
        label: "Latency",
        value: "18 ms",
      },
    ],

    wanStability: [
      { label: "WAN Uptime", value: "100.0%" },
      { label: "Downtime Events", value: "16 events" },
      { label: "Total Downtime", value: "1082 seconds (18 minutes)" },
      { label: "Average Latency", value: "21.6 ms" },
    ],

    serviceLatency: [
      {
        name: "Microsoft",
        latency: "18 ms",
        status: "100% Available",
        statusClass: "status-ok",
      },
      {
        name: "Google",
        latency: "18 ms",
        status: "100% Available",
        statusClass: "status-ok",
      },
      {
        name: "Cloudflare",
        latency: "18 ms",
        status: "100% Available",
        statusClass: "status-ok",
      },
    ],

    trafficSummary: [
      { label: "Total Download", value: "98.21 GB" },
      { label: "Total Upload", value: "48.73 GB" },
      { label: "Current Rate", value: "↓ 466.5 KB/s ↑ 4.6 KB/s" },
      { label: "Peak Rate", value: "↓ 11.31 MB/s ↑ 4.17 MB/s" },
    ],

    topApplications: [
      {
        name: "App #185 (Category 20)",
        download: "43.58 GB",
        upload: "2.08 GB",
        total: "45.67 GB",
      },
      {
        name: "App #112 (Category 4)",
        download: "8.90 GB",
        upload: "0.13 GB",
        total: "9.03 GB",
      },
      {
        name: "App #9 (Category 14)",
        download: "8.31 GB",
        upload: "0.64 GB",
        total: "8.95 GB",
      },
      {
        name: "App #10 (Category 4)",
        download: "5.29 GB",
        upload: "0.09 GB",
        total: "5.38 GB",
      },
      {
        name: "App #94 (Category 19)",
        download: "4.57 GB",
        upload: "0.06 GB",
        total: "4.64 GB",
      },
    ],

    accessPoints: [
      {
        name: "U6 Pro",
        model: "UAP6MP",
        clients: "14",
        traffic: "72.9%",
        retryRate: "2.2%",
        tagText: "Good",
        tagClass: "tag-success",
      },
      {
        name: "UAP-AC-M - Food Truck",
        model: "U7MSH",
        clients: "4",
        traffic: "0.2%",
        retryRate: "23.3%",
        tagText: "High",
        tagClass: "tag-warning",
      },
      {
        name: "UAP-AC-M - Snack Bar",
        model: "U7MSH",
        clients: "3",
        traffic: "1.3%",
        retryRate: "32.5%",
        tagText: "Critical",
        tagClass: "tag-danger",
      },
      {
        name: "U6 Lite",
        model: "UAL6",
        clients: "14",
        traffic: "25.6%",
        retryRate: "2.6%",
        tagText: "Good",
        tagClass: "tag-success",
      },
    ],

    activeDevices: [
      {
        name: "TSURF-DANS",
        type: "Dell Inc.",
        download: "23.13 GB",
        upload: "1.00 GB",
        total: "24.13 GB",
      },
      {
        name: "Apple iPad",
        type: "Apple iPad",
        download: "11.07 GB",
        upload: "0.38 GB",
        total: "11.45 GB",
      },
      {
        name: "Apple iPad Pro",
        type: "Apple iPad",
        download: "8.71 GB",
        upload: "0.25 GB",
        total: "8.96 GB",
      },
      {
        name: "Apple iPad 10.2 (9th Gen)",
        type: "Apple iPad",
        download: "8.38 GB",
        upload: "0.38 GB",
        total: "8.76 GB",
      },
      {
        name: "LAPTOP-KH9THLOC",
        type: "TP-Link Corporation Limited",
        download: "7.29 GB",
        upload: "0.19 GB",
        total: "7.48 GB",
      },
    ],

    recommendations: [
      {
        category: "WiFi Performance",
        title: "Investigate High Retry Rates",
        description:
          "The UAP-AC-M access points at both Food Truck and Snack Bar locations are experiencing high retry rates (23.3% and 32.5% respectively). Consider relocating these access points or checking for sources of interference.",
      },
      {
        category: "WAN Stability",
        title: "Monitor ISP Connection",
        description:
          "Recent alerts indicate issues with WAN connectivity, high latency, and packet loss. Contact Comcast Business to investigate potential issues with the connection quality.",
      },
    ],
  });

  /**
   * Updates content based on selected controller if needed
   */
  onMounted(() => {
    if (store.selectedController) {
      reportData.value.systemName = store.selectedController.name;
    }
  });

  return {
    reportData,
  };
}
