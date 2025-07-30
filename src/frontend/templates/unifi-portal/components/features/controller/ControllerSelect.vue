<template>
  <div
    class="controller-select"
    role="form"
    aria-label="Controller selection form"
  >
    <BaseAlert
      v-if="status.message"
      :type="status.error ? 'error' : status.stage ? 'info' : 'success'"
      :show="true"
      dismissible
    >
      <div class="alert-content">
        <span>{{ status.message }}</span>
        <BaseButton
          v-if="status.error"
          variant="text"
          size="sm"
          :loading="status.retrying"
          @click="handleRetry"
        >
          Retry
        </BaseButton>
      </div>
    </BaseAlert>

    <div class="select-group">
      <label id="controller-label">Controller</label>
      <BaseSelect
        :modelValue="selectedController"
        :options="controllerOptions"
        :loading="status.loading && status.stage === 'controllers'"
        :disabled="!store.canModifySelection || status.loading"
        :error="status.error && status.stage === 'controllers'"
        placeholder="Select a UniFi controller"
        aria-labelledby="controller-label"
        @update:modelValue="onControllerChange"
      />
      <span v-if="!controllerOptions.length" class="helper-text text-warning">
        No controllers available
      </span>
      <span v-else-if="store.isLocked" class="helper-text text-info">
        Controller selection is locked. Reset or disconnect to change.
      </span>
    </div>

    <Transition name="fade">
      <div v-if="store.sites.length > 0 && !status.error" class="select-group">
        <label id="site-label">Site</label>
        <BaseSelect
          :modelValue="selectedSite"
          :options="siteOptions"
          :loading="
            status.loading &&
            (status.stage === 'sites' || status.stage === 'finalizing')
          "
          :disabled="!store.canModifySelection || status.loading"
          :error="
            status.error &&
            (status.stage === 'sites' || status.stage === 'finalizing')
          "
          placeholder="Select a site"
          aria-labelledby="site-label"
          @update:modelValue="onSiteChange"
        />
        <span
          v-if="store.connectionState === 'waiting' && !status.loading"
          class="helper-text text-info"
        >
          Please select a site to continue
        </span>
        <span v-else-if="store.isLocked" class="helper-text text-info">
          Site selection is locked. Reset or disconnect to change.
        </span>
      </div>
    </Transition>

    <Transition name="fade">
      <BaseCard
        v-if="hasControllerDetails"
        variant="outline"
        class="controller-summary"
      >
        <dl class="summary-content">
          <div v-if="controllerName" class="summary-item">
            <dt>Controller Name</dt>
            <dd>{{ controllerName }}</dd>
          </div>
          <div v-if="systemType" class="summary-item">
            <dt>System Version</dt>
            <dd>{{ formatSystemType }}</dd>
          </div>
          <div v-if="connectionStatusInfo.show" class="summary-item">
            <dt>Connection Status</dt>
            <dd :class="connectionStatusInfo.class">
              {{ connectionStatusInfo.text }}
            </dd>
          </div>
          <div v-if="store.sites.length" class="summary-item">
            <dt>Available Sites</dt>
            <dd>{{ store.sites.length }}</dd>
          </div>
        </dl>
      </BaseCard>
    </Transition>
  </div>
</template>

<script>
import { computed, onMounted, onBeforeUnmount } from "vue";
import { useController } from "@frontend/composables/features/controller/useController";
import { useControllerStore } from "@frontend/stores/controller";
import { UNIFI } from "@frontend/constants";
import {
  BaseSelect,
  BaseButton,
  BaseAlert,
  BaseCard,
} from "@frontend/components/base";

export default {
  name: "ControllerSelect",

  components: {
    BaseSelect,
    BaseButton,
    BaseAlert,
    BaseCard,
  },

  setup() {
    const store = useControllerStore();
    const controller = useController();

    const onControllerChange = (selected) => {
      if (!store.canModifySelection) return;

      const selectedController = store.controllerOptions.find(
        (opt) => opt.value === selected
      );
      if (selectedController) {
        controller.handleControllerChange(selectedController);
      }
    };

    const onSiteChange = (selected) => {
      if (!store.canModifySelection) return;

      const selectedSite = store.siteOptions.find(
        (opt) => opt.value === selected
      );
      if (selectedSite) {
        controller.handleSiteChange(selectedSite);
      }
    };

    const selectedController = computed(() => store.controllerKey);
    const selectedSite = computed(() => store.selectedSite);

    const hasControllerDetails = computed(() =>
      Boolean(
        store.controllerName ||
          store.systemType ||
          store.connectionState !== "unknown" ||
          store.sites.length
      )
    );

    const formatSystemType = computed(() => {
      const type = store.systemType;
      if (type === UNIFI.SYSTEM_TYPES.MODERN) {
        return "Modern (Network Application)";
      }
      if (type === UNIFI.SYSTEM_TYPES.LEGACY) {
        return "Legacy (Controller)";
      }
      return type;
    });

    const connectionStatusInfo = computed(() => {
      const state = store.connectionState;

      switch (state) {
        case "connected":
          return {
            show: true,
            text: "Connected",
            class: "status-connected",
          };
        case "waiting":
          return {
            show: true,
            text: "Waiting for site selection",
            class: "status-waiting",
          };
        case "connecting":
          return {
            show: true,
            text: "Connecting...",
            class: "status-connecting",
          };
        case "disconnected":
          return {
            show: true,
            text: "Disconnected",
            class: "status-error",
          };
        default:
          return { show: false };
      }
    });

    onMounted(() => {
      if (!store.isLocked && store.connectionState !== "connected") {
        controller.fetchControllers();
      }
    });

    onBeforeUnmount(() => {
      controller.cleanup();
    });

    return {
      store,
      ...controller,
      selectedController,
      selectedSite,
      hasControllerDetails,
      formatSystemType,
      connectionStatusInfo,
      onControllerChange,
      onSiteChange,
    };
  },
};
</script>

<style lang="scss">
.controller-select {
  display: flex;
  flex-direction: column;
  gap: map.get($spacing, "lg");

  .select-group {
    label {
      display: block;
      font-weight: 500;
      color: map.get($colors, "slate", 700);
      margin-bottom: map.get($spacing, "sm");
    }

    .helper-text {
      display: block;
      margin-top: map.get($spacing, "xs");
      font-size: map.get($font-size, "sm");

      &.text-info {
        color: map.get($colors, "blue", 500);
      }

      &.text-warning {
        color: map.get($colors, "accent", "warning");
      }

      &.text-error {
        color: map.get($colors, "accent", "error");
      }
    }
  }

  .alert-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: map.get($spacing, "sm");
  }

  .controller-summary {
    .summary-content {
      display: grid;
      gap: map.get($spacing, "sm");
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: map.get($spacing, "xs") map.get($spacing, "sm");
      border-radius: 0.25rem;

      &:hover {
        background-color: map.get($colors, "slate", 50);
      }

      dt {
        color: map.get($colors, "slate", 600);
        font-size: map.get($font-size, "sm");
      }

      dd {
        font-weight: 500;
        color: map.get($colors, "slate", 900);
        font-size: map.get($font-size, "sm");

        &.status-connected {
          color: map.get($colors, "accent", "success");
        }

        &.status-waiting {
          color: map.get($colors, "blue", 500);
        }

        &.status-connecting {
          color: map.get($colors, "accent", "warning");
        }

        &.status-error {
          color: map.get($colors, "accent", "error");
        }
      }
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
