<script lang="ts" setup>
import { NH1, NTabPane, NTabs } from "naive-ui";
import { trackerPerAutomation, trackerPerCustomer } from "@/data/tables/tracker";
import { reactive } from "vue";
import type { Filters } from "@/composables/useFilter";
import DataTable from "@/components/Table/DataTable.vue";
import { useTrackerStore } from "@/app/marketing-lists/trackerStore";
import { storeToRefs } from "pinia";

const store = useTrackerStore();
const { automations, customers } = storeToRefs(store);

const tableFilters = reactive<Filters>({
  email: null,
  list: null,
  type: null,
});
</script>
<template>
  <NH1>{{ __("Tracked emails", "shopmagic-for-woocommerce") }}</NH1>
  <NTabs>
    <NTabPane :tab="__('Per Automation', 'shopmagic-for-woocommerce')" name="automation">
      <DataTable
        :columns="trackerPerAutomation"
        :data="automations || []"
        :error="null"
        :filters="tableFilters"
        :loading="automations === undefined"
        :total-count="automations?.length || 0"
      />
    </NTabPane>
    <NTabPane :tab="__('Per Customer', 'shopmagic-for-woocommerce')" name="customer">
      <DataTable
        :columns="trackerPerCustomer"
        :data="customers || []"
        :error="null"
        :filters="tableFilters"
        :loading="customers === undefined"
        :total-count="customers?.length || 0"
      />
    </NTabPane>
  </NTabs>
</template>
