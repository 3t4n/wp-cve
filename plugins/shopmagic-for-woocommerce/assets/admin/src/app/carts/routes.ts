import type { RouteRecordRaw } from "vue-router";

const routes: RouteRecordRaw[] = [];

if (window.ShopMagic.modules.includes("shopmagic-abandoned-carts")) {
  routes.push({
    name: "carts",
    path: "/carts",
    component: async () => import("./views/CartsPage.vue"),
  });
}

export default routes;
