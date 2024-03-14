<script lang="ts" setup>
import { useClientsStore } from "@/stores/clients";
import DynamicSearch from "@/components/Select/DynamicSearch.vue";

const { getClientByEmail, fetchItems } = useClientsStore();

const loadOptions = (query: string) =>
  getClientByEmail(query).map((item) => ({
    label: item.email,
    value: item.id,
  }));
</script>
<template>
  <DynamicSearch
    :fetch="fetchItems"
    :load-options="loadOptions"
    :placeholder="__('Search client', 'shopmagic-for-woocommerce')"
  />
</template>
