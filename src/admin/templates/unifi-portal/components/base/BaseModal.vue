<template>
  <vue-final-modal
    v-model="localShow"
    :classes="['modal-container', `modal-${size}`]"
    content-class="modal-content"
    :overlay-transition="overlayTransition"
    :content-transition="contentTransition"
    overlay-class="modal-overlay"
  >
    <div class="modal-header">
      <div class="header-content">
        <span v-if="icon" class="modal-icon">
          <component :is="icon" />
        </span>
        <h3 class="modal-title">
          <slot name="title">{{ title }}</slot>
        </h3>
      </div>
      <button
        v-if="showClose"
        class="modal-close"
        @click="close"
        aria-label="Close modal"
      >
        <X />
      </button>
    </div>

    <div class="modal-body" :class="{ 'has-footer': $slots.footer }">
      <slot></slot>
    </div>

    <div v-if="$slots.footer" class="modal-footer">
      <slot name="footer">
        <base-button variant="text" @click="close">Cancel</base-button>
        <base-button variant="primary" @click="confirm">Confirm</base-button>
      </slot>
    </div>
  </vue-final-modal>
</template>

<script>
import { VueFinalModal } from "vue-final-modal";
import { X } from "lucide-vue-next";
import BaseButton from "./BaseButton.vue";

/**
 * Base Modal Component
 * A flexible modal dialog component with different sizes and transitions
 */
export default {
  name: "BaseModal",

  components: {
    VueFinalModal,
    BaseButton,
    X,
  },

  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "",
    },
    icon: {
      type: [String, Object],
      default: null,
    },
    size: {
      type: String,
      default: "md",
      validator: (value) => ["sm", "md", "lg", "xl", "full"].includes(value),
    },
    showClose: {
      type: Boolean,
      default: true,
    },
    overlayTransition: {
      type: String,
      default: "vfm-fade",
    },
    contentTransition: {
      type: String,
      default: "vfm-slide-up",
    },
  },

  emits: ["update:modelValue", "close", "confirm"],

  computed: {
    localShow: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  },

  methods: {
    close() {
      this.localShow = false;
      this.$emit("close");
    },
    confirm() {
      this.$emit("confirm");
    },
  },
};
</script>

<style lang="scss">
.modal {
  &-container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999;
  }

  &-overlay {
    backdrop-filter: blur(4px);
    background-color: rgba(map.get($colors, "slate", 900), 0.75);
  }

  &-content {
    position: relative;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 2rem);
    margin: 1rem;
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
      0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  // Modal sizes
  &-sm {
    .modal-content {
      width: 100%;
      max-width: 24rem;
    }
  }

  &-md {
    .modal-content {
      width: 100%;
      max-width: 32rem;
    }
  }

  &-lg {
    .modal-content {
      width: 100%;
      max-width: 48rem;
    }
  }

  &-xl {
    .modal-content {
      width: 100%;
      max-width: 64rem;
    }
  }

  &-full {
    .modal-content {
      width: calc(100% - 2rem);
      height: calc(100vh - 2rem);
      max-width: none;
      max-height: none;
    }
  }

  &-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: map.get($spacing, "lg");
    border-bottom: 1px solid map.get($colors, "slate", 200);

    .header-content {
      display: flex;
      align-items: center;
      gap: map.get($spacing, "sm");
    }

    .modal-icon {
      display: flex;
      align-items: center;
      color: map.get($colors, "blue", 500);

      svg {
        width: 1.5rem;
        height: 1.5rem;
      }
    }
  }

  &-title {
    font-size: map.get($font-size, "xl");
    font-weight: 600;
    color: map.get($colors, "slate", 900);
    margin: 0;
  }

  &-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    padding: 0;
    background: transparent;
    border: none;
    border-radius: 0.375rem;
    color: map.get($colors, "slate", 500);
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover {
      background-color: map.get($colors, "slate", 100);
      color: map.get($colors, "slate", 700);
    }

    svg {
      width: 1.25rem;
      height: 1.25rem;
    }
  }

  &-body {
    flex: 1;
    overflow-y: auto;
    padding: map.get($spacing, "lg");

    &.has-footer {
      padding-bottom: 0;
    }
  }

  &-footer {
    display: flex;
    justify-content: flex-end;
    gap: map.get($spacing, "sm");
    padding: map.get($spacing, "lg");
    border-top: 1px solid map.get($colors, "slate", 200);
    background-color: map.get($colors, "slate", 50);
  }
}

// Transitions
.vfm-fade-enter-active,
.vfm-fade-leave-active {
  transition: opacity 0.2s ease;
}

.vfm-fade-enter-from,
.vfm-fade-leave-to {
  opacity: 0;
}

.vfm-slide-up-enter-active,
.vfm-slide-up-leave-active {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.vfm-slide-up-enter-from,
.vfm-slide-up-leave-to {
  transform: translateY(10px);
  opacity: 0;
}
</style>
