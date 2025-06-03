<template>
  <div class="settings-view">
    <div class="settings-content">
      <BaseCard class="settings-card">
        <h3>General Settings</h3>
        <div class="settings-form">
          <div class="form-group">
            <label>Cache Duration</label>
            <BaseSelect
              v-model="settings.cacheDuration"
              :options="cacheOptions"
            />
            <span class="help-text"
              >How long to cache UniFi controller data</span
            >
          </div>

          <div class="form-group">
            <label>Debug Mode</label>
            <div class="toggle-group">
              <label class="toggle">
                <input type="checkbox" v-model="settings.debugMode" />
                <span class="toggle-slider"></span>
              </label>
              <span class="toggle-label">Enable debug logging</span>
            </div>
          </div>
        </div>
      </BaseCard>

      <BaseCard class="settings-card">
        <h3>Access Control</h3>
        <div class="settings-form">
          <div class="form-group">
            <label>Minimum User Role</label>
            <BaseSelect v-model="settings.minRole" :options="roleOptions" />
            <span class="help-text"
              >Minimum role required to access the portal</span
            >
          </div>

          <div class="form-group">
            <label>API Access</label>
            <div class="toggle-group">
              <label class="toggle">
                <input type="checkbox" v-model="settings.apiEnabled" />
                <span class="toggle-slider"></span>
              </label>
              <span class="toggle-label">Enable REST API access</span>
            </div>
          </div>
        </div>
      </BaseCard>

      <div class="settings-actions">
        <BaseButton variant="primary" :loading="saving" @click="saveSettings">
          Save Changes
        </BaseButton>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import BaseCard from "@admin/components/base/BaseCard.vue";
import BaseButton from "@admin/components/base/BaseButton.vue";
import BaseSelect from "@admin/components/base/BaseSelect.vue";

export default {
  name: "SettingsView",

  components: {
    BaseCard,
    BaseButton,
    BaseSelect,
  },

  setup() {
    const saving = ref(false);
    const settings = ref({
      cacheDuration: "1hour",
      debugMode: false,
      minRole: "administrator",
      apiEnabled: true,
    });

    const cacheOptions = [
      { value: "30min", label: "30 Minutes" },
      { value: "1hour", label: "1 Hour" },
      { value: "4hours", label: "4 Hours" },
      { value: "12hours", label: "12 Hours" },
    ];

    const roleOptions = [
      { value: "administrator", label: "Administrator" },
      { value: "editor", label: "Editor" },
      { value: "author", label: "Author" },
    ];

    const saveSettings = async () => {
      saving.value = true;
      try {
        await new Promise((resolve) => setTimeout(resolve, 1000));
        // Save settings logic here
      } finally {
        saving.value = false;
      }
    };

    return {
      saving,
      settings,
      cacheOptions,
      roleOptions,
      saveSettings,
    };
  },
};
</script>

<style lang="scss">
.settings-view {
  padding: map.get($spacing, "xl") 0;

  .settings-content {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "xl");
  }

  .settings-card {
    h3 {
      font-size: map.get($font-size, "xl");
      color: map.get($colors, "slate", 900);
      margin-bottom: map.get($spacing, "xl");
    }
  }

  .settings-form {
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "xl");

    .form-group {
      label {
        display: block;
        font-weight: 500;
        color: map.get($colors, "slate", 700);
        margin-bottom: map.get($spacing, "sm");
      }

      .help-text {
        display: block;
        color: map.get($colors, "slate", 500);
        font-size: map.get($font-size, "sm");
        margin-top: map.get($spacing, "xs");
      }
    }
  }

  .toggle-group {
    display: flex;
    align-items: center;
    gap: map.get($spacing, "md");

    .toggle {
      position: relative;
      display: inline-block;
      width: 3rem;
      height: 1.5rem;

      input {
        opacity: 0;
        width: 0;
        height: 0;

        &:checked + .toggle-slider {
          background-color: map.get($colors, "blue", 500);
        }

        &:checked + .toggle-slider:before {
          transform: translateX(1.5rem);
        }
      }

      .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: map.get($colors, "slate", 300);
        transition: 0.2s;
        border-radius: 1rem;

        &:before {
          position: absolute;
          content: "";
          height: 1.25rem;
          width: 1.25rem;
          left: 0.125rem;
          bottom: 0.125rem;
          background-color: white;
          transition: 0.2s;
          border-radius: 50%;
        }
      }
    }

    .toggle-label {
      color: map.get($colors, "slate", 600);
      font-size: map.get($font-size, "sm");
    }
  }

  .settings-actions {
    display: flex;
    justify-content: flex-end;
    padding-top: map.get($spacing, "xl");
    border-top: 1px solid map.get($colors, "slate", 200);
  }
}
</style>
