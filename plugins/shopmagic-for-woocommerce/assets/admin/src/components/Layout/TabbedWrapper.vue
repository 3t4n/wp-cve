<script lang="ts" setup>
import type { TabsProps } from "naive-ui";
import { NTab, NTabs } from "naive-ui";
import { RouterView, useRoute, useRouter } from "vue-router";
import { ref } from "vue";

type Tabs = {
  [k: string]: string;
};

withDefaults(defineProps<{ tabs: Tabs; showTabs: boolean }>(), {
  showTabs: true,
});

const router = useRouter();
const route = useRoute();
function switchTab(value: string) {
  router.push({ name: value });
}
const currentRoute = ref<string | null>(null);
currentRoute.value = route.name?.toString() || null;
router.afterEach((to) => {
  currentRoute.value = to.name?.toString() || null;
});

type TabThemeOverrides = NonNullable<TabsProps["themeOverrides"]>;

const themeOverrides: TabThemeOverrides = {
  tabBorderColor: "#d9d9d9",
};
</script>
<template>
  <NTabs
    v-if="showTabs"
    :theme-overrides="themeOverrides"
    class="mb-6"
    type="card"
    @update:value="switchTab"
    v-model:value="currentRoute"
  >
    <NTab v-for="(name, tab) in tabs" :key="tab" :name="tab">
      {{ name }}
    </NTab>
  </NTabs>
  <RouterView />
</template>
