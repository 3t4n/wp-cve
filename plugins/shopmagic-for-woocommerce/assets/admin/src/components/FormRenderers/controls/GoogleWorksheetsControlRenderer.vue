<script lang="ts" setup>
import { NButton, NCheckbox, NInput, NSelect, useMessage } from "naive-ui";
import {
  isScoped,
  type Layout,
  mapDispatchToControlProps,
  mapStateToLayoutProps,
  resolveSchema,
} from "@jsonforms/core";
import { type LayoutProps, rendererProps, useControl } from "@jsonforms/vue";
import { useVanillaLayout } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { type Ref, computed, ref, unref, watch } from "vue";
import { __ } from "@/plugins/i18n";
import { get } from "@/_utils";

const useJsonFormsLayout = (props: LayoutProps) => {
  const { control, ...other } = useControl(props, mapStateToLayoutProps, mapDispatchToControlProps);
  return { layout: control, ...other };
};

const props = defineProps(rendererProps<Layout>());
const { handleChange, layout } = useVanillaLayout(useJsonFormsLayout(props));

const spreadsheetRef = ref<string | null>(layout.value.data["spreadsheet"] || null);
const worksheetRef = ref<string | null>(layout.value.data["spreadsheet_tab"] || null);
const worksheetsRef = ref<string[]>([]);
const rowsHeader = ref<string[]>([]);
const useHeader = ref<boolean>(layout.value.data["use_header"] || false);
const rowsRef = ref<string[]>(layout.value.data["values"] || []);

const message = useMessage();

populateHeaderRows(useHeader.value);

const groupSchema = computed(() => {
  if (!isScoped(layout.value.uischema)) return;
  return resolveSchema(layout.value.schema, layout.value.uischema.scope, layout.value.schema);
});

async function getWorksheets(spreadsheet: string|Ref<string>|null) {
  spreadsheet = unref(spreadsheet);

  if ( spreadsheet === null ) {
    return;
  }

  worksheetRef.value = null;
  const data = await get<string[]>(`/extensions/sheets/${spreadsheet}/worksheets`);
  worksheetsRef.value = data.map((item) => ({
    label: item,
    value: item,
  }));

  if (data.length === 1) {
    worksheetRef.value = data[0];
  }
}

async function populateHeaderRows(useRealLabels: boolean) {
  if (useRealLabels && (worksheetRef.value === null || spreadsheetRef.value === null)) {
    message.warning(
      __("Please select a spreadsheet and a worksheet first", "shopmagic-for-woocommerce"),
      { duration: 3000 },
    );
    return;
  }

  useHeader.value = useRealLabels;
  rowsHeader.value = [];

  if (useRealLabels) {
    rowsHeader.value = await get<string[]>(
      `/extensions/sheets/${spreadsheetRef.value}/worksheets/${worksheetRef.value}`,
    );
  } else {
    rowsRef.value.length === 0 ?
      addRow() :
      rowsRef.value.forEach(addRow);
  }
}

function addRow() {
  const A_CHAR_CODE = 65;
  rowsHeader.value = rowsHeader.value || [];
  const nextCharCode =
    rowsHeader.value.length > 0
      ? (rowsHeader.value.at(-1) as string).charCodeAt(0) + 1
      : A_CHAR_CODE;
  const nextLabel = String.fromCharCode(nextCharCode);

  rowsHeader.value.push(nextLabel);
}

function updateDynamicRows(rowIndex: number, value: string) {
  rowsRef.value[rowIndex] = value;
}

watch(spreadsheetRef, (spreadsheet) => {
  handleChange("spreadsheet", spreadsheet);
  worksheetRef.value = null
});

watch(worksheetRef, (worksheet) => {
  handleChange("spreadsheet_tab", worksheet);
});

watch(useHeader, (newHeader, oldHeader) => {
  if (newHeader !== oldHeader) {
    handleChange("use_header", newHeader);
  }
});

watch(
  rowsRef,
  (rows) => {
    handleChange("values", rows);
  },
  { deep: true },
);
</script>
<template>
  <FieldWrapper :label="groupSchema.properties['spreadsheet'].title">
    <NSelect
      v-model:value="spreadsheetRef"
      :options="
        groupSchema.properties['spreadsheet'].oneOf.map((item) => {
          return {
            label: item.title,
            value: item.const,
          };
        })
      "
      filterable
      @update:value="getWorksheets"
    />
  </FieldWrapper>
  <FieldWrapper :label="groupSchema.properties['spreadsheet_tab'].title">
    <NSelect v-model:value="worksheetRef" :options="worksheetsRef" @update:show="(show: boolean) => show && worksheetsRef.length === 0 && getWorksheets(spreadsheetRef)" filterable />
  </FieldWrapper>
  <FieldWrapper :show-label="false">
    <NCheckbox
      v-model:checked="useHeader"
      :label="groupSchema.properties['use_header'].title"
      @update:checked="populateHeaderRows"
    />
  </FieldWrapper>
  <div class="flex flex-col gap-y-4">
    <FieldWrapper v-for="(row, i) in rowsHeader" :key="i" :label="row">
      <NInput :default-value="rowsRef[i]" @update:value="(v) => updateDynamicRows(i, v)" />
    </FieldWrapper>
    <NButton v-if="!useHeader" @click="addRow">Add field</NButton>
  </div>
</template>
