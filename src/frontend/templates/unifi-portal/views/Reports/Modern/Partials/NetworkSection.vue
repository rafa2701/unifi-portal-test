<template>
  <div class="network-section">
    <!-- Network Performance -->
    <h2 class="page-break-before">Network Performance</h2>
    <div class="chart">
      <div class="chart-title">30-Day Traffic Trend</div>
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
          <text x="35" y="25">100MB</text>
          <text x="35" y="65">80MB</text>
          <text x="35" y="105">60MB</text>
          <text x="35" y="145">40MB</text>
          <text x="35" y="185">20MB</text>
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

        <!-- Download Line -->
        <polyline
          fill="none"
          stroke="#0559C9"
          stroke-width="2"
          points="
          40,170
          70,165
          100,80
          130,160
          160,170
          190,175
          220,130
          250,140
          280,170
          310,160
          340,165
          370,145
          400,150
          430,175
          460,100
          490,145
          520,135
          550,170
          580,120
        "
        />

        <!-- Upload Line -->
        <polyline
          fill="none"
          stroke="#28a745"
          stroke-width="2"
          points="
          40,180
          70,175
          100,175
          130,180
          160,180
          190,180
          220,165
          250,150
          280,150
          310,150
          340,170
          370,165
          400,160
          430,160
          460,155
          490,155
          520,180
          550,175
          580,175
        "
        />

        <!-- Chart Legend -->
        <g font-size="12" font-family="Arial, sans-serif">
          <rect x="460" y="30" width="12" height="2" fill="#0559C9" />
          <text x="477" y="34" fill="#333">Download</text>

          <rect x="460" y="47" width="12" height="2" fill="#28a745" />
          <text x="477" y="50" fill="#333">Upload</text>
        </g>
      </svg>
    </div>

    <!-- Traffic Summary -->
    <h3>Traffic Summary</h3>
    <div class="info-box">
      <div
        v-for="(traffic, index) in trafficSummary"
        :key="`traffic-${index}`"
        class="info-item"
      >
        <div class="info-label">{{ traffic.label }}</div>
        <div class="info-value">{{ traffic.value }}</div>
      </div>
    </div>

    <!-- Top Applications -->
    <h3>Top Applications by Traffic</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Application</th>
          <th>Download</th>
          <th>Upload</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(app, index) in topApplications" :key="`app-${index}`">
          <td>{{ app.name }}</td>
          <td>{{ app.download }}</td>
          <td>{{ app.upload }}</td>
          <td>{{ app.total }}</td>
        </tr>
      </tbody>
    </table>

    <!-- WiFi Performance -->
    <h2 class="page-break-before">WiFi Performance</h2>
    <h3>Access Point Summary</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Access Point</th>
          <th>Model</th>
          <th>Clients</th>
          <th>Traffic %</th>
          <th>Retry Rate</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(ap, index) in accessPoints" :key="`ap-${index}`">
          <td>{{ ap.name }}</td>
          <td>{{ ap.model }}</td>
          <td>{{ ap.clients }}</td>
          <td>{{ ap.traffic }}</td>
          <td>
            {{ ap.retryRate }}
            <span :class="`tag ${ap.tagClass}`">{{ ap.tagText }}</span>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Most Active Devices -->
    <h3>Most Active Devices</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Device</th>
          <th>Type</th>
          <th>Download</th>
          <th>Upload</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(device, index) in activeDevices" :key="`device-${index}`">
          <td>{{ device.name }}</td>
          <td>{{ device.type }}</td>
          <td>{{ device.download }}</td>
          <td>{{ device.upload }}</td>
          <td>{{ device.total }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "NetworkSection",

  props: {
    trafficSummary: {
      type: Array,
      required: true,
    },
    topApplications: {
      type: Array,
      required: true,
    },
    accessPoints: {
      type: Array,
      required: true,
    },
    activeDevices: {
      type: Array,
      required: true,
    },
  },
};
</script>
