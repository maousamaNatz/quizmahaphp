import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'assets/dist',
    rollupOptions: {
      input: 'assets/js/main.js'
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
      'alpinejs': path.resolve(__dirname, 'node_modules/alpinejs'),
      'gsap': path.resolve(__dirname, 'node_modules/gsap'),
      'split-type': path.resolve(__dirname, 'node_modules/split-type')
    }
  }
}); 