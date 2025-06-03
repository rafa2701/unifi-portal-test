<template>
  <div class="base-select" :class="{ 'is-disabled': disabled }">
    <label v-if="label" class="select-label" :for="id">
      {{ label }}
      <span v-if="required" class="required-mark">*</span>
    </label>

    <div class="select-wrapper" :class="{ 'has-error': error }">
      <select
        :id="id"
        ref="selectRef"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        :placeholder="placeholder"
      >
        <option value="">{{ placeholder }}</option>
        <template v-if="isGrouped">
          <optgroup
            v-for="group in processedOptions"
            :key="group.label"
            :label="group.label"
          >
            <option
              v-for="option in group.options"
              :key="option.value"
              :value="option.value"
            >
              {{ option.label }}
            </option>
          </optgroup>
        </template>
        <template v-else>
          <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </template>
      </select>
    </div>

    <div v-if="description" class="select-description">{{ description }}</div>
    <div v-if="error" class="select-error">{{ error }}</div>
  </div>
</template>

<script>
import {
  ref,
  computed,
  onMounted,
  onBeforeUnmount,
  watch,
  nextTick,
} from "vue";
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

/**
 * Base Select Component
 * A custom select component with TomSelect integration
 */
export default {
  name: "BaseSelect",

  props: {
    id: {
      type: String,
      default() {
        return `select-${Math.random().toString(36).substr(2, 9)}`;
      },
    },
    modelValue: {
      type: [String, Number, Object],
      default: null,
    },
    options: {
      type: Array,
      required: true,
      validator: (value) =>
        value.every((option) => "value" in option && "label" in option),
    },
    groupBy: {
      type: [String, Function],
      default: null,
    },
    label: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Select an option",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    required: {
      type: Boolean,
      default: false,
    },
    searchable: {
      type: Boolean,
      default: true,
    },
    clearable: {
      type: Boolean,
      default: true,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    error: {
      type: String,
      default: "",
    },
  },

  emits: ["update:modelValue", "change", "clear"],

  setup(props, { emit }) {
    const selectRef = ref(null);
    const tomSelect = ref(null);
    const isGrouped = computed(() => !!props.groupBy);

    const processedOptions = computed(() => {
      if (!props.groupBy) return props.options;

      const groups = {};
      props.options.forEach((option) => {
        const groupName =
          typeof props.groupBy === "function"
            ? props.groupBy(option)
            : option[props.groupBy];

        if (!groups[groupName]) {
          groups[groupName] = {
            label: groupName,
            options: [],
          };
        }
        groups[groupName].options.push(option);
      });

      return Object.values(groups);
    });

    const initializeTomSelect = () => {
      if (tomSelect.value) {
        tomSelect.value.destroy();
      }

      const config = {
        valueField: "value",
        labelField: "label",
        searchField: ["label"],
        options: props.options,
        items: props.modelValue ? [props.modelValue] : [],
        placeholder: props.placeholder,
        disabled: props.disabled,
        persist: false,
        create: false,
        allowEmptyOption: true,
        plugins: props.clearable ? ["clear_button"] : [],
        render: {
          option: (data) => {
            return `<div class="option-item">
              <span class="option-label">${data.label}</span>
            </div>`;
          },
          item: (data) => {
            return `<div class="item-content">${data.label}</div>`;
          },
          no_results: () => {
            return '<div class="no-results">No results found</div>';
          },
          loading: () => {
            return `<div class="loading-spinner">
              <img src="${props.$img(
                "icons/ajax_spinner.svg"
              )}" alt="Loading" />
            </div>`;
          },
        },
        onChange: (value) => {
          emit("update:modelValue", value);
          emit("change", value);
        },
        onClear: () => {
          emit("update:modelValue", null);
          emit("clear");
        },
      };

      if (!props.searchable) {
        config.controlInput =
          '<input type="text" autocomplete="off" size="1" />';
      }

      tomSelect.value = new TomSelect(selectRef.value, config);
    };

    // Watchers
    watch(
      () => props.options,
      () => {
        if (tomSelect.value) {
          tomSelect.value.clearOptions();
          tomSelect.value.addOptions(props.options);

          if (props.modelValue) {
            tomSelect.value.setValue(props.modelValue);
          }
        }
      },
      { deep: true }
    );

    watch(
      () => props.modelValue,
      (value) => {
        if (tomSelect.value && tomSelect.value.getValue() !== value) {
          tomSelect.value.setValue(value || "");
        }
      }
    );

    watch(
      () => props.loading,
      (value) => {
        if (tomSelect.value) {
          tomSelect.value.loading = value;
        }
      }
    );

    watch(
      () => props.disabled,
      (value) => {
        if (tomSelect.value) {
          value ? tomSelect.value.disable() : tomSelect.value.enable();
        }
      }
    );

    // Lifecycle
    onMounted(() => {
      nextTick(() => {
        initializeTomSelect();
      });
    });

    onBeforeUnmount(() => {
      if (tomSelect.value) {
        tomSelect.value.destroy();
      }
    });

    return {
      selectRef,
      isGrouped,
      processedOptions,
    };
  },
};
</script>

<style lang="scss">
.base-select {
  width: 100%;

  .select-label {
    display: block;
    margin-bottom: map.get($spacing, "xs");
    font-weight: 500;
    color: map.get($colors, "slate", 700);

    .required-mark {
      color: map.get($colors, "accent", "error");
      margin-left: map.get($spacing, "xs");
    }
  }

  .select-wrapper {
    position: relative;
    width: 100%;

    &.has-error {
      .ts-control {
        border-color: map.get($colors, "accent", "error");

        &:hover {
          border-color: map.get($colors, "accent", "error");
        }

        &:focus-within {
          border-color: map.get($colors, "accent", "error");
          box-shadow: 0 0 0 1px map.get($colors, "accent", "error");
        }
      }
    }
  }

  .select-description {
    margin-top: map.get($spacing, "xs");
    font-size: map.get($font-size, "sm");
    color: map.get($colors, "slate", 500);
  }

  .select-error {
    margin-top: map.get($spacing, "xs");
    font-size: map.get($font-size, "sm");
    color: map.get($colors, "accent", "error");
  }

  // TomSelect Custom Styles
  .ts-wrapper {
    .ts-control {
      padding: 0.5rem 0.75rem;
      border: 1px solid map.get($colors, "slate", 200);
      border-radius: 0.375rem;
      min-height: 2.5rem;
      background-color: white;
      transition: all 0.2s ease;

      &:hover:not(.disabled) {
        border-color: map.get($colors, "slate", 300);
      }

      &.focus {
        border-color: map.get($colors, "blue", 500);
        box-shadow: 0 0 0 1px map.get($colors, "blue", 500);
      }

      input {
        color: map.get($colors, "slate", 900);
        min-height: 1.5rem;

        &::placeholder {
          color: map.get($colors, "slate", 400);
        }
      }
    }

    .ts-dropdown {
      margin-top: 0.25rem;
      border: 1px solid map.get($colors, "slate", 200);
      border-radius: 0.375rem;
      padding: 0.25rem;
      background: white;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);

      .optgroup-header {
        padding: 0.5rem;
        color: map.get($colors, "slate", 500);
        font-size: map.get($font-size, "sm");
        font-weight: 600;
        text-transform: uppercase;
        background: map.get($colors, "slate", 50);
      }

      .option {
        padding: 0.5rem 0.75rem;
        cursor: pointer;

        &.active {
          background-color: map.get($colors, "blue", 50);
          color: map.get($colors, "blue", 700);
        }

        &.selected {
          background-color: map.get($colors, "blue", 500);
          color: white;
        }

        .option-item {
          display: flex;
          align-items: center;
        }
      }

      .no-results {
        padding: 0.75rem;
        text-align: center;
        color: map.get($colors, "slate", 500);
        font-size: map.get($font-size, "sm");
      }
    }

    // Clear button
    .clear-button {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 1.25rem;
      height: 1.25rem;
      color: map.get($colors, "slate", 400);
      cursor: pointer;
      opacity: 0.6;
      transition: opacity 0.2s ease;

      &:hover {
        opacity: 1;
      }
    }

    // Loading state
    .loading-spinner {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.75rem;

      img {
        width: 1.25rem;
        height: 1.25rem;
        animation: spin 1s linear infinite;
      }
    }
  }

  &.is-disabled {
    opacity: 0.6;
    cursor: not-allowed;

    .ts-control {
      background-color: map.get($colors, "slate", 100);
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
