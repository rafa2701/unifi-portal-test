<template>
  <div class="base-tabs">
    <div class="tabs-header" role="tablist">
      <button
        v-for="(tab, index) in tabs"
        :key="tab.id || index"
        class="tab-button"
        :class="{
          'tab-active': isTabActive(tab),
          'tab-disabled': tab.disabled,
          [`tab-${variant}`]: true,
        }"
        role="tab"
        :aria-selected="isTabActive(tab)"
        :aria-controls="`panel-${tab.id || index}`"
        :disabled="tab.disabled"
        @click="selectTab(tab)"
      >
        <span v-if="tab.icon" class="tab-icon">
          <component :is="tab.icon" />
        </span>
        <span class="tab-text">{{ tab.title }}</span>
        <span
          v-if="tab.badge"
          class="tab-badge"
          :class="`badge-${tab.badge.type || 'default'}`"
        >
          {{ tab.badge.content }}
        </span>
      </button>
    </div>

    <div class="tabs-content">
      <div
        v-for="(tab, index) in tabs"
        :key="tab.id || index"
        :id="`panel-${tab.id || index}`"
        class="tab-panel"
        role="tabpanel"
        :aria-labelledby="`tab-${tab.id || index}`"
        :class="{ 'panel-active': isTabActive(tab) }"
      >
        <slot :name="tab.id || index" v-if="isTabActive(tab)">
          {{ tab.content }}
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * Base Tab Component
 * A versatile tab component supporting icons, badges, and different styles
 */
export default {
  name: "BaseTab",

  props: {
    modelValue: {
      type: [String, Number],
      default: null,
    },
    tabs: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every(
          (tab) =>
            typeof tab.title === "string" &&
            (!tab.id ||
              typeof tab.id === "string" ||
              typeof tab.id === "number")
        );
      },
    },
    variant: {
      type: String,
      default: "default",
      validator: (value) => ["default", "pills", "underline"].includes(value),
    },
  },

  emits: ["update:modelValue", "tab-change"],

  data() {
    return {
      activeTab: null,
    };
  },

  watch: {
    modelValue: {
      immediate: true,
      handler(value) {
        if (value !== null) {
          this.activeTab = value;
        } else if (this.tabs.length > 0) {
          this.activeTab = this.tabs[0].id || 0;
          this.$emit("update:modelValue", this.activeTab);
        }
      },
    },
  },

  methods: {
    isTabActive(tab) {
      return this.activeTab === (tab.id || this.tabs.indexOf(tab));
    },

    selectTab(tab) {
      if (tab.disabled) return;

      const tabId = tab.id || this.tabs.indexOf(tab);
      this.activeTab = tabId;
      this.$emit("update:modelValue", tabId);
      this.$emit("tab-change", tab);
    },
  },
};
</script>

<style lang="scss">
.base-tabs {
  width: 100%;

  .tabs-header {
    display: flex;
    border-bottom: 1px solid map.get($colors, "slate", 200);
    margin-bottom: map.get($spacing, "md");
    gap: map.get($spacing, "xs");
  }

  .tab-button {
    display: inline-flex;
    align-items: center;
    gap: map.get($spacing, "xs");
    padding: map.get($spacing, "sm") map.get($spacing, "md");
    font-size: map.get($font-size, "sm");
    font-weight: 500;
    color: map.get($colors, "slate", 600);
    background: transparent;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover:not(:disabled) {
      color: map.get($colors, "slate", 900);
      background: map.get($colors, "slate", 50);
    }

    &:focus {
      outline: none;
      box-shadow: 0 0 0 2px map.get($colors, "blue", 100);
    }

    &.tab-active {
      color: map.get($colors, "blue", 600);
      background: map.get($colors, "blue", 50);

      &::after {
        opacity: 1;
      }
    }

    &.tab-disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .tab-icon {
      display: flex;
      align-items: center;

      svg {
        width: 1rem;
        height: 1rem;
      }
    }

    .tab-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 1.25rem;
      height: 1.25rem;
      padding: 0 0.375rem;
      font-size: map.get($font-size, "xs");
      font-weight: 500;
      border-radius: 1rem;
      background: map.get($colors, "slate", 100);
      color: map.get($colors, "slate", 700);

      &.badge-success {
        background: map.get($colors, "accent", "success");
        color: white;
      }

      &.badge-warning {
        background: map.get($colors, "accent", "warning");
        color: white;
      }

      &.badge-error {
        background: map.get($colors, "accent", "error");
        color: white;
      }

      &.badge-info {
        background: map.get($colors, "accent", "info");
        color: white;
      }
    }
  }

  // Tab variants
  .tab-pills {
    border-radius: 0.375rem;

    &.tab-active {
      background: map.get($colors, "blue", 500);
      color: white;
    }
  }

  .tab-underline {
    border-radius: 0;
    position: relative;

    &::after {
      content: "";
      position: absolute;
      bottom: -1px;
      left: 0;
      width: 100%;
      height: 2px;
      background: map.get($colors, "blue", 500);
      opacity: 0;
      transition: opacity 0.2s ease;
    }

    &.tab-active {
      background: transparent;

      &::after {
        opacity: 1;
      }
    }
  }

  .tabs-content {
    position: relative;
  }

  .tab-panel {
    display: none;

    &.panel-active {
      display: block;
      animation: fadeIn 0.2s ease;
    }
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
