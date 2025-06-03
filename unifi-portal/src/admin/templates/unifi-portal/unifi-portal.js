import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";

// Style imports
import "@root/assets/theme/main.scss";

// Utils
import { img } from "./utils/image";

/**
 * Create and configure the Vue application instance
 */
const createVueApp = () => {
  const app = createApp(App);
  const pinia = createPinia();

  // Register plugins
  app.use(router);
  app.use(pinia);

  // Global properties
  app.config.globalProperties.$img = img;

  // Mount the app
  app.mount("#unifi-portal-admin");

  return app;
};

// Initialize the application
const app = createVueApp();

// Handle HMR for development
if (import.meta.hot) {
  import.meta.hot.accept();
}

export default app;
