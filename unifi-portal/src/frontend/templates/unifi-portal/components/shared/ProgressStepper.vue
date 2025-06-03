<template>
  <nav class="progress-stepper">
    <ol class="progress-list">
      <li
        v-for="(step, index) in steps"
        :key="step.name"
        :class="[
          'progress-item',
          {
            'is-active': currentStep === index,
            'is-completed': currentStep > index,
          },
        ]"
      >
        <div class="progress-indicator">
          <span v-if="currentStep > index" class="progress-check">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </span>
          <span v-else class="progress-step">{{ index + 1 }}</span>
        </div>
        <div class="progress-content">
          <h3 class="progress-title">{{ step.title }}</h3>
          <p class="progress-desc">{{ step.description }}</p>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script>
/**
 * Progress Stepper Component
 * Displays a vertical progress indicator for multi-step workflows
 */
export default {
  name: "ProgressStepper",

  props: {
    steps: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every(
          (step) =>
            typeof step.title === "string" &&
            typeof step.description === "string" &&
            typeof step.name === "string"
        );
      },
    },
    currentStep: {
      type: Number,
      required: true,
      validator: (value) => value >= 0,
    },
  },
};
</script>

<style lang="scss">
.progress-stepper {
  padding: map.get($spacing, "lg");

  .progress-list {
    position: relative;
    counter-reset: steps;
    list-style: none;

    &::before {
      content: "";
      position: absolute;
      left: 1.125rem;
      top: 0;
      height: 100%;
      width: 2px;
      background: map.get($colors, "slate", 200);
    }
  }

  .progress-item {
    position: relative;
    padding: map.get($spacing, "md") 0;
    padding-left: map.get($spacing, "xl");

    &.is-active {
      .progress-indicator {
        background: map.get($colors, "blue", 500);
        color: white;
      }

      .progress-title {
        color: map.get($colors, "blue", 700);
      }
    }

    &.is-completed {
      .progress-indicator {
        background: map.get($colors, "blue", 700);
        color: white;
      }

      .progress-check {
        svg {
          width: 1rem;
          height: 1rem;
        }
      }
    }
  }

  .progress-indicator {
    position: absolute;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 50%;
    background: map.get($colors, "slate", 100);
    color: map.get($colors, "slate", 600);
    font-weight: 600;
    z-index: 1;
  }

  .progress-content {
    min-height: 2.25rem;
    padding-left: map.get($spacing, "md");
  }

  .progress-title {
    margin: 0;
    font-size: map.get($font-size, "lg");
    font-weight: 600;
    color: map.get($colors, "slate", 700);
  }

  .progress-desc {
    margin: map.get($spacing, "xs") 0 0;
    font-size: map.get($font-size, "sm");
    color: map.get($colors, "slate", 500);
  }
}
</style>
