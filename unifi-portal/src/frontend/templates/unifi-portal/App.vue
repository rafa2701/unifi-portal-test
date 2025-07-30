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
/* Styles removed for build */
</style>
