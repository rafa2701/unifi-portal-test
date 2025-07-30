<template>
  <div class="test-view">
    <div class="test-content">
      <BaseCard class="test-card">
        <h3>Connection Test</h3>
        <div class="test-form">
          <BaseSelect
            v-model="selectedController"
            :options="controllerOptions"
            label="Select Controller"
            placeholder="Choose a controller to test"
          />
          <BaseButton
            variant="primary"
            icon="Activity"
            :loading="testing"
            :disabled="!selectedController"
            @click="runTest"
          >
            Test Connection
          </BaseButton>
        </div>
      </BaseCard>

      <BaseCard v-if="testResult" class="result-card">
        <div class="result-header">
          <h3>Test Results</h3>
          <span
            :class="['status-badge', testResult.success ? 'success' : 'error']"
          >
            {{ testResult.success ? "Success" : "Failed" }}
          </span>
        </div>
        <div class="result-details">
          <div class="detail-item">
            <span class="label">Controller:</span>
            <span class="value">{{ testResult.controller }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Response Time:</span>
            <span class="value">{{ testResult.responseTime }}ms</span>
          </div>
          <div class="detail-item">
            <span class="label">Status:</span>
            <span class="value">{{ testResult.status }}</span>
          </div>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import BaseCard from "@admin/components/base/BaseCard.vue";
import BaseButton from "@admin/components/base/BaseButton.vue";
import BaseSelect from "@admin/components/base/BaseSelect.vue";
import { Activity } from "lucide-vue-next";

export default {
  name: "TestView",

  components: {
    BaseCard,
    BaseButton,
    BaseSelect,
    Activity,
  },

  setup() {
    const selectedController = ref(null);
    const testing = ref(false);
    const testResult = ref(null);

    const controllerOptions = [
      { value: 1, label: "Production Controller" },
      { value: 2, label: "Development Controller" },
    ];

    const runTest = async () => {
      testing.value = true;
      try {
        await new Promise((resolve) => setTimeout(resolve, 2000));
        testResult.value = {
          success: true,
          controller: "Production Controller",
          responseTime: 245,
          status: "Connected",
        };
      } finally {
        testing.value = false;
      }
    };

    return {
      selectedController,
      testing,
      testResult,
      controllerOptions,
      runTest,
    };
  },
};
</script>

<style lang="scss">
.test-view {
  padding: map.get($spacing, "xl") 0;

  .test-content {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "xl");
  }

  .test-card {
    h3 {
      font-size: map.get($font-size, "xl");
      color: map.get($colors, "slate", 900);
      margin-bottom: map.get($spacing, "xl");
    }
  }

  .test-form {
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "lg");
  }

  .result-card {
    .result-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: map.get($spacing, "xl");

      h3 {
        font-size: map.get($font-size, "xl");
        color: map.get($colors, "slate", 900);
        margin: 0;
      }

      .status-badge {
        padding: map.get($spacing, "xs") map.get($spacing, "sm");
        border-radius: 1rem;
        font-size: map.get($font-size, "sm");
        font-weight: 500;

        &.success {
          background: map.get($colors, "accent", "success");
          color: white;
        }

        &.error {
          background: map.get($colors, "accent", "error");
          color: white;
        }
      }
    }

    .result-details {
      display: flex;
      flex-direction: column;
      gap: map.get($spacing, "md");

      .detail-item {
        display: flex;
        justify-content: space-between;
        padding: map.get($spacing, "sm") 0;
        border-bottom: 1px solid map.get($colors, "slate", 200);

        &:last-child {
          border-bottom: none;
        }

        .label {
          color: map.get($colors, "slate", 500);
          font-size: map.get($font-size, "sm");
        }

        .value {
          color: map.get($colors, "slate", 900);
          font-weight: 500;
        }
      }
    }
  }
}
</style>
