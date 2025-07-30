<template>
  <div class="unifi-portal-admin">
    <div class="admin-view">
      <div class="admin-header">
        <div class="header-wrapper">
          <img
            :src="$img('icons/unifi-logo.svg')"
            alt="UniFi Logo"
            class="admin-logo"
          />
          <div class="header-content">
            <h1>UniFi Portal</h1>
            <p class="header-subtitle">WordPress Plugin Settings</p>
          </div>
        </div>
      </div>

      <div class="admin-container">
        <div class="admin-content">
          <div class="tabs-navigation">
            <BaseTab
              :modelValue="currentTab"
              :tabs="tabs"
              variant="underline"
              @update:modelValue="handleTabChange"
            />
          </div>

          <div class="tab-content">
            <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
                <component :is="Component" />
              </transition>
            </router-view>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import BaseTab from "@admin/components/base/BaseTab.vue";
import { Home, Server, FileText, Activity, Settings } from "lucide-vue-next";

export default {
  name: "AdminView",

  components: {
    BaseTab,
  },

  setup() {
    const router = useRouter();
    const route = useRoute();

    const tabs = [
      {
        id: "home",
        title: "Home",
        icon: "Home",
        route: "admin-home",
      },
      {
        id: "controllers",
        title: "Controllers",
        icon: "Server",
        route: "admin-controllers",
      },
      {
        id: "docs",
        title: "Documentation",
        icon: "FileText",
        route: "admin-docs",
      },
      {
        id: "test",
        title: "Test",
        icon: "Activity",
        route: "admin-test",
      },
      {
        id: "settings",
        title: "Settings",
        icon: "Settings",
        route: "admin-settings",
      },
    ];

    const currentTab = computed(() => {
      const currentRoute = route.name;
      const tab = tabs.find((tab) => tab.route === currentRoute);
      return tab ? tab.id : "home";
    });

    const handleTabChange = (tabId) => {
      const tab = tabs.find((t) => t.id === tabId);
      if (tab) {
        router.push({ name: tab.route });
      }
    };

    return {
      tabs,
      currentTab,
      handleTabChange,
    };
  },
};
</script>

<style lang="scss">
// Reset WordPress admin styles for our plugin
.unifi-portal-admin {
  all: initial;
  * {
    all: revert;
  }

  // Now apply our custom styles
  .admin-view {
    position: relative;
    min-height: calc(100vh - 80px);
    background: map.get($colors, "slate", 50);
    font-family: map.get($font-family, "sans");
    line-height: 1.5;
    color: map.get($colors, "slate", 900);

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
      margin: 0;
      padding: 0;
      font-family: map.get($font-family, "sans");
    }

    a {
      color: map.get($colors, "blue", 600);
      text-decoration: none;

      &:hover {
        text-decoration: underline;
      }
    }

    button {
      font-family: map.get($font-family, "sans");
    }
  }

  .admin-header {
    background: white;
    border-bottom: 1px solid map.get($colors, "slate", 200);
    padding: map.get($spacing, "md") map.get($spacing, "2xl");
    margin-bottom: map.get($spacing, "xl");

    .header-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      gap: map.get($spacing, "lg");
    }

    .admin-logo {
      width: 3rem;
      height: 3rem;
    }

    .header-content {
      h1 {
        font-size: map.get($font-size, "2xl");
        color: map.get($colors, "slate", 900);
        margin-bottom: map.get($spacing, "xs");
        font-weight: 600;
        line-height: 1.2;
      }

      .header-subtitle {
        color: map.get($colors, "slate", 600);
        font-size: map.get($font-size, "base");
        margin: 0;
      }
    }
  }

  .admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 map.get($spacing, "2xl");
    box-sizing: border-box;
  }

  .admin-content {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    min-height: 600px;
  }

  .tabs-navigation {
    :deep(.base-tabs) {
      .tabs-header {
        padding: 0 map.get($spacing, "lg");
        background: map.get($colors, "slate", 50);
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-bottom: 1px solid map.get($colors, "slate", 200);
      }

      .tab-button {
        padding: map.get($spacing, "md") map.get($spacing, "lg");
        font-weight: 500;
        position: relative;
        background: transparent;
        border: none;
        cursor: pointer;
        font-family: map.get($font-family, "sans");

        &.tab-active {
          color: map.get($colors, "blue", 600);
          background: white;
          margin-bottom: -1px;

          &::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: map.get($colors, "blue", 600);
          }
        }

        .tab-icon {
          svg {
            width: 1.25rem;
            height: 1.25rem;
          }
        }
      }
    }
  }

  .tab-content {
    padding: map.get($spacing, "xl");
  }

  // Animation classes
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.15s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
}

// Media queries
@media (max-width: map.get($breakpoints, "md")) {
  .unifi-portal-admin {
    .admin-header {
      padding: map.get($spacing, "md");

      .admin-logo {
        width: 2.5rem;
        height: 2.5rem;
      }

      .header-content {
        h1 {
          font-size: map.get($font-size, "xl");
        }

        .header-subtitle {
          font-size: map.get($font-size, "sm");
        }
      }
    }

    .admin-container {
      padding: 0 map.get($spacing, "md");
    }

    .tab-content {
      padding: map.get($spacing, "md");
    }
  }
}
</style>
