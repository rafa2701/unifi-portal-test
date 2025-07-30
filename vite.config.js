import { defineConfig } from "vite";
import path from "path";
import vue from "@vitejs/plugin-vue";
import react from "@vitejs/plugin-react";
import vueDevTools from "vite-plugin-vue-devtools";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";

const isDev = process.env.NODE_ENV === "development";

const frontendPath = "src/frontend/templates/unifi-portal";
const adminPath = "src/admin/templates/unifi-portal";

const createPathAliases = (basePath, prefix = "") => ({
  [`${prefix}`]: path.resolve(__dirname, basePath),
  [`${prefix}assets`]: path.resolve(__dirname, `${basePath}/assets`),
  [`${prefix}constants`]: path.resolve(__dirname, `${basePath}/constants`),
  [`${prefix}components`]: path.resolve(__dirname, `${basePath}/components`),
  [`${prefix}composables`]: path.resolve(__dirname, `${basePath}/composables`),
  [`${prefix}services`]: path.resolve(__dirname, `${basePath}/services`),
  [`${prefix}stores`]: path.resolve(__dirname, `${basePath}/stores`),
  [`${prefix}views`]: path.resolve(__dirname, `${basePath}/views`),
  [`${prefix}utils`]: path.resolve(__dirname, `${basePath}/utils`),
});

export default defineConfig(({ command }) => ({
  plugins: [
    vue(),

    vueDevTools(),

    AutoImport({
      imports: [
        "vue",
        "vue-router",
        "pinia",
        "@vueuse/core",
        "react",
        "react-router-dom",
      ],
      dirs: [
        `${frontendPath}/composables`,
        `${frontendPath}/stores`,
        `${adminPath}/composables`,
        `${adminPath}/stores`,
      ],
      dts: false,
    }),

    Components({
      dirs: [`${frontendPath}/components`, `${adminPath}/components`],
      dts: false,
    }),
  ],

  root: "./",
  base: isDev ? "/" : "/wp-content/plugins/unifi-portal/dist/",

  build: {
    outDir: path.resolve(__dirname, "dist"),
    emptyOutDir: true,
    assetsInlineLimit: 4096,
    manifest: "assets.json",
    target: "es2018",
    minify: true,
    write: true,

    rollupOptions: {
      input: {
        "unifi-portal-frontend": path.resolve(
          __dirname,
          frontendPath,
          "unifi-portal.js",
        ),
        "unifi-portal-admin": path.resolve(
          __dirname,
          adminPath,
          "unifi-portal.js",
        ),
      },

      output: {
        chunkFileNames: "js/[name]-[hash].js",
        entryFileNames: "js/[name]-[hash].js",
        manualChunks(id) {
          if (id.includes("node_modules")) {
            const moduleName = id.split("node_modules/")[1].split("/")[0];
            return moduleName ? `unifi-vendor-${moduleName}` : null;
          }
        },
        assetFileNames({ name }) {
          if (/\.(gif|jpe?g|png|svg)$/.test(name ?? "")) {
            return "images/[name]-[hash][extname]";
          }
          if (/\.css$/.test(name ?? "")) {
            return "css/[name]-[hash][extname]";
          }
          if (/\.(woff|woff2|eot|ttf|otf)$/.test(name ?? "")) {
            return "fonts/[name]-[hash][extname]";
          }
          return "assets/[name]-[hash][extname]";
        },
      },
    },
  },

  resolve: {
    alias: {
      "@root": path.resolve(__dirname, "./"),
      ...createPathAliases(frontendPath, "@frontend/"),
      ...createPathAliases(adminPath, "@admin/"),
    },
  },

  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `
          @use "sass:math";
          @use "sass:map";
          @use "@root/assets/theme/base/_variables" as *;
        `,
        sassOptions: {
          includePaths: [
            path.resolve(__dirname, "assets/theme"),
            path.resolve(__dirname, "assets/theme"),
          ],
        },
      },
    },
  },

  server: {
    host: "0.0.0.0",
    cors: true,
    strictPort: true,
    port: 6173,
    https: false,
    hmr: {
      port: 6173,
    },
    watch: {
      usePolling: true,
    },
  },
}));
