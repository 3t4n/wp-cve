// vite.config.js
import { resolve } from 'path';
import { defineConfig, splitVendorChunkPlugin } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  resolve: {
    extensions: [
        ".js",
        ".vue",
    ],
  },
  build: {
    outDir: resolve(__dirname, 'assets/vue'),
    emptyOutDir: false,
    target: 'es2015',
    lib: {
      entry: resolve(__dirname, 'vue_src/frontend/main.js'),
      name: 'Pargo Button',
      // the proper extensions will be added
      fileName: 'pargo_button'
    },
    rollupOptions: {
      output:{
        assetFileNames: (assetInfo) => {
          if (assetInfo.name == 'style.css')
            return 'pargo_front.css';
          return assetInfo.name;
        },
      }
    }
  },
  plugins: [vue(), splitVendorChunkPlugin()],
  define: {
    'process.env.NODE_ENV': '"production"'
  }
});
