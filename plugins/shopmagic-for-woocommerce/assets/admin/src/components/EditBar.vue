<script lang="ts" setup>
import { NButton, NIcon, NInput, NSpace, NSwitch } from "naive-ui";
import { ChevronBackOutline } from "@vicons/ionicons5";
import type { CSSProperties } from "vue";

defineProps<{
  name?: string | null;
  namePlaceholder?: string;
  publish: boolean;
}>();

defineEmits<{
  (e: "update:publish", value: boolean): void;
  (e: "update:name", value: string | null): void;
  (e: "save"): void;
}>();

function switchStyle({ checked }: { focused: boolean; checked: boolean }): CSSProperties | string {
  const style: CSSProperties = {};
  if (!checked) {
    style["--n-text-color"] = "#676666";
  }
  return style;
}
</script>
<template>
  <NSpace justify="space-between">
    <div class="flex items-center gap-4">
      <NButton text @click="$router.back() || $router.push('/automations')">
        <NIcon>
          <ChevronBackOutline />
        </NIcon>
      </NButton>
      <NInput
        :autofocus="name === null"
        :placeholder="namePlaceholder || __('New awesome creation', 'shopmagic-for-woocommerce')"
        :value="name"
        class="min-w-[520px]"
        @update:value="$emit('update:name', $event)"
      />
    </div>
    <div class="items-center flex gap-4">
      <NButton ghost @click="$emit('save')">{{ __("Save", "shopmagic-for-woocommerce") }}</NButton>
      <NSwitch
        :rail-style="switchStyle"
        :value="publish"
        @update:value="$emit('update:publish', $event)"
      >
        <template #checked>
          {{ __("Published", "shopmagic-for-woocommerce") }}
        </template>
        <template #unchecked>
          {{ __("Draft", "shopmagic-for-woocommerce") }}
        </template>
      </NSwitch>
    </div>
  </NSpace>
</template>
