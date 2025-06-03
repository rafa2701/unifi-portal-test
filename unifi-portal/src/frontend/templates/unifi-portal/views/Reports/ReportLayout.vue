<template>
  <div class="report-layout">
    <div v-if="isLoading" class="report-loading">
      <img
        :src="$img('icons/ajax_spinner.svg')"
        alt="Loading"
        class="loading-spinner"
      />
      <p>Loading report data...</p>
    </div>
    <div v-else>
      <router-view />
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useControllerStore } from "@frontend/stores/controller";
import { UNIFI } from "@frontend/constants";

export default {
  name: "ReportLayout",

  setup() {
    const router = useRouter();
    const route = useRoute();
    const store = useControllerStore();
    const isLoading = ref(true);

    const controllerSystemType = computed(() => {
      return store.selectedController?.system || "";
    });

    const redirectToCorrectReport = () => {
      if (!store.selectedController || !store.selectedSite) {
        router.push({ name: UNIFI.ROUTES.PORTAL });
        return;
      }

      const targetRoute =
        controllerSystemType.value === UNIFI.SYSTEM_TYPES.MODERN
          ? UNIFI.ROUTES.REPORTS_MODERN
          : UNIFI.ROUTES.REPORTS_LEGACY;

      if (route.name !== targetRoute) {
        router.push({ name: targetRoute });
      }

      isLoading.value = false;
    };

    onMounted(() => {
      redirectToCorrectReport();
    });

    watch(
      () => store.selectedController,
      (newValue) => {
        if (newValue) {
          redirectToCorrectReport();
        } else {
          router.push({ name: UNIFI.ROUTES.PORTAL });
        }
      }
    );

    return {
      isLoading,
    };
  },
};
</script>

<style lang="scss">
.report-layout {
  min-height: 100vh;

  .report-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: map.get($spacing, "xl");
    margin-top: map.get($spacing, "xl");

    .loading-spinner {
      width: 48px;
      height: 48px;
      margin-bottom: map.get($spacing, "md");
      animation: spin 1s linear infinite;
    }

    p {
      font-size: map.get($font-size, "lg");
      color: map.get($colors, "slate", 600);
    }
  }
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
