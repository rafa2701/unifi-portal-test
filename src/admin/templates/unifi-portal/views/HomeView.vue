<template>
  <div class="home-view">
    <div class="welcome-content">
      <div class="welcome-header">
        <img
          :src="$img('icons/unifi-logo.svg')"
          alt="UniFi Logo"
          class="welcome-logo"
        />
        <h1>Welcome to UniFi Portal</h1>
        <p class="welcome-subtitle">
          Manage UniFi portal settings and configurations.
        </p>
      </div>

      <div class="quick-actions">
        <BaseCard
          v-for="action in quickActions"
          :key="action.id"
          hover
          class="action-card"
          @click="handleAction(action)"
        >
          <template #icon>
            <component :is="action.icon" class="action-icon" />
          </template>
          <h3>{{ action.title }}</h3>
          <p>{{ action.description }}</p>
        </BaseCard>
      </div>
    </div>
  </div>
</template>

<script>
import BaseCard from "@admin/components/base/BaseCard.vue";
import { Server, FileText, Activity, Settings } from "lucide-vue-next";
import { useRouter } from "vue-router";

export default {
  name: "HomeView",

  components: {
    BaseCard,
    Server,
    FileText,
    Activity,
    Settings,
  },

  setup() {
    const router = useRouter();

    const quickActions = [
      {
        id: "controllers",
        title: "Controller Management",
        description: "Add or manage UniFi controllers",
        icon: "Server",
        route: "admin-controllers",
      },
      {
        id: "test",
        title: "Connection Test",
        description: "Test controller connectivity",
        icon: "Activity",
        route: "admin-test",
      },
      {
        id: "docs",
        title: "Documentation",
        description: "View plugin documentation",
        icon: "FileText",
        route: "admin-docs",
      },
      {
        id: "settings",
        title: "Settings",
        description: "Configure plugin settings",
        icon: "Settings",
        route: "admin-settings",
      },
    ];

    const handleAction = (action) => {
      router.push({ name: action.route });
    };

    return {
      quickActions,
      handleAction,
    };
  },
};
</script>

<style lang="scss">
.home-view {
  padding: map.get($spacing, "xl") 0;
}

.welcome-content {
  max-width: 1000px;
  margin: 0 auto;
}

.welcome-header {
  text-align: center;
  margin-bottom: map.get($spacing, "2xl");

  .welcome-logo {
    width: 6rem;
    height: 6rem;
    margin: 0 auto map.get($spacing, "lg");
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
  }

  h1 {
    font-size: map.get($font-size, "3xl");
    color: map.get($colors, "slate", 900);
    margin-bottom: map.get($spacing, "sm");
    background: linear-gradient(
      135deg,
      map.get($colors, "blue", 600),
      map.get($colors, "blue", 400)
    );
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .welcome-subtitle {
    color: map.get($colors, "slate", 600);
    font-size: map.get($font-size, "lg");
    max-width: 36rem;
    margin: 0 auto;
  }
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: map.get($spacing, "lg");

  .action-card {
    text-align: center;
    padding: map.get($spacing, "xl");
    cursor: pointer;
    transition: all 0.2s ease;

    &:hover {
      transform: translateY(-4px);
    }

    .action-icon {
      width: 2rem;
      height: 2rem;
      color: map.get($colors, "blue", 500);
      margin-bottom: map.get($spacing, "md");
    }

    h3 {
      font-size: map.get($font-size, "lg");
      color: map.get($colors, "slate", 900);
      margin-bottom: map.get($spacing, "sm");
    }

    p {
      color: map.get($colors, "slate", 600);
      font-size: map.get($font-size, "base");
      line-height: 1.5;
    }
  }
}

@media (max-width: map.get($breakpoints, "md")) {
  .welcome-header {
    .welcome-logo {
      width: 4rem;
      height: 4rem;
    }

    h1 {
      font-size: map.get($font-size, "2xl");
    }

    .welcome-subtitle {
      font-size: map.get($font-size, "base");
    }
  }
}
</style>
