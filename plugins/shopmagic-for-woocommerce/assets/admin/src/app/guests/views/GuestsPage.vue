<script lang="ts" setup>
import { NButton, NH1, NSelect } from "naive-ui";
import DataTable from "@/components/Table/DataTable.vue";
import { reactive, ref } from "vue";
import { guestsTableColumns } from "../data/table";
import { useGuestsStore } from "../store";
import { storeToRefs } from "pinia";

const store = useGuestsStore();
const { guests, guestsTotal, loading, error } = storeToRefs(store);
const { getGuests, deleteGuests } = store;
const tableFilters = reactive({});

const bulkAction = ref<string | null>(null);
const checkedRows = ref([]);

function executeBulkAction() {
  if (bulkAction.value === "delete") {
    try {
      deleteGuests(checkedRows.value);
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
    <NH1>{{ __("Guests", "shopmagic-for-woocommerce") }}</NH1>
  </div>
  <DataTable
    :columns="guestsTableColumns"
    :data="guests"
    :error="error"
    :filters="tableFilters"
    :loading="loading"
    :total-count="guestsTotal"
    v-model:checked-row-keys="checkedRows"
    @update:data="getGuests"
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
  </DataTable>
</template>
