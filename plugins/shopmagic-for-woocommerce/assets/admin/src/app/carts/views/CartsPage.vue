<script lang="ts" setup>
import { NButton, NH1, NSelect, NSkeleton, NStatistic } from "naive-ui";
import ShadyCard from "@/components/ShadyCard.vue";
import { cartColumns } from "@/app/carts/data/table";
import DataTable from "@/components/Table/DataTable.vue";
import { useCartsStore } from "@/app/carts/store";
import useSWRV from "@/_utils/swrv";
import { reactive, ref } from "vue";

const store = useCartsStore();

const { data: cartStats } = useSWRV("/analytics/carts/top-stats");

const bulkAction = ref<string | null>(null);
const checkedRows = ref([]);

function executeBulkAction() {
  if (bulkAction.value === "delete") {
    try {
      store.delete(checkedRows.value);
    } catch (e) {
      console.error(e);
    } finally {
      checkedRows.value = [];
    }
  }
}

const tableFilters = reactive({
  status: null,
});
</script>
<template>
  <div class="flex gap-4">
    <NH1>{{ __("Carts", "shopmagic-for-woocommerce") }}</NH1>
  </div>
  <div v-if="cartStats !== undefined" class="flex flex-row gap-4 mb-4">
    <ShadyCard
      v-for="(stat, k) in cartStats.top_stats"
      :key="k"
      content-style="text-align: center;"
    >
      <NStatistic :label="stat?.name">
        <template v-if="stat?.value !== 0" #label>
          <NSkeleton :width="140" text />
        </template>
        <span v-html="stat?.value || 0"></span>
      </NStatistic>
    </ShadyCard>
  </div>
  <DataTable
    v-model:checked-row-keys="checkedRows"
    :columns="cartColumns"
    :data="store.carts"
    :error="null"
    :filters="tableFilters"
    :loading="store.loading"
    :total-count="store.count"
    @update:data="store.fetchItems"
  >
    <template #filters>
      <NSelect
        :options="[
          {
            label: () => __('Recovered', 'shopmagic-for-woocommerce'),
            value: 'recovered',
          },
          {
            label: () => __('Abandoned', 'shopmagic-for-woocommerce'),
            value: 'abandoned',
          },
          {
            label: () => __('Submitted', 'shopmagic-for-woocommerce'),
            value: 'submitted',
          },
          {
            label: () => __('Ordered', 'shopmagic-for-woocommerce'),
            value: 'ordered',
          },
        ]"
        :placeholder="__('Select status', 'shopmagic-for-woocommerce')"
        class="w-[215px]"
        clearable
        @update:value="tableFilters.status = $event"
      />
    </template>
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
