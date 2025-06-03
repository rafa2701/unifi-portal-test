<template>
  <div class="app-layout">
    <AppHeader />

    <main class="layout-main">
      <div class="layout-content">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </main>
  </div>
</template>

<script>
import { AppHeader } from "@frontend/components/layout";

/**
 * Main layout component that provides the application structure
 */
export default {
  name: "AppLayout",

  components: {
    AppHeader,
  },
};
</script>

<style lang="scss">
.app-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;

  .layout-main {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    background: map.get($colors, "slate", 50);
  }

  .layout-content {
    flex: 1;
    padding: map.get($spacing, "lg");
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
