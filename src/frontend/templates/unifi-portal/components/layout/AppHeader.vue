<template>
  <header class="app-header">
    <div class="header-logo" role="button" @click="navigateHome">
      <img :src="$img('icons/unifi-logo.svg')" alt="UniFi Logo" />
    </div>
    <div v-if="hasControllerSelection" class="header-info">
      <div class="info-item">
        <span class="info-label">Controller:</span>
        <span class="info-value">{{ controllerName }}</span>
      </div>
      <div class="info-item">
        <span class="info-label">Site:</span>
        <span class="info-value">{{ selectedSite }}</span>
      </div>
    </div>
  </header>
</template>

<script>
import { computed } from "vue";
import { useRouter } from "vue-router";
import { useControllerStore } from "@frontend/stores/controller";
import { UNIFI } from "@frontend/constants";

export default {
  name: "AppHeader",

  setup() {
    const store = useControllerStore();
    const router = useRouter();

    const navigateHome = () => {
      router.push({ name: UNIFI.ROUTES.PORTAL });
    };

    const hasControllerSelection = computed(() => {
      return store.selectedController && store.selectedSite;
    });

    const controllerName = computed(() => {
      return store.selectedController?.name || "";
    });

    const selectedSite = computed(() => {
      return store.selectedSite || "";
    });

    return {
      hasControllerSelection,
      controllerName,
      selectedSite,
      navigateHome,
    };
  },
};
</script>

<style lang="scss">
.app-header {
  display: flex;
  align-items: center;
  height: 4rem;
  padding: 0 map.get($spacing, "lg");
  background: white;
  border-bottom: 1px solid map.get($colors, "slate", 200);

  .header-logo {
    cursor: pointer;
    transition: opacity 0.2s ease;

    &:hover {
      opacity: 0.8;
    }

    img {
      height: 2rem;
      width: auto;
    }
  }

  .header-info {
    display: flex;
    align-items: center;
    gap: map.get($spacing, "lg");
    margin-left: map.get($spacing, "xl");

    .info-item {
      display: flex;
      align-items: center;
      gap: map.get($spacing, "xs");

      .info-label {
        font-size: map.get($font-size, "sm");
        color: map.get($colors, "slate", 500);
      }

      .info-value {
        display: flex;
        align-items: center;
        gap: map.get($spacing, "xs");
        font-size: map.get($font-size, "sm");
        font-weight: 500;
        color: map.get($colors, "slate", 900);
      }
    }
  }
}
</style>
