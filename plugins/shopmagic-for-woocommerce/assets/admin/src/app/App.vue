<script lang="ts" setup>
import type { GlobalThemeOverrides, MenuOption } from "naive-ui";
import {
  NConfigProvider,
  NLayout,
  NLayoutContent,
  NLayoutHeader,
  NMenu,
  NMessageProvider,
} from "naive-ui";
import ShopMagicIcon from "@/components/ShopMagicIcon.vue";
import { menu } from "@/data/menu";

function nodeProps(options: MenuOption) {
  if (options.key !== "pro") return {};
  return {
    class: ["bg-[#50C878]", "rounded"],
  };
}

const themeOverrides: GlobalThemeOverrides = {
  common: {
    primaryColor: "#50C878",
  },
  Typography: {
    headerFontSize1: "26px",
  },
  Card: {
    borderRadius: "12px",
  },
};
</script>
<template>
  <NConfigProvider :theme-overrides="themeOverrides">
    <NMessageProvider :max="2" placement="bottom-right">
      <NLayout class="bg-transparent">
        <NLayoutHeader bordered class="flex justify-between items-center px-4 shadow-sm">
          <RouterLink :to="{ name: 'dashboard' }" class="w-[175px] py-1.5">
            <ShopMagicIcon />
          </RouterLink>
          <NMenu
            :dropdown-props="{ size: 'large', trigger: 'click' }"
            :node-props="nodeProps"
            :options="menu"
            :responsive="false"
            dropdown-placement="bottom-start"
            mode="horizontal"
          />
        </NLayoutHeader>
        <NLayout class="bg-transparent">
          <NLayoutContent class="bg-transparent" content-style="padding: 16px">
            <RouterView />
          </NLayoutContent>
        </NLayout>
      </NLayout>
    </NMessageProvider>
  </NConfigProvider>
</template>

<style>
#wpcontent {
  padding-left: 0;
}

#wpbody {
  margin-top: 0 !important;
}

#wpbody-content {
  all: revert;
}

/** Hide all notifications from shopmagic page **/
#wpbody-content > :not(#shopmagic-app) {
  display: none !important;
}

#wpfooter {
  display: none;
}

.notice {
  display: none;
}

input[type="checkbox"]:focus,
input[type="color"]:focus,
input[type="date"]:focus,
input[type="datetime-local"]:focus,
input[type="datetime"]:focus,
input[type="email"]:focus,
input[type="month"]:focus,
input[type="number"]:focus,
input[type="password"]:focus,
input[type="radio"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="text"]:focus,
input[type="time"]:focus,
input[type="url"]:focus,
input[type="week"]:focus,
select:focus,
textarea:focus,
a:focus {
  border-color: unset;
  box-shadow: unset;
  outline: unset;
}
a {
  text-decoration: none;
}

.n-base-select-menu .n-base-select-option .n-base-select-option__content {
  white-space: revert;
  text-overflow: revert;
  overflow: revert;
}

/* Fix horizontal menu after UI package update. */
.n-menu.n-menu--horizontal {
  width: auto;
}
</style>
