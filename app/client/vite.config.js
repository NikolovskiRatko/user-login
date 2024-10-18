import path from "node:path";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
// eslint-disable-next-line import/default
import eslintPlugin from "vite-plugin-eslint";
import { viteStaticCopy } from "vite-plugin-static-copy";

const shouldMinify = process.env.NODE_ENV !== "development";

export default defineConfig({
  plugins: [
    laravel({
      input: ["src/app.ts"],
      refresh: true,
      publicDirectory: "../api/public",
    }),
    vue({
      template: {
        transformAssetUrls: {
          // The Vue plugin will re-write asset URLs, when referenced
          // in Single File Components, to point to the Laravel web
          // server. Setting this to `null` allows the Laravel plugin
          // to instead re-write asset URLs to point to the Vite
          // server instead.
          base: null,

          // The Vue plugin will parse absolute URLs and treat them
          // as absolute paths to files on disk. Setting this to
          // `false` will leave absolute URLs un-touched so they can
          // reference assets in the public directory as expected.
          includeAbsolute: false,
        },
      },
    }),
    viteStaticCopy({
      targets: [
        {
          src: path.resolve(__dirname, "./assets/images"),
          dest: "./images",
        },
      ],
    }),
    eslintPlugin(),
  ],
  build: {
    outDir: path.resolve(__dirname, "./../api/public/build"),
    emptyOutDir: true,
    minify: shouldMinify ? "terser" : false,
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
      "@styles": path.resolve(__dirname, "./assets/sass"),
    },
  },
  css: {
    preprocessorOptions: {
      scss: {},
    },
  },
  server: {
    host: "0.0.0.0",
    hmr: {
      host: "localhost",
      clientPort: 5173,
    },
  },
  __VUE_PROD_DEVTOOLS__: process.env.NODE_ENV === "development",
});
