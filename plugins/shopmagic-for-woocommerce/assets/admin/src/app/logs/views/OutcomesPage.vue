<script lang="ts" setup>
import { NButton, NH1, NSelect } from "naive-ui";
import { reactive, ref } from "vue";
import { outcomeColumns } from "../data/table";
import type { Filters } from "@/composables/useFilter";
import AutomationSearch from "@/components/Select/AutomationSearch.vue";
import ClientSearch from "@/components/Select/ClientSearch.vue";
import DataTable from "@/components/Table/DataTable.vue";
import { useOutcomesStore } from "@/app/logs/store";
import { storeToRefs } from "pinia";

const store = useOutcomesStore();
const { outcomes, loading, count } = storeToRefs(store);
const { deleteOutcomes } = store;

const tableFilters = reactive<Filters>({
  status: null,
  action: null,
  user: null,
  automation: null,
});

function filterStatus(value: string) {
  tableFilters.status = value;
}

function filterClients(value: string) {
  if (!value) {
    tableFilters.user = null;
  } else {
    tableFilters.user = value;
  }
}

function filterAutomations(value: string | number | boolean) {
  if (!value) {
    tableFilters.automation = null;
  } else {
    tableFilters.automation = parseInt(value);
  }
}

const bulkAction = ref<string | null>(null);
const checkedRows = ref([]);

function executeBulkAction() {
  if (bulkAction.value === "delete") {
    try {
      deleteOutcomes(checkedRows.value);
    } catch (e) {
      console.error(e);
    } finally {
      checkedRows.value = [];
    }
  }
}
</script>
<template>
  <div class="flex gap-4">
    <NH1>{{ __("Outcomes", "shopmagic-for-woocommerce") }}</NH1>
  </div>
  <DataTable
    :columns="outcomeColumns"
    :data="outcomes || []"
    :error="null"
    :filters="tableFilters"
    :loading="loading"
    :total-count="count || 0"
    v-model:checked-row-keys="checkedRows"
    @update:data="store.fetch"
  >
    <template #bulkActions>
      <NSelect
        v-model:value="bulkAction"
        :options="[
          {
            label: __('Delete', 'shopmagic-for-woocommerce'),
            value: 'delete',
          },
        ]"
        class="w-[320px]"
      />
      <NButton @click="executeBulkAction">
        {{ __("Execute", "shopmagic-for-woocommerce") }}
      </NButton>
    </template>
    <template #filters>
      <NSelect
        :options="[
          {
            label: () => __('Completed', 'shopmagic-for-woocommerce'),
            value: 'completed',
          },
          {
            label: () => __('Failed', 'shopmagic-for-woocommerce'),
            value: 'failed',
          },
        ]"
        :placeholder="__('Select status', 'shopmagic-for-woocommerce')"
        class="w-[215px]"
        clearable
        @update:value="filterStatus"
      />
      <AutomationSearch @update:value="filterAutomations" />
      <ClientSearch @update:value="filterClients" />
    </template>
  </DataTable>
</template>
