<script lang="ts" setup>
import {
  NButton,
  NH1,
  NLayout,
  NLayoutSider,
  NMenu,
  NSkeleton,
  NSpace,
  useMessage,
} from "naive-ui";
import { __ } from "@/plugins/i18n";
import { storeToRefs } from "pinia";
import { useSettingsStore } from "../store";
import { computed, toRefs } from "vue";
import { useRoute } from "vue-router";
import DefaultRenderer from "../components/DefaultRenderer.vue";
import ModulesRenderer from "../components/ModulesRenderer.vue";

const store = useSettingsStore();
const { loading, asMenuItems, values } = storeToRefs(store);
const { save, tab, $patch: updateSettings } = store;

const message = useMessage();

const { params } = toRefs(useRoute());

const currentTab = computed(() => (params.value.page as string) || (tab() as string));

function updateData({ data /* errors */ }) {
  updateSettings((state) => (state.values[currentTab.value] = data));
}

const determinedRenderer = computed(() => {
  if (params.value.page === "modules") return ModulesRenderer;
  return DefaultRenderer;
});

async function saveSettings() {
  const m = message.loading(__("Saving settings", "shopmagic-for-woocommerce"), { duration: 0 });
  try {
    await save(values.value);
    m.content = __(
      "Settings saved. You may need to reload the page to apply the changes.",
      "shopmagic-for-woocommerce",
    );
    m.type = "success";
  } catch (e) {
    m.content = e.message;
    m.type = "error";
  } finally {
    setTimeout(m.destroy, 2500);
  }
}
</script>
<template>
  <div class="flex gap-4">
    <NH1>{{ __("Settings", "shopmagic-for-woocommerce") }}</NH1>
  </div>
  <NLayout class="bg-transparent" has-sider>
    <NLayoutSider>
      <NSpace v-if="loading" class="my-2" justify="center" vertical>
        <NSkeleton
          v-for="i in 3"
          :key="i"
          :sharp="false"
          class="mx-auto"
          height="40px"
          width="90%"
        />
      </NSpace>
      <NMenu v-else :default-value="currentTab" :options="asMenuItems" />
    </NLayoutSider>
    <NLayout class="bg-gray-50" content-style="padding: 1rem 2rem">
      <NSpace v-if="loading" vertical>
        <NSkeleton v-for="i in 8" :key="i" :sharp="false" height="32px" text width="65%" />
      </NSpace>
      <div v-else>
        <component
          :is="determinedRenderer"
          v-bind="{
            values: values[currentTab],
            schema: tab(currentTab)?.fields,
          }"
          @change="updateData"
        />
      </div>
      <NButton class="mt-4" type="primary" @click="saveSettings()">
        {{ __("Save", "shopmagic-for-woocommerce") }}
      </NButton>
    </NLayout>
  </NLayout>
</template>
