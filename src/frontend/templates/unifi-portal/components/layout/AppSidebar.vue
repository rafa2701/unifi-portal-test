<template>
  <aside class="app-sidebar">
    <div class="sidebar-content">
      <slot name="progress"></slot>
    </div>

    <div v-if="isControllerSelected" class="sidebar-footer">
      <BaseButton
        variant="text"
        size="sm"
        :icon="LogOut"
        class="disconnect-button"
        :loading="disconnecting"
        :disabled="isDisconnecting"
        @click="handleDisconnect"
      >
        {{ disconnectButtonText }}
      </BaseButton>
    </div>
  </aside>
</template>

<script>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useControllerStore } from "@frontend/stores/controller";
import { useController } from "@frontend/composables/features/controller/useController";
import { LogOut } from "lucide-vue-next";
import { createUnifiApi } from "@frontend/services/api/unifi";
import { BaseButton } from "@frontend/components/base";

export default {
  name: "AppSidebar",

  components: {
    BaseButton,
    LogOut,
  },

  setup() {
    const router = useRouter();
    const store = useControllerStore();
    const controller = useController();
    const disconnecting = ref(false);

    const isControllerSelected = computed(() => store.isControllerSelected);
    const isDisconnecting = computed(
      () =>
        store.connectionState === "disconnected" ||
        disconnecting.value ||
        store.connectionState === "connecting"
    );

    const disconnectButtonText = computed(() =>
      disconnecting.value ? "Disconnecting..." : "Disconnect Controller"
    );

    const handleDisconnect = async () => {
      try {
        disconnecting.value = true;
        controller.fullCleanup();
        store.unlockSelection();
        store.setConnectionState("disconnected");

        const abortController = new AbortController();
        const tempApi = createUnifiApi(abortController.signal);

        await tempApi.controllers.resetConnection(store.controllerKey);
        store.clearController();
        router.push({ name: "controller" });
      } catch (error) {
        if (error.name !== "AbortError") {
          console.error("Failed to reset connection:", error);
          store.setConnectionState("disconnected");
        }
      } finally {
        disconnecting.value = false;
      }
    };

    return {
      isControllerSelected,
      isDisconnecting,
      disconnecting,
      disconnectButtonText,
      handleDisconnect,
      LogOut,
    };
  },
};
</script>

<style lang="scss">
.app-sidebar {
  height: 100%;
  display: flex;
  flex-direction: column;

  .sidebar-content {
    flex: 1;
    padding: map.get($spacing, "lg");
  }

  .sidebar-footer {
    padding: map.get($spacing, "md");
    border-top: 1px solid map.get($colors, "slate", 200);

    .disconnect-button {
      width: 100%;
      justify-content: center;
    }
  }
}
</style>
