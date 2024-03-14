<script lang="ts" setup>
import { NButton, NH1, NSelect } from "naive-ui";
import { reactive, ref } from "vue";
import DataTable from "@/components/Table/DataTable.vue";
import { marketingListsTableColumns } from "../data/table";
import { useMarketingStore } from "@/app/marketing-lists/store";
import { storeToRefs } from "pinia";
import StatusSelect from "@/app/automations/components/StatusSelect.vue";

const store = useMarketingStore();
const { lists, loading, listsTotal, error } = storeToRefs(store);
const { fetchItems, removeMultiple } = store;

fetchItems();

const tableFilters = reactive({
  status: null,
  type: null,
});

const bulkAction = ref<string | null>(null);
const checkedRows = ref([]);

function executeBulkAction() {
  if (bulkAction.value === "delete") {
    try {
      removeMultiple(checkedRows.value);
    } catch (e) {
      console.error(e);
    } finally {
      checkedRows.value = [];
    }
  }
}
</script>
<template>
  <div class="flex flex-wrap items-center gap-4">
    <NH1 class="m-0">{{ __("Marketing Lists", "shopmagic-for-woocommerce") }}</NH1>
    <RouterLink :to="{ name: 'marketing-list', params: { id: 'new' } }">
      <NButton type="primary">
        {{ __("Add new", "shopmagic-for-woocommerce") }}
      </NButton>
    </RouterLink>
  </div>
  <DataTable
    v-model:checked-row-keys="checkedRows"
    :columns="marketingListsTableColumns"
    :data="lists || []"
    :error="error"
    :filters="tableFilters"
    :loading="loading"
    :total-count="listsTotal || 0"
    @update:data="fetchItems"
  >
    <template #filters>
      <StatusSelect @update:value="tableFilters.status = $event" />
      <NSelect
        :options="[
          {
            label: () => __('Opt-in', 'shopmagic-for-woocommerce'),
            value: 'opt_in',
          },
          {
            label: () => __('Opt-out', 'shopmagic-for-woocommerce'),
            value: 'opt_out',
          },
        ]"
        :placeholder="__('Select type', 'shopmagic-for-woocommerce')"
        class="w-[215px]"
        clearable
        @update:value="tableFilters.type = $event"
      />
    </template>
    <template #bulkActions>
      <NSelect
        v-model:value="bulkAction"
        :options="[
          {
            label: () => __('Delete', 'shopmagic-for-woocommerce'),
            value: 'delete',
          },
        ]"
        class="w-[320px]"
      />
      <NButton @click="executeBulkAction">
        {{ __("Execute", "shopmagic-for-woocommerce") }}
      </NButton>
    </template>
  </DataTable>
</template>
