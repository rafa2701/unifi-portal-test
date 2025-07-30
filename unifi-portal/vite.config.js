import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
      '@admin': path.resolve(__dirname, './src/admin/templates/unifi-portal'),
      '@frontend': path.resolve(__dirname, './src/frontend/templates/unifi-portal'),
      '@root': path.resolve(__dirname, '.')
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `
          @import "@root/assets/theme/base/_variables.scss";
        `
      }
    }
  },
  build: {
    outDir: 'dist',
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: {
        'unifi-portal-admin': path.resolve(__dirname, 'src/admin/templates/unifi-portal/unifi-portal.js'),
        'unifi-portal-frontend': path.resolve(__dirname, 'src/frontend/templates/unifi-portal/unifi-portal.js')
      }
    }
  }
})
