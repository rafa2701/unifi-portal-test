<template>
  <div
    v-if="show"
    class="base-alert"
    :class="[`alert-${type}`, { 'alert-dismissible': dismissible }]"
    role="alert"
  >
    <div class="alert-icon">
      <component :is="alertIcon" />
    </div>

    <div class="alert-content">
      <div v-if="title" class="alert-title">{{ title }}</div>
      <div class="alert-message">
        <slot></slot>
      </div>
    </div>

    <button
      v-if="dismissible"
      class="alert-dismiss"
      @click="handleDismiss"
      aria-label="Dismiss alert"
    >
      <X />
    </button>
  </div>
</template>

<script>
import { computed } from "vue";
import {
  AlertCircle,
  AlertTriangle,
  CheckCircle,
  Info,
  X,
} from "lucide-vue-next";

/**
 * Base Alert Component
 * A versatile alert component with different types and optional dismiss button
 */
export default {
  name: "BaseAlert",

  components: {
    AlertCircle,
    AlertTriangle,
    CheckCircle,
    Info,
    X,
  },

  props: {
    type: {
      type: String,
      default: "info",
      validator: (value) =>
        ["info", "success", "warning", "error"].includes(value),
    },
    title: {
      type: String,
      default: "",
    },
    show: {
      type: Boolean,
      default: true,
    },
    dismissible: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["dismiss", "update:show"],

  setup(props, { emit }) {
    const alertIcon = computed(() => {
      switch (props.type) {
        case "success":
          return CheckCircle;
        case "warning":
          return AlertTriangle;
        case "error":
          return AlertCircle;
        default:
          return Info;
      }
    });

    const handleDismiss = () => {
      emit("update:show", false);
      emit("dismiss");
    };

    return {
      alertIcon,
      handleDismiss,
    };
  },
};
</script>

<style lang="scss">
.base-alert {
  position: relative;
  display: flex;
  padding: map.get($spacing, "md");
  border-radius: 0.5rem;
  margin-bottom: map.get($spacing, "md");
  gap: map.get($spacing, "md");

  // Alert types
  &.alert-info {
    background-color: rgba(map.get($colors, "accent", "info"), 0.1);
    border: 1px solid rgba(map.get($colors, "accent", "info"), 0.2);

    .alert-icon {
      color: map.get($colors, "accent", "info");
    }

    .alert-title {
      color: map.get($colors, "accent", "info");
    }
  }

  &.alert-success {
    background-color: rgba(map.get($colors, "accent", "success"), 0.1);
    border: 1px solid rgba(map.get($colors, "accent", "success"), 0.2);

    .alert-icon {
      color: map.get($colors, "accent", "success");
    }

    .alert-title {
      color: map.get($colors, "accent", "success");
    }
  }

  &.alert-warning {
    background-color: rgba(map.get($colors, "accent", "warning"), 0.1);
    border: 1px solid rgba(map.get($colors, "accent", "warning"), 0.2);

    .alert-icon {
      color: map.get($colors, "accent", "warning");
    }

    .alert-title {
      color: map.get($colors, "accent", "warning");
    }
  }

  &.alert-error {
    background-color: rgba(map.get($colors, "accent", "error"), 0.1);
    border: 1px solid rgba(map.get($colors, "accent", "error"), 0.2);

    .alert-icon {
      color: map.get($colors, "accent", "error");
    }

    .alert-title {
      color: map.get($colors, "accent", "error");
    }
  }

  &.alert-dismissible {
    padding-right: 3rem;
  }

  .alert-icon {
    flex-shrink: 0;
    display: flex;
    align-items: flex-start;

    svg {
      width: 1.25rem;
      height: 1.25rem;
    }
  }

  .alert-content {
    flex: 1;
    min-width: 0;

    .alert-title {
      font-weight: 600;
      font-size: map.get($font-size, "base");
      margin-bottom: map.get($spacing, "xs");
    }

    .alert-message {
      color: map.get($colors, "slate", 700);
      font-size: map.get($font-size, "sm");
      line-height: 1.5;
    }
  }

  .alert-dismiss {
    position: absolute;
    top: map.get($spacing, "sm");
    right: map.get($spacing, "sm");
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.5rem;
    height: 1.5rem;
    padding: 0;
    background: transparent;
    border: none;
    border-radius: 0.25rem;
    color: map.get($colors, "slate", 500);
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover {
      background-color: rgba(0, 0, 0, 0.05);
      color: map.get($colors, "slate", 700);
    }

    svg {
      width: 1rem;
      height: 1rem;
    }
  }
}
</style>
