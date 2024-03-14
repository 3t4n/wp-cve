<script lang="ts" setup>
import DataTable from "@/components/Table/DataTable.vue";
import { ref } from "vue";
import { automationSubTable } from "@/app/automations/data/subtable";
import type { Automation } from "@/types/automation";
import { get } from "@/_utils";

const props = defineProps<{
  automation: Automation;
}>();

const automations = ref([]);

get<Automation[]>(props.automation._links.children.href).then((a) => {
  automations.value = a;
});

const checkedRows = ref([]);
</script>
<template>
  <DataTable
    :show-pagination="false"
    v-model:checked-row-keys="checkedRows"
    :columns="automationSubTable"
    :data="automations"
  />
</template>
