import { createRouter, createWebHashHistory } from "vue-router";

const routes = [
  {
    path: "/",
    component: () => import("@admin/views/AdminView.vue"),
    children: [
      {
        path: "",
        name: "admin-home",
        component: () => import("@admin/views/HomeView.vue"),
      },
      {
        path: "controllers",
        name: "admin-controllers",
        component: () => import("@admin/views/ControllerView.vue"),
      },
      {
        path: "docs",
        name: "admin-docs",
        component: () => import("@admin/views/DocsView.vue"),
      },
      {
        path: "test",
        name: "admin-test",
        component: () => import("@admin/views/TestView.vue"),
      },
      {
        path: "settings",
        name: "admin-settings",
        component: () => import("@admin/views/SettingsView.vue"),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
