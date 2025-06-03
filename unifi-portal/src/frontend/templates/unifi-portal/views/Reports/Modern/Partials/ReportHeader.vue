<template>
  <div class="header">
    <div class="logo-container">
      <img :src="$img('icons/unifi-logo.svg')" alt="UniFi Logo" class="logo" />
      <div class="logo-text">UniFi</div>
    </div>
    <h1>Network Performance Report</h1>
    <p>{{ systemName }}</p>
    <p>Generated on {{ generatedDate }}</p>
    <p>Report period: {{ reportPeriod }}</p>
  </div>
</template>

<script>
export default {
  name: "ReportHeader",

  props: {
    systemName: {
      type: String,
      required: true,
    },
    generatedDate: {
      type: String,
      required: true,
    },
    reportPeriod: {
      type: String,
      required: true,
    },
  },
};
</script>
