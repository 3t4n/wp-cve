<script lang="ts" setup>
import { NDataTable, NP, NSpace } from "naive-ui";
import { computed, reactive, ref, unref, type VNodeChild, watch } from "vue";
import type { Filters } from "@/composables/useFilter";
import type { SortOptions } from "@/composables/useSorter";
import type { Query, SortQuery } from "@/_utils";
import { __, sprintf } from "@/plugins/i18n";

const emit = defineEmits<{
  (e: "update:data", query: Query): void;
  (e: "update:checked-row-keys", keys: Array<string | number>): void;
}>();

type TableProps = {
  filters?: Filters;
  error?: Error | null;
  data: any[];
  loading?: boolean;
  showPagination?: boolean;
  columns: any[];
  checkedRowKeys?: Array<string | number>;
  totalCount?: number;
  renderExpandIcon?: () => VNodeChild;
};

const props = withDefaults(defineProps<TableProps>(), {
  showPagination: true,
  loading: false,
  totalCount: 0,
  checkedRowKeys: () => [],
  error: null
});

const pageSize = ref(20);

const pagination = reactive({
  displayOrder: ["size-picker", "pages"],
  pageSize: computed(() => pageSize.value),
  page: 1,
  itemCount: computed(() => props.totalCount),
  pageCount: computed(() => (props.totalCount || 0) / pageSize.value),
  showSizePicker: true,
  pageSizes: [20, 60, 100],
  prefix: ({ itemCount }) => sprintf(__("Total items %s", "shopmagic-for-woocommerce"), itemCount),
});

const tableSorter = ref<SortQuery | null>(null);

function emitUpdate() {
  const query = {
    filters: props.filters,
    order: unref(tableSorter),
    page: pagination.page,
    pageSize: pageSize.value,
  };
  emit("update:data", query);
}

if (props.filters) {
  watch(props.filters, emitUpdate);
}

function handlePageUpdate(page: number) {
  pagination.page = page;
  emitUpdate();
}

function handlePageSizeUpdate(size: number) {
  pagination.page = 1;
  pageSize.value = size;
  emitUpdate();
}

function handleSorterUpdate(sorter: SortOptions) {
  if (sorter.order === false) {
    tableSorter.value = null;
  } else {
    tableSorter.value = {
      [sorter.columnKey]: sorter.order,
    };
  }
  pagination.page = 1;
  emitUpdate();
}

const showBulkActions = computed(() => props.checkedRowKeys?.length !== 0);

function handleCheckRows(keys: Array<string | number>) {
  emit("update:checked-row-keys", keys);
}
</script>
<template>
  <NSpace class="my-4">
    <slot name="filters" />
  </NSpace>
  <div v-if="showBulkActions && $slots.bulkActions">
    <NP>Select bulk actions</NP>
    <NSpace class="my-4">
      <slot name="bulkActions" />
    </NSpace>
  </div>
  <NDataTable
    :pagination="showPagination ? pagination : false"
    :row-key="(k) => k.id"
    remote
    v-bind="$props"
    @update:sorter="handleSorterUpdate"
    @update:page="handlePageUpdate"
    @update:page-size="handlePageSizeUpdate"
    @update:checked-row-keys="handleCheckRows"
  >
    <template v-if="error" #empty>
      There was an error when loading data!
      {{ error.toString() }}
    </template>
  </NDataTable>
</template>
