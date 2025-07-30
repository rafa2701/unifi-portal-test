<template>
  <nav class="breadcrumbs" aria-label="Breadcrumbs">
    <ol>
      <li
        v-for="(crumb, index) in breadcrumbs"
        :key="crumb.name"
        :class="{ 'is-current': index === breadcrumbs.length - 1 }"
      >
        <button
          v-if="index < breadcrumbs.length - 1"
          class="breadcrumb-link"
          :disabled="!canNavigate(crumb.name)"
          @click="navigateTo(crumb.name)"
        >
          {{ crumb.title }}
        </button>
        <span v-else class="breadcrumb-text">{{ crumb.title }}</span>

        <svg
          v-if="index < breadcrumbs.length - 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="breadcrumb-separator"
        >
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </li>
    </ol>
  </nav>
</template>

<script>
import { useProgress } from "@frontend/composables/core/useProgress";

export default {
  name: "Breadcrumbs",

  props: {
    breadcrumbs: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every(
          (crumb) =>
            typeof crumb.title === "string" && typeof crumb.name === "string"
        );
      },
    },
  },

  setup() {
    const { canNavigate, navigateTo } = useProgress();

    return {
      canNavigate,
      navigateTo,
    };
  },
};
</script>

<style lang="scss">
.breadcrumbs {
  padding: map.get($spacing, "md") map.get($spacing, "lg");
  background: map.get($colors, "slate", 50);
  border-bottom: 1px solid map.get($colors, "slate", 200);

  ol {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  li {
    display: flex;
    align-items: center;
    font-size: map.get($font-size, "sm");

    &.is-current {
      .breadcrumb-text {
        color: map.get($colors, "slate", 700);
        font-weight: 600;
      }
    }
  }

  .breadcrumb-link {
    appearance: none;
    background: none;
    border: none;
    padding: 0;
    color: map.get($colors, "blue", 600);
    font-size: inherit;
    cursor: pointer;
    transition: color 0.2s ease;

    &:hover:not(:disabled) {
      color: map.get($colors, "blue", 700);
    }

    &:disabled {
      color: map.get($colors, "slate", 400);
      cursor: not-allowed;
    }
  }

  .breadcrumb-text {
    color: map.get($colors, "slate", 600);
  }

  .breadcrumb-separator {
    width: 1rem;
    height: 1rem;
    margin: 0 map.get($spacing, "sm");
    color: map.get($colors, "slate", 400);
  }
}
</style>
