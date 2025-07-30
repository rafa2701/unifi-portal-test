<template>
  <div id="unifi-portal">
    <AppLayout />
  </div>
</template>

<script>
import { AppLayout } from "@frontend/components/layout";

/**
 * Root application component
 * Serves as the application container and entry point
 */
export default {
  name: "UnifiPortal",

  components: {
    AppLayout,
  },
};
</script>

<style lang="scss">
#unifi-portal {
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-family: map.get($font-family, "sans") !important;
}
</style>
