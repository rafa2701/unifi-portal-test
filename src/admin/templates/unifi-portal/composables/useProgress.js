import { ref, computed } from "vue";
import { useRouter, useRoute } from "vue-router";

/**
 * Progress steps configuration for the Unifi Portal workflow
 */
export const steps = [
  {
    name: "portal",
    title: "UniFi Portal",
    description: "Get started with UniFi Network Management",
  },
  {
    name: "controller",
    title: "Controller",
    description: "Select your Unifi controller",
  },
  {
    name: "topology",
    title: "Topology",
    description: "View network topology",
  },
  {
    name: "flowchart",
    title: "Flowchart",
    description: "Access management tools",
  },
  {
    name: "reports",
    title: "Report",
    description: "View detailed reports",
  },
];

/**
 * Setup navigation guards for the progress workflow
 */
export function setupProgressGuards(router) {
  router.beforeEach((to, from, next) => {
    const targetIndex = steps.findIndex((step) => step.name === to.name);
    const fromIndex = steps.findIndex((step) => step.name === from.name);

    if (targetIndex > fromIndex + 1) {
      next({ name: steps[fromIndex + 1].name });
    } else {
      next();
    }
  });
}

/**
 * Composable for managing progress state and navigation
 */
export function useProgress() {
  const router = useRouter();
  const route = useRoute();

  const currentStepIndex = computed(() => {
    return steps.findIndex((step) => step.name === route.name);
  });

  const breadcrumbs = computed(() => {
    const currentIndex = currentStepIndex.value;
    return steps.slice(0, currentIndex + 1);
  });

  const canGoBack = computed(() => {
    return currentStepIndex.value > 0;
  });

  const canGoForward = computed(() => {
    return currentStepIndex.value < steps.length - 1;
  });

  const goBack = async () => {
    if (canGoBack.value) {
      const prevStep = steps[currentStepIndex.value - 1];
      await router.push({ name: prevStep.name });
    }
  };

  const goForward = async () => {
    if (canGoForward.value) {
      const nextStep = steps[currentStepIndex.value + 1];
      await router.push({ name: nextStep.name });
    }
  };

  return {
    steps,
    currentStepIndex,
    breadcrumbs,
    canGoBack,
    canGoForward,
    goBack,
    goForward,
  };
}
