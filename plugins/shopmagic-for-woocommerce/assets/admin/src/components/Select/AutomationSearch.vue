<script lang="ts" setup>
import { findAutomationsBy } from "@/app/automations/store";
import DynamicSearch from "@/components/Select/DynamicSearch.vue";
import type { SelectOption } from "naive-ui";

defineProps<{
  defaultOptions?: SelectOption[];
}>();
const loadOptions = async (query: string) => {
  const automations = await findAutomationsBy({ filters: { name: query } });

  return automations.map((automation) => ({
    label: automation?.name,
    value: automation?.id,
  }));
};
</script>
<template>
  <DynamicSearch
    :default-options="defaultOptions"
    :load-options="loadOptions"
    :placeholder="__('Search automation', 'shopmagic-for-woocommerce')"
  />
</template>
