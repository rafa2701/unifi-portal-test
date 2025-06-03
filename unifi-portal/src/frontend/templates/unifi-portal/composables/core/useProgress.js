import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useControllerStore } from "@frontend/stores/controller";
import { UNIFI } from "@frontend/constants";

export function useProgress() {
  const route = useRoute();
  const router = useRouter();
  const store = useControllerStore();

  const steps = [
    {
      name: UNIFI.ROUTES.PORTAL,
      title: "Start",
      description: "Begin your network setup",
    },
    {
      name: UNIFI.ROUTES.CONTROLLER,
      title: "Controller",
      description: "Select and connect controller",
    },
    {
      name: UNIFI.ROUTES.REPORTS,
      title: "Reports",
      description: "View network reports",
    },
    {
      name: UNIFI.ROUTES.TOPOLOGY,
      title: "Topology",
      description: "Network topology view",
    },
    {
      name: UNIFI.ROUTES.FLOWCHART,
      title: "Flowchart",
      description: "Network flow analysis",
    },
  ];

  const currentStepIndex = computed(() => {
    return steps.findIndex((step) => step.name === route.name);
  });

  const canNavigateNext = computed(() => {
    const nextIndex = currentStepIndex.value + 1;
    return nextIndex < steps.length;
  });

  const canNavigateBack = computed(() => {
    return currentStepIndex.value > 0;
  });

  const breadcrumbs = computed(() => {
    const currentIndex = currentStepIndex.value;
    const validSteps = steps.slice(0, currentIndex + 1);

    return validSteps.map((step) => ({
      name: step.name,
      title: step.title,
    }));
  });

  const canNavigate = (targetName) => {
    if (targetName === UNIFI.ROUTES.PORTAL) return true;
    if (targetName === UNIFI.ROUTES.CONTROLLER) return true;
    if (!store.isControllerSelected || !store.isSiteSelected) return false;
    return true;
  };

  const navigateTo = async (name) => {
    try {
      await router.push({ name });
      return true;
    } catch (error) {
      console.error("Navigation failed:", error);
      return false;
    }
  };

  const handleBack = async () => {
    if (!canNavigateBack.value) return false;

    const previousStep = steps[currentStepIndex.value - 1];
    return navigateTo(previousStep.name);
  };

  const handleNext = async () => {
    if (!canNavigateNext.value) return false;

    const nextStep = steps[currentStepIndex.value + 1];
    return navigateTo(nextStep.name);
  };

  return {
    steps,
    currentStepIndex,
    breadcrumbs,
    canNavigate,
    canNavigateNext,
    canNavigateBack,
    navigateTo,
    handleBack,
    handleNext,
  };
}
