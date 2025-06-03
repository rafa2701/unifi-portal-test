<template>
  <button
    :type="type"
    :class="[
      'base-button',
      `button-${variant}`,
      `button-${size}`,
      {
        'button-loading': loading,
        'button-icon-only': iconOnly,
        'button-with-icon': !!icon && !iconOnly,
      },
    ]"
    :disabled="disabled || loading"
    @click="$emit('click', $event)"
  >
    <span v-if="loading" class="button-spinner">
      <img :src="$img('icons/ajax_spinner.svg')" alt="Loading" />
    </span>
    <span v-else-if="icon" class="button-icon">
      <component :is="icon" />
    </span>
    <span v-if="!iconOnly" class="button-content">
      <slot></slot>
    </span>
  </button>
</template>

<script>
import {
  ArrowRight,
  ArrowLeft,
  Plus,
  Trash,
  Edit,
  Download,
  Upload,
  Check,
  X,
} from "lucide-vue-next";

/**
 * Base Button Component
 * A versatile button component with multiple variants, sizes, and icon support
 */
export default {
  name: "BaseButton",

  components: {
    ArrowRight,
    ArrowLeft,
    Plus,
    Trash,
    Edit,
    Download,
    Upload,
    Check,
    X,
  },

  props: {
    type: {
      type: String,
      default: "button",
    },
    variant: {
      type: String,
      default: "primary",
      validator: (value) =>
        [
          "primary",
          "secondary",
          "outline",
          "text",
          "danger",
          "success",
        ].includes(value),
    },
    size: {
      type: String,
      default: "md",
      validator: (value) => ["sm", "md", "lg"].includes(value),
    },
    icon: {
      type: [String, Object],
      default: null,
    },
    iconOnly: {
      type: Boolean,
      default: false,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["click"],
};
</script>

<style lang="scss">
.base-button {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
  cursor: pointer;
  white-space: nowrap;

  &:focus {
    outline: none;
    box-shadow: 0 0 0 2px white, 0 0 0 4px map.get($colors, "blue", 500);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  // Variants
  &.button-primary {
    background-color: map.get($colors, "blue", 500);
    color: white;
    border: none;

    &:hover:not(:disabled) {
      background-color: map.get($colors, "blue", 600);
    }

    &:active:not(:disabled) {
      background-color: map.get($colors, "blue", 700);
    }
  }

  &.button-secondary {
    background-color: map.get($colors, "slate", 200);
    color: map.get($colors, "slate", 800);
    border: none;

    &:hover:not(:disabled) {
      background-color: map.get($colors, "slate", 300);
    }

    &:active:not(:disabled) {
      background-color: map.get($colors, "slate", 400);
    }
  }

  &.button-outline {
    background-color: transparent;
    border: 1px solid map.get($colors, "blue", 500);
    color: map.get($colors, "blue", 500);

    &:hover:not(:disabled) {
      background-color: map.get($colors, "blue", 50);
    }

    &:active:not(:disabled) {
      background-color: map.get($colors, "blue", 100);
    }
  }

  &.button-text {
    background-color: transparent;
    border: none;
    color: map.get($colors, "blue", 500);

    &:hover:not(:disabled) {
      background-color: map.get($colors, "blue", 50);
    }

    &:active:not(:disabled) {
      background-color: map.get($colors, "blue", 100);
    }
  }

  &.button-danger {
    background-color: map.get($colors, "accent", "error");
    color: white;
    border: none;

    &:hover:not(:disabled) {
      filter: brightness(90%);
    }

    &:active:not(:disabled) {
      filter: brightness(85%);
    }
  }

  &.button-success {
    background-color: map.get($colors, "accent", "success");
    color: white;
    border: none;

    &:hover:not(:disabled) {
      filter: brightness(90%);
    }

    &:active:not(:disabled) {
      filter: brightness(85%);
    }
  }

  // Sizes
  &.button-sm {
    height: 2rem;
    padding: 0 0.75rem;
    font-size: map.get($font-size, "sm");

    &.button-with-icon {
      gap: 0.375rem;
    }

    .button-icon {
      width: 1rem;
      height: 1rem;
    }
  }

  &.button-md {
    height: 2.5rem;
    padding: 0 1rem;
    font-size: map.get($font-size, "base");

    &.button-with-icon {
      gap: 0.5rem;
    }

    .button-icon {
      width: 1.25rem;
      height: 1.25rem;
    }
  }

  &.button-lg {
    height: 3rem;
    padding: 0 1.5rem;
    font-size: map.get($font-size, "lg");

    &.button-with-icon {
      gap: 0.625rem;
    }

    .button-icon {
      width: 1.5rem;
      height: 1.5rem;
    }
  }

  // Icon styles
  &.button-icon-only {
    padding: 0;
    aspect-ratio: 1;

    &.button-sm {
      width: 2rem;
    }

    &.button-md {
      width: 2.5rem;
    }

    &.button-lg {
      width: 3rem;
    }
  }

  .button-icon {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  // Loading state
  &.button-loading {
    cursor: wait;

    .button-spinner {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);

      img {
        width: 1.25rem;
        height: 1.25rem;
        animation: spin 1s linear infinite;
      }
    }

    .button-content,
    .button-icon {
      opacity: 0;
    }
  }
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
