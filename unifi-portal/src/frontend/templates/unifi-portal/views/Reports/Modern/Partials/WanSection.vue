<template>
  <div class="wan-section">
    <!-- WAN Information -->
    <h2>WAN Information</h2>
    <div class="info-box">
      <div
        v-for="(wan, index) in wanInfo"
        :key="`wan-${index}`"
        class="info-item"
      >
        <div class="info-label">{{ wan.label }}</div>
        <div class="info-value">{{ wan.value }}</div>
      </div>
    </div>

    <!-- Speed Test Results -->
    <h3>Speed Test Results</h3>
    <div class="info-box">
      <div
        v-for="(speed, index) in speedTests"
        :key="`speed-${index}`"
        class="info-item"
      >
        <div class="info-label">{{ speed.label }}</div>
        <div class="info-value">{{ speed.value }}</div>
        <div v-if="speed.progressBar" class="progress-bar">
          <div
            class="progress-fill"
            :class="speed.progressClass"
            :style="{ width: speed.progressValue }"
          ></div>
        </div>
        <div
          v-if="speed.subLabel"
          class="info-label"
          style="margin-top: 8px; margin-bottom: 0"
        >
          {{ speed.subLabel }}
        </div>
      </div>
    </div>

    <!-- WAN Stability -->
    <h3>WAN Stability</h3>
    <div
      v-for="(stability, index) in wanStability"
      :key="`stability-${index}`"
      class="metric"
    >
      <div class="metric-label">{{ stability.label }}</div>
      <div class="metric-value">{{ stability.value }}</div>
    </div>

    <!-- WAN Stability Chart -->
    <div class="chart">
      <div class="chart-title">WAN Latency & Stability (30 Days)</div>
      <svg width="600" height="200" viewBox="0 0 600 200">
        <!-- Background Grid -->
        <g fill="none" stroke="#e0e0e0" stroke-width="1">
          <!-- Horizontal grid lines -->
          <line x1="40" y1="20" x2="580" y2="20" />
          <line x1="40" y1="60" x2="580" y2="60" />
          <line x1="40" y1="100" x2="580" y2="100" />
          <line x1="40" y1="140" x2="580" y2="140" />
          <line x1="40" y1="180" x2="580" y2="180" />

          <!-- Vertical grid lines -->
          <line x1="40" y1="20" x2="40" y2="180" />
          <line x1="130" y1="20" x2="130" y2="180" />
          <line x1="220" y1="20" x2="220" y2="180" />
          <line x1="310" y1="20" x2="310" y2="180" />
          <line x1="400" y1="20" x2="400" y2="180" />
          <line x1="490" y1="20" x2="490" y2="180" />
          <line x1="580" y1="20" x2="580" y2="180" />
        </g>

        <!-- Y-axis labels -->
        <g
          fill="#666"
          font-size="12"
          font-family="Arial, sans-serif"
          text-anchor="end"
        >
          <text x="35" y="25">50ms</text>
          <text x="35" y="65">40ms</text>
          <text x="35" y="105">30ms</text>
          <text x="35" y="145">20ms</text>
          <text x="35" y="185">10ms</text>
        </g>

        <!-- X-axis labels -->
        <g
          fill="#666"
          font-size="12"
          font-family="Arial, sans-serif"
          text-anchor="middle"
        >
          <text x="40" y="195">Jan 28</text>
          <text x="130" y="195">Feb 4</text>
          <text x="220" y="195">Feb 11</text>
          <text x="310" y="195">Feb 18</text>
          <text x="400" y="195">Feb 19</text>
          <text x="490" y="195">Feb 22</text>
          <text x="580" y="195">Feb 26</text>
        </g>

        <!-- Average Latency Line -->
        <polyline
          fill="none"
          stroke="#0559C9"
          stroke-width="2"
          points="
          40,140
          70,150
          100,130
          130,135
          160,120
          190,110
          220,105
          250,100
          280,110
          310,90
          340,95
          370,85
          400,90
          430,100
          460,80
          490,95
          520,75
          550,85
          580,110
        "
        />

        <!-- Outage Events -->
        <g fill="#dc3545">
          <circle cx="100" cy="130" r="4" />
          <circle cx="190" cy="110" r="4" />
          <circle cx="280" cy="110" r="4" />
          <circle cx="370" cy="85" r="4" />
          <circle cx="460" cy="80" r="4" />
          <circle cx="550" cy="85" r="4" />
        </g>

        <!-- Chart Legend -->
        <g font-size="12" font-family="Arial, sans-serif">
          <rect x="460" y="30" width="12" height="2" fill="#0559C9" />
          <text x="477" y="34" fill="#333">Average Latency</text>

          <circle cx="466" cy="47" r="4" fill="#dc3545" />
          <text x="477" y="50" fill="#333">Outage Event</text>
        </g>
      </svg>
    </div>

    <!-- Service Latency -->
    <h3>Service Latency</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Service</th>
          <th>Latency</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(service, index) in serviceLatency"
          :key="`service-${index}`"
        >
          <td>{{ service.name }}</td>
          <td>{{ service.latency }}</td>
          <td>
            <span :class="service.statusClass">{{ service.status }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "WanSection",

  props: {
    wanInfo: {
      type: Array,
      required: true,
    },
    speedTests: {
      type: Array,
      required: true,
    },
    wanStability: {
      type: Array,
      required: true,
    },
    serviceLatency: {
      type: Array,
      required: true,
    },
  },
};
</script>
