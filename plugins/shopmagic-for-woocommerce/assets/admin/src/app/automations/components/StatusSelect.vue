<script lang="ts" setup>
import { NSelect } from "naive-ui";

defineEmits<{
  (e: "update:value", value: string): void;
}>();
</script>
<template>
  <NSelect
    :options="[
      {
        label: () => __('Draft', 'shopmagic-for-woocommerce'),
        value: 'draft',
      },
      {
        label: () => __('Published', 'shopmagic-for-woocommerce'),
        value: 'publish',
      },
      {
        label: () => __('Trashed', 'shopmagic-for-woocommerce'),
        value: 'trash',
      },
    ]"
    :placeholder="__('Select status', 'shopmagic-for-woocommerce')"
    class="w-[215px]"
    clearable
    @update:value="$emit('update:value', $event)"
  />
</template>
