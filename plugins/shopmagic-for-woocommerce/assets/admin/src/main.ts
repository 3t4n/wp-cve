import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./app/App.vue";
import router from "@/router";
import "./assets/base.css";
import { i18n } from "@/plugins/i18n";
import "chartjs-adapter-luxon";
import { modulesKey, proEnabledKey, userKey } from "@/provide";
import * as log from "@/_utils/log";

window.addEventListener("unhandledrejection", ({ reason }) => {
  if (reason instanceof Error) {
    log.error(reason.message, { cause: reason.cause });
  }
});

const app = createApp(App);

app.provide(userKey, window.ShopMagic.user);

app.provide(modulesKey, window.ShopMagic.modules);

app.provide(proEnabledKey, window.ShopMagic.proEnabled === "1");

app.use(createPinia());
app.use(router);
app.use(i18n, { domain: "shopmagic-for-woocommerce" });

app.mount("#shopmagic-app");
