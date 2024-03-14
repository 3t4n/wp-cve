<script lang="ts" setup>
import { NButton, NH1, NInput, NSelect } from "naive-ui";
import { subscribersTableColumns } from "../data/subscribers";
import { reactive, ref } from "vue";
import type { Filters } from "@/composables/useFilter";
import DataTable from "@/components/Table/DataTable.vue";
import DynamicSearch from "@/components/Select/DynamicSearch.vue";
import { useSubscribersStore } from "@/app/marketing-lists/subscribersStore";
import { storeToRefs } from "pinia";
import { useMarketingStore } from "@/app/marketing-lists/store";
import { useDebounceFn } from "@vueuse/core";
import { useRoute } from "vue-router";

const store = useSubscribersStore();
const { subscribers, subscribersTotal, error, loading } = storeToRefs(store);
const { getSubscribers, deleteSubscribers } = store;
const { getListsByName } = useMarketingStore();

const tableFilters = reactive<Filters>({
  email: null,
  list: null,
  type: null,
  active: null,
});

const route = useRoute();
const selectedListDefaultValue = ref<number | null>(null);
if (route.query.list) {
  const numericValue = parseInt(route.query.list?.toString());
  if (Number.isInteger(numericValue)) {
    getSubscribers({ filters: { list: numericValue } });
    tableFilters.list = numericValue;
    selectedListDefaultValue.value = numericValue;
  }
} else {
  getSubscribers();
}

const filterClients = useDebounceFn((v) => (tableFilters.email = v), 500, {
  maxWait: 5000,
});

function filterLists(value: number | null) {
  tableFilters.list = value;
}

function filterType(value: number | null) {
  tableFilters.type = value;
}

function filterActive(value: number | null) {
  tableFilters.active = value;
}

const loadOptions = async (query: string) => {
  const lists = await getListsByName(query);
  return lists.map((item) => ({
    label: item.name,
    value: item.id,
  }));
};

const bulkAction = ref<string | null>(null);
const checkedRows = ref<number[]>([]);

function executeBulkAction() {
  if (bulkAction.value === "delete") {
    try {
      console.log(checkedRows.value);
      deleteSubscribers(checkedRows.value);
    } catch (e) {
      console.error(e);
    } finally {
      checkedRows.value = [];
    }
  }
}
</script>
<template>
  <NH1>{{ __("Subscribers", "shopmagic-for-woocommerce") }}</NH1>
  <DataTable
    :columns="subscribersTableColumns"
    :data="subscribers"
    :error="error"
    :filters="tableFilters"
    :loading="loading"
    :total-count="subscribersTotal"
    v-model:checked-row-keys="checkedRows"
    @update:data="getSubscribers"
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
      <NInput
        :placeholder="__('Search email', 'shopmagic-for-woocommerce')"
        clearable
        @update:value="(v) => filterClients(v)"
      />
      <DynamicSearch
        :default-value="selectedListDefaultValue"
        :load-options="loadOptions"
        :placeholder="__('Search list', 'shopmagic-for-woocommerce')"
        @update:value="filterLists"
      />
      <NSelect
        :options="[
          {
            label: () => __('Subscribed', 'shopmagic-for-woocommerce'),
            value: 1,
          },
          {
            label: () => __('Unsubscribed', 'shopmagic-for-woocommerce'),
            value: 0,
          },
        ]"
        :placeholder="__('Select status', 'shopmagic-for-woocommerce')"
        class="w-[215px]"
        clearable
        @update:value="filterActive"
      />
      <NSelect
        :options="[
          {
            label: () => __('Opt-in', 'shopmagic-for-woocommerce'),
            value: 1,
          },
          {
            label: () => __('Opt-out', 'shopmagic-for-woocommerce'),
            value: 0,
          },
        ]"
        :placeholder="__('Select list type', 'shopmagic-for-woocommerce')"
        class="w-[215px]"
        clearable
        @update:value="filterType"
      />
    </template>
  </DataTable>
</template>
