<template>
  <div class="base-listbox">
    <Listbox
      v-model="localValue"
      :disabled="disabled"
      as="div"
      class="listbox-container"
    >
      <div class="relative">
        <ListboxButton
          class="listbox-button"
          :class="{ 'button-error': error, 'button-disabled': disabled }"
        >
          <span class="button-text">
            {{ selectedLabel }}
          </span>
          <span class="button-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon"
            >
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </span>
        </ListboxButton>

        <transition
          leave-active-class="transition duration-100 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <ListboxOptions class="listbox-options">
            <ListboxOption
              v-for="option in options"
              :key="option.value"
              :value="option.value"
              v-slot="{ active, selected }"
              as="template"
            >
              <li
                :class="[
                  'option-item',
                  active ? 'option-active' : '',
                  selected ? 'option-selected' : '',
                ]"
              >
                <span class="option-text">{{ option.label }}</span>
                <span v-if="selected" class="option-check">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon"
                  >
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                </span>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
      </div>
    </Listbox>

    <span v-if="error" class="error-message">{{ error }}</span>
  </div>
</template>

<script>
import { computed } from "vue";
import {
  Listbox,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from "@headlessui/vue";

/**
 * Enhanced Listbox Component
 * A modern select component built with HeadlessUI
 */
export default {
  name: "BaseListbox",

  components: {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
  },

  props: {
    modelValue: {
      type: [String, Number, Object],
      default: null,
    },
    options: {
      type: Array,
      default: () => [],
      validator: (value) =>
        value.every((option) => "value" in option && "label" in option),
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    error: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Select an option",
    },
  },

  emits: ["update:modelValue", "change"],

  setup(props, { emit }) {
    const localValue = computed({
      get: () => props.modelValue,
      set: (value) => {
        emit("update:modelValue", value);
        emit("change", value);
      },
    });

    const selectedLabel = computed(() => {
      if (!props.modelValue) return props.placeholder;
      const selected = props.options.find(
        (option) => option.value === props.modelValue
      );
      return selected ? selected.label : props.placeholder;
    });

    return {
      localValue,
      selectedLabel,
    };
  },
};
</script>

<style lang="scss">
.base-listbox {
  position: relative;
  width: 100%;

  .listbox-container {
    width: 100%;
  }

  .listbox-button {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.625rem 1rem;
    background: white;
    border: 1px solid map.get($colors, "slate", 200);
    border-radius: 0.375rem;
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;

    &:hover:not(.button-disabled) {
      border-color: map.get($colors, "slate", 300);
    }

    &:focus {
      border-color: map.get($colors, "blue", 500);
      box-shadow: 0 0 0 1px map.get($colors, "blue", 500);
    }

    &.button-error {
      border-color: map.get($colors, "accent", "error");
    }

    &.button-disabled {
      background-color: map.get($colors, "slate", 100);
      cursor: not-allowed;
    }

    .button-text {
      display: block;
      flex: 1;
      text-align: left;
      color: map.get($colors, "slate", 700);
      font-size: map.get($font-size, "base");
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .button-icon {
      flex-shrink: 0;
      margin-left: 0.5rem;
      color: map.get($colors, "slate", 400);

      .icon {
        width: 1.25rem;
        height: 1.25rem;
      }
    }
  }

  .listbox-options {
    position: absolute;
    z-index: 10;
    margin-top: 0.25rem;
    width: 100%;
    background: white;
    border: 1px solid map.get($colors, "slate", 200);
    border-radius: 0.375rem;
    padding: 0.25rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
      0 2px 4px -1px rgba(0, 0, 0, 0.06);
    max-height: 15rem;
    overflow-y: auto;

    .option-item {
      position: relative;
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      cursor: pointer;
      user-select: none;
      border-radius: 0.25rem;

      &.option-active {
        background-color: map.get($colors, "blue", 50);
      }

      &.option-selected {
        background-color: map.get($colors, "blue", 500);
        color: white;

        .option-check {
          color: white;
        }
      }

      .option-text {
        flex: 1;
      }

      .option-check {
        flex-shrink: 0;
        color: map.get($colors, "blue", 500);
        margin-left: 0.75rem;

        .icon {
          width: 1.25rem;
          height: 1.25rem;
        }
      }
    }
  }

  .error-message {
    display: block;
    margin-top: 0.25rem;
    color: map.get($colors, "accent", "error");
    font-size: map.get($font-size, "sm");
  }
}
</style>
