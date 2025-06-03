<template>
  <div class="summary-section">
    <!-- Executive Summary -->
    <h2>Executive Summary</h2>
    <p>{{ executiveSummary }}</p>

    <!-- Network Stats -->
    <div class="network-stats">
      <div
        v-for="(stat, index) in networkStats"
        :key="`stat-${index}`"
        class="stat-item"
      >
        <div class="stat-value">{{ stat.value }}</div>
        <div class="stat-label">{{ stat.label }}</div>
      </div>
    </div>

    <!-- System Information -->
    <h2>System Information</h2>
    <div class="info-box">
      <div
        v-for="(info, index) in systemInfo"
        :key="`sysinfo-${index}`"
        class="info-item"
      >
        <div class="info-label">{{ info.label }}</div>
        <div class="info-value">{{ info.value }}</div>
      </div>
    </div>

    <!-- Network Health Status -->
    <h2>Network Health Status</h2>
    <div class="info-box">
      <div
        v-for="(health, index) in networkHealth"
        :key="`health-${index}`"
        class="info-item"
      >
        <div class="info-label">{{ health.label }}</div>
        <div v-if="health.progressBar" class="info-value">
          {{ health.value }}
          <div class="progress-bar">
            <div
              class="progress-fill"
              :class="health.progressClass"
              :style="{ width: health.progressValue }"
            ></div>
          </div>
          <div
            v-if="health.subLabel"
            class="info-label"
            style="margin-top: 8px; margin-bottom: 0"
          >
            {{ health.subLabel }}
          </div>
        </div>
        <div v-else class="info-value" :class="health.statusClass">
          {{ health.value }}
        </div>
      </div>
    </div>

    <!-- Active Alerts -->
    <h2>Active Alerts</h2>
    <div v-if="alerts && alerts.length > 0" class="alert">
      <div
        v-for="(alert, index) in alerts"
        :key="`alert-${index}`"
        class="alert-item"
      >
        <div class="alert-title">{{ alert.title }}</div>
        <div class="alert-time">{{ alert.time }}</div>
      </div>
    </div>
    <p v-else>No active alerts at this time.</p>
  </div>
</template>

<script>
export default {
  name: "SummarySection",

  props: {
    executiveSummary: {
      type: String,
      required: true,
    },
    networkStats: {
      type: Array,
      required: true,
    },
    systemInfo: {
      type: Array,
      required: true,
    },
    networkHealth: {
      type: Array,
      required: true,
    },
    alerts: {
      type: Array,
      required: true,
    },
  },
};
</script>
