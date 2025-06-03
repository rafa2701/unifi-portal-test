<template>
  <div
    class="base-card"
    :class="[
      `card-${variant}`,
      {
        'card-hover': hover,
        'card-selected': selected,
        'card-highlighted': highlighted,
      },
    ]"
  >
    <div v-if="$slots.icon" class="card-icon">
      <slot name="icon"></slot>
    </div>
    <div v-if="$slots.header" class="card-header">
      <slot name="header"></slot>
    </div>
    <div class="card-content">
      <slot></slot>
    </div>
    <div v-if="$slots.footer" class="card-footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
/**
 * Base Card Component
 * A versatile card component that supports various styles and slots
 */
export default {
  name: "BaseCard",

  props: {
    variant: {
      type: String,
      default: "default",
      validator: (value) => ["default", "primary", "outline"].includes(value),
    },
    hover: {
      type: Boolean,
      default: false,
    },
    selected: {
      type: Boolean,
      default: false,
    },
    highlighted: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style lang="scss">
.base-card {
  position: relative;
  padding: map.get($spacing, "md");
  border-radius: 0.5rem;
  background: white;
  transition: all 0.3s ease;

  // Default variant
  &.card-default {
    border: 1px solid map.get($colors, "slate", 200);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  // Primary variant
  &.card-primary {
    background: linear-gradient(
      135deg,
      map.get($colors, "blue", 500),
      map.get($colors, "blue", 600)
    );
    color: white;
    border: none;
    box-shadow: 0 4px 6px -1px rgba(map.get($colors, "blue", 500), 0.3);

    .card-header {
      border-color: rgba(255, 255, 255, 0.1);
    }

    .card-footer {
      border-color: rgba(255, 255, 255, 0.1);
    }
  }

  // Outline variant
  &.card-outline {
    background: transparent;
    border: 2px solid map.get($colors, "blue", 200);
  }

  // Hover state
  &.card-hover {
    cursor: pointer;

    &:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 12px -1px rgba(0, 0, 0, 0.15);
    }
  }

  // Selected state
  &.card-selected {
    border-color: map.get($colors, "blue", 500);
    box-shadow: 0 0 0 2px map.get($colors, "blue", 200);
  }

  // Highlighted state
  &.card-highlighted {
    background: map.get($colors, "blue", 50);
    border-color: map.get($colors, "blue", 200);
  }

  // Icon slot
  .card-icon {
    display: flex;
    align-items: center;
    margin-bottom: map.get($spacing, "md");

    :deep(svg) {
      width: 1.5rem;
      height: 1.5rem;
      color: map.get($colors, "blue", 500);
    }

    .card-primary & {
      :deep(svg) {
        color: white;
      }
    }
  }

  // Header slot
  .card-header {
    margin: -(map.get($spacing, "lg"));
    margin-bottom: map.get($spacing, "lg");
    padding: map.get($spacing, "lg");
    border-bottom: 1px solid map.get($colors, "slate", 200);
  }

  // Content slot
  .card-content {
    :deep(h1, h2, h3, h4, h5, h6) {
      color: map.get($colors, "slate", 900);
      margin-bottom: map.get($spacing, "sm");

      .card-primary & {
        color: white;
      }
    }

    :deep(p) {
      color: map.get($colors, "slate", 600);
      font-size: map.get($font-size, "base");
      line-height: 1.6;
      margin-bottom: map.get($spacing, "md");

      &:last-child {
        margin-bottom: 0;
      }

      .card-primary & {
        color: rgba(255, 255, 255, 0.9);
      }
    }
  }

  // Footer slot
  .card-footer {
    margin: 0 calc(-1 * #{map.get($spacing, "lg")})
      calc(-1 * #{map.get($spacing, "lg")});
    padding: map.get($spacing, "lg");
    border-top: 1px solid map.get($colors, "slate", 200);
  }
}
</style>
