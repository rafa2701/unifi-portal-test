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
/* Styles removed for build */
</style>
