<script lang="ts" setup>
import { NCard, NSwitch } from "naive-ui";
import { ref, watchEffect } from "vue";

const props = defineProps<{ description: string; slug: string }>();

type PluginData = {
  name: string;
  icons: {
    "1x": string;
  };
};

const pluginData = ref<PluginData | null>(null);

watchEffect(() => {
  fetch(
    `https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=${props.slug}&request[fields][icons]=true`,
  )
    .then((res) => res.json())
    .then((data) => (pluginData.value = data));
});
</script>
<template>
  <NCard :title="pluginData?.name">
    <template #cover>
      <img
        class="object-contain"
        width="128"
        height="128"
        :src="pluginData?.icons['1x']"
        :alt="pluginData?.name + ' icon'"
      />
    </template>
    {{ description }}
    <template #action>
      <div class="flex flex-wrap justify-between">
        Enable module
        <NSwitch></NSwitch>
      </div>
    </template>
  </NCard>
</template>
