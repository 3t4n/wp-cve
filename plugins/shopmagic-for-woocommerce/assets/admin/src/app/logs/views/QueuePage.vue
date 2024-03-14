<script lang="ts" setup>
import { NButton, NIcon, NH1, NPopover } from "naive-ui";
import { CloseOutline } from "@vicons/ionicons5";
import DataTable from "@/components/Table/DataTable.vue";
import { h, reactive } from "vue";
import { queueTableColumns } from "../data/queueTable";
import { useQueueStore } from "@/app/logs/queueStore";
import { storeToRefs } from "pinia";
import { __ } from "@/plugins/i18n";

const store = useQueueStore();
const { getQueue, cancelQueue } = store;
const { queue, queueTotal, loading, error } = storeToRefs(store);

getQueue();

const columns = [
  ...queueTableColumns,
  {
    key: "actions",
    title: () => __("Actions", "shopmagic-for-woocommerce"),
    render: ({ id }) =>
      h(
        NPopover,
        {
          trigger: "hover",
        },
        {
          trigger: () =>
            h(
              NButton,
              {
                quaternary: true,
                type: "error",
                size: "small",
                onClick: () => cancelQueue(id),
              },
              { icon: () => h(NIcon, () => h(CloseOutline)) },
            ),
          default: () => __("Cancel", "shopmagic-for-woocommerce"),
        },
      ),
  },
];

const tableFilters = reactive<{ automation: number | null }>({
  automation: null,
});

function filterAutomations(automationId: number) {
  tableFilters.automation = automationId;
}
</script>
<template>
  <div class="flex gap-4">
    <NH1>{{ __("Queue", "shopmagic-for-woocommerce") }}</NH1>
  </div>
  <DataTable
    :columns="columns"
    :data="queue"
    :error="error"
    :filters="tableFilters"
    :loading="loading"
    :total-count="queueTotal || 0"
    @update:data="getQueue"
  >
  </DataTable>
</template>
