<script lang="ts" setup>
import { NSwitch } from "naive-ui";
import { ref, watchEffect } from "vue";
import type { ControlElement } from "@jsonforms/core";
import { useVanillaControl } from "../util";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import ShadyCard from "@/components/ShadyCard.vue";

const props = defineProps(rendererProps<ControlElement>());

const { control, onChange } = useVanillaControl(useJsonFormsControl(props), (target) => target);

type PluginData = {
  name: string;
  icons: {
    "1x": string;
  };
};

const pluginData = ref<PluginData | null>(null);

watchEffect(() => {
  const slug = control.value.schema.presentation.pluginSlug;
  if (!slug) return;
  fetch(
    `https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=${slug}&request[fields][icons]=true`,
  )
    .then((res) => res.json())
    .then((data) => (pluginData.value = data));
});
</script>
<template>
  <ShadyCard :title="control.label">
    <template #cover>
      <img
        v-if="pluginData"
        :alt="pluginData?.name + ' icon'"
        :src="pluginData?.icons['1x']"
        class="object-contain"
        height="128"
        width="128"
      />
    </template>
    {{ control.description }}
    <template #action>
      <div class="flex flex-wrap justify-between">
        {{ __("Enable module", "shopmagic-for-woocommerce") }}
        <NSwitch :value="control.data" @update:value="onChange"></NSwitch>
      </div>
    </template>
  </ShadyCard>
</template>
