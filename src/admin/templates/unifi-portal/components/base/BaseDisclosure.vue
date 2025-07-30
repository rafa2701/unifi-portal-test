<template>
  <div class="base-disclosure">
    <Disclosure v-slot="{ open }" :defaultOpen="defaultOpen">
      <DisclosureButton
        class="disclosure-button"
        :class="{ 'button-open': open }"
      >
        <slot name="title">
          <span class="button-text">{{ title }}</span>
        </slot>
        <span class="button-icon" :class="{ 'icon-open': open }">
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
      </DisclosureButton>

      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-out"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <DisclosurePanel class="disclosure-panel">
          <slot></slot>
        </DisclosurePanel>
      </transition>
    </Disclosure>
  </div>
</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";

/**
 * Base Disclosure Component
 * A collapsible section component built with HeadlessUI
 */
export default {
  name: "BaseDisclosure",

  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
  },

  props: {
    title: {
      type: String,
      required: true,
    },
    defaultOpen: {
      type: Boolean,
      default: true,
    },
  },
};
</script>

<style lang="scss">
.base-disclosure {
  width: 100%;
  background: white;
  border: 1px solid map.get($colors, "slate", 200);
  border-radius: 0.375rem;
  overflow: hidden;

  .disclosure-button {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.875rem 1rem;
    background: white;
    cursor: pointer;
    outline: none;
    border: none;
    transition: background-color 0.2s ease;

    &:hover {
      background-color: map.get($colors, "slate", 50);
    }

    &:focus {
      background-color: map.get($colors, "slate", 100);
    }

    &.button-open {
      border-bottom: 1px solid map.get($colors, "slate", 200);
    }

    .button-text {
      flex: 1;
      text-align: left;
      font-weight: 500;
      color: map.get($colors, "slate", 700);
    }

    .button-icon {
      flex-shrink: 0;
      margin-left: 0.75rem;
      color: map.get($colors, "slate", 400);
      transition: transform 0.2s ease;

      &.icon-open {
        transform: rotate(-180deg);
      }

      .icon {
        width: 1.25rem;
        height: 1.25rem;
      }
    }
  }

  .disclosure-panel {
    padding: 1rem;
    background: white;
    color: map.get($colors, "slate", 600);
    font-size: map.get($font-size, "base");
    line-height: 1.5;
  }
}
</style>
