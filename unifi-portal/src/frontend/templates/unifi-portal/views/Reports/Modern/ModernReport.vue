<template>
  <div class="modern-report">
    <div class="container">
      <!-- Report Header -->
      <ReportHeader
        :systemName="reportData.systemName"
        :generatedDate="reportData.generatedDate"
        :reportPeriod="reportData.reportPeriod"
      />

      <!-- Summary Section -->
      <SummarySection
        :executiveSummary="reportData.executiveSummary"
        :networkStats="reportData.networkStats"
        :systemInfo="reportData.systemInfo"
        :networkHealth="reportData.networkHealth"
        :alerts="reportData.alerts"
      />

      <!-- WAN Section -->
      <WanSection
        :wanInfo="reportData.wanInfo"
        :speedTests="reportData.speedTests"
        :wanStability="reportData.wanStability"
        :serviceLatency="reportData.serviceLatency"
      />

      <!-- Network Section -->
      <NetworkSection
        :trafficSummary="reportData.trafficSummary"
        :topApplications="reportData.topApplications"
        :accessPoints="reportData.accessPoints"
        :activeDevices="reportData.activeDevices"
      />

      <!-- Recommendations and Footer -->
      <RecommendationsFooter
        :recommendations="reportData.recommendations"
        :systemName="reportData.systemName"
        :controllerVersion="reportData.controllerVersion"
        :reportPeriod="reportData.reportPeriod"
      />
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons no-print">
      <button class="action-button back-button" @click="goBack">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Back
      </button>

      <button
        class="action-button save-button"
        @click="savePDF"
        :disabled="generatingPDF"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        {{ generatingPDF ? "Generating PDF..." : "Save as PDF" }}
      </button>
    </div>
  </div>
</template>

<script>
import ReportHeader from "./Partials/ReportHeader.vue";
import SummarySection from "./Partials/SummarySection.vue";
import WanSection from "./Partials/WanSection.vue";
import NetworkSection from "./Partials/NetworkSection.vue";
import RecommendationsFooter from "./Partials/RecommendationsFooter.vue";
import useModernReport from "./ModernReport";

export default {
  name: "ModernReport",

  components: {
    ReportHeader,
    SummarySection,
    WanSection,
    NetworkSection,
    RecommendationsFooter,
  },

  setup() {
    const { reportData, generatingPDF, printReport, savePDF, goBack } =
      useModernReport();

    return {
      reportData,
      generatingPDF,
      savePDF,
      goBack,
    };
  },
};
</script>

<style lang="scss">
@forward "./ModernReport.scss";
</style>
