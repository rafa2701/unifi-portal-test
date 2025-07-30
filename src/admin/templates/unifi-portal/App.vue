<template>
  <div class="unifi-portal">
    <!-- <Breadcrumbs :breadcrumbs="breadcrumbs" /> -->
    <div class="portal-layout">
      <!-- <aside class="portal-sidebar">
        <ProgressStepper :steps="steps" :current-step="currentStepIndex" />
      </aside> -->
      <main class="portal-main">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<script>
import { useProgress } from "@admin/composables/useProgress";

/**
 * Root component for the Unifi Portal application
 */
export default {
  name: "UnifiPortal",

  setup() {
    const { steps, currentStepIndex, breadcrumbs } = useProgress();

    return {
      steps,
      currentStepIndex,
      breadcrumbs,
    };
  },
};
</script>

<style lang="scss">
.unifi-portal {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.portal-layout {
  flex: 1;
  display: flex;

  .portal-sidebar {
    width: 20rem;
    border-right: 1px solid map.get($colors, "slate", 200);
    background: white;
  }

  .portal-main {
    flex: 1;
    padding: map.get($spacing, "lg");
    background: map.get($colors, "slate", 50);
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
