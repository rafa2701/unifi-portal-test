import { createRouter, createWebHashHistory } from "vue-router";
import { UNIFI } from "@frontend/constants";

// Import views
import UnifiHome from "@frontend/views/Home/UnifiHome.vue";

// Lazy-load report views
const ReportLayout = () => import("@frontend/views/Reports/ReportLayout.vue");
const ModernReport = () =>
  import("@frontend/views/Reports/Modern/ModernReport.vue");
const LegacyReport = () =>
  import("@frontend/views/Reports/Legacy/LegacyReport.vue");

/**
 * Router configuration for UniFi Portal
 */
const routes = [
  {
    path: "/",
    name: UNIFI.ROUTES.PORTAL,
    component: UnifiHome,
    meta: {
      title: "UniFi Portal - Home",
    },
  },
  {
    path: "/reports",
    name: UNIFI.ROUTES.REPORTS,
    component: ReportLayout,
    meta: {
      title: "UniFi Portal - Reports",
    },
    children: [
      {
        path: "modern",
        name: UNIFI.ROUTES.REPORTS_MODERN,
        component: ModernReport,
        meta: {
          title: "UniFi Portal - Modern Report",
        },
      },
      {
        path: "legacy",
        name: UNIFI.ROUTES.REPORTS_LEGACY,
        component: LegacyReport,
        meta: {
          title: "UniFi Portal - Legacy Report",
        },
      },
    ],
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

/**
 * Update document title on route change
 */
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || "UniFi Portal";
  next();
});

export default router;
