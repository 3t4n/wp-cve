<script lang="ts" setup>
import { NSelect } from "naive-ui";
import { langToSelect } from "@/_utils/langToSelect";
</script>
<template>
  <NSelect
    :options="langToSelect()"
    :placeholder="__('Select language', 'shopmagic-for-woocommerce')"
    clearable
    filterable
  />
</template>
