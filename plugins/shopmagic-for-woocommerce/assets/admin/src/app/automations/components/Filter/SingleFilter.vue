<script lang="ts" setup>
import { NButton, NIcon, NSelect } from "naive-ui";
import { CloseOutline } from "@vicons/ionicons5";
import { computed, toRaw } from "vue";
import type { FilterConfig } from "@/types/automationConfig";
import { storeToRefs } from "pinia";
import { useAutomationResourcesStore } from "../../resourceStore";
import { useSingleAutomation } from "../../singleAutomation";
import JsonForm from "@/components/JsonForm.vue";
import { useFuzzySearch } from "@/composables/useFuzzySearch";

const { filters } = storeToRefs(useAutomationResourcesStore());
const { getFilter } = useAutomationResourcesStore();
const { automation } = storeToRefs(useSingleAutomation());
const { insertFilter } = useSingleAutomation();

const currentFilter = computed<FilterConfig | undefined>(() => getFilter(filterValue));

const props = defineProps<{
  id: number;
  groupId: number;
}>();
const emit = defineEmits<{ (e: "remove", id: number): void }>();

const filterValue = computed<string>({
  get: () => automation.value.filters[props.groupId][props.id]?.id || null,
  set: (filter: string) => {
    automation.value.filters[props.groupId][props.id].id = filter;
  },
});

function maybeResetCondition() {
  useSingleAutomation().$patch((state) => {
    const originalFilter = toRaw(state.automation.filters[props.groupId][props.id]);
    const keys = Object.keys(originalFilter).filter((k) => k !== "id");
    const emptyFilter = keys.reduce((previousValue, currentValue) => {
      originalFilter[currentValue] = null;
      return previousValue;
    }, originalFilter);
    Object.assign(state.automation.filters[props.groupId][props.id], emptyFilter);
  });
}

const filterValues = computed(() => automation.value?.filters[props.groupId][props.id] || {});

function updateFilters({ data /** errors */ }) {
  useSingleAutomation().$patch((state) => {
    Object.assign(state.automation.filters[props.groupId][props.id], data);
  });
}

const { search, renderLabel, renderTag, matches } = useFuzzySearch(filters);
</script>
<template>
  <div class="flex gap-4 items-center">
    <NSelect
      v-model:value="filterValue"
      :consistent-menu-width="false"
      :options="matches"
      :placeholder="__('Select filter', 'shopmagic-for-woocommerce')"
      :render-label="renderLabel"
      :render-tag="renderTag"
      class="max-w-[320px] basis-1/2 grow"
      filterable
      @search="search"
      @update:value="maybeResetCondition"
    >
      <template #empty>
        {{ __("Select any event to show available filters.", "shopmagic-for-woocommerce") }}
      </template>
    </NSelect>
    <JsonForm
      v-if="typeof currentFilter?.settings !== 'undefined'"
      :data="filterValues"
      :schema="currentFilter.settings"
      layout="horizontal"
      @change="updateFilters"
    />
    <NButton class="ml-auto" tertiary type="info" @click="insertFilter(groupId)">
      {{ __("And", "shopmagic-for-woocommerce") }}
    </NButton>
    <NButton tertiary type="error" @click="emit('remove', props.id)">
      <NIcon>
        <CloseOutline />
      </NIcon>
    </NButton>
  </div>
</template>
