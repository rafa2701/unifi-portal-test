<template>
  <div class="unifi-home">
    <section class="welcome-section">
      <img
        :src="$img('icons/unifi-logo.svg')"
        alt="UniFi Logo"
        class="welcome-logo"
      />
      <h1>Welcome to UniFi Portal</h1>
      <p class="welcome-subtitle">
        Your central hub for fetching network reports.
      </p>
    </section>

    <section class="selection-section">
      <BaseAlert
        v-if="alert.show"
        :type="alert.type"
        :title="alert.title"
        :show="alert.show"
        dismissible
        @dismiss="clearAlert"
      >
        {{ alert.message }}
      </BaseAlert>

      <BaseCard>
        <div class="selection-form">
          <BaseSelect
            v-model="selectedController"
            :options="controllerOptions"
            label="Controller"
            placeholder="Select a controller"
            :disabled="isLoading"
            :loading="isLoading"
            :error="controllerError"
            @change="handleControllerChange"
          />

          <BaseSelect
            v-model="selectedSite"
            :options="siteOptions"
            label="Site"
            placeholder="Select a site"
            :disabled="!selectedController || sitesLoading"
            :loading="sitesLoading"
            :error="siteError"
          />
        </div>
      </BaseCard>
    </section>

    <section class="action-section">
      <BaseButton
        variant="primary"
        size="lg"
        :disabled="!canViewReports"
        @click="handleViewButtonClick"
      >
        View Reports
      </BaseButton>
    </section>
  </div>
</template>

<script>
import {
  BaseAlert,
  BaseButton,
  BaseCard,
  BaseSelect,
} from "@frontend/components/base";
import useUnifiHome from "./UnifiHome";

export default {
  name: "UnifiHome",

  components: {
    BaseAlert,
    BaseButton,
    BaseCard,
    BaseSelect,
  },

  setup() {
    return useUnifiHome();
  },
};
</script>

<style lang="scss">
@forward "./UnifiHome.scss";
</style>
