import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  base: "",
  optimizeDeps: {
    disabled: "build",
  },
  esbuild: {
    /**
     * WordPress doesn't parse esbuild minification correctly.
     * Due to that don't optimize identifiers for better i18n handling.
     */
    minifyIdentifiers: false,
  },
  build: {
    manifest: true,
    outDir: "../../dist/admin",
    reportCompressedSize: false,
    chunkSizeWarningLimit: 3000,
    rollupOptions: {
      output: {
        chunkFileNames: "[name].js",
        entryFileNames: "[name].js",
        assetFileNames: "[name].[ext]",
        manualChunks: () => "app",
      },
      input: "src/main.ts",
    },
  },
});
