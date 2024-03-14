<script lang="ts" setup>
import EditableCard from "./EditableCard.vue";
import { NButton, NIcon, NText } from "naive-ui";
import { AddCircleOutline } from "@vicons/ionicons5";
import SingleFilter from "./Filter/SingleFilter.vue";
import { storeToRefs } from "pinia";
import { useAutomationResourcesStore } from "../resourceStore";
import { useAutomationEvent } from "@/composables/useAutomationEvent";
import { useSingleAutomation } from "@/app/automations/singleAutomation";

const store = useSingleAutomation();
const { removeFilter, addOrFilterGroup } = store;
const { automation, hasFilters } = storeToRefs(store);
const { fetchAvailableFilters } = useAutomationResourcesStore();

const { onChange } = useAutomationEvent();
onChange(fetchAvailableFilters);
</script>
<template>
  <NButton v-if="!hasFilters" block @click="addOrFilterGroup">
    <template #icon>
      <NIcon>
        <AddCircleOutline />
      </NIcon>
    </template>
    {{ __("Add filters", "shopmagic-for-woocommerce") }}
  </NButton>
  <EditableCard v-if="hasFilters" :title="__('Filters', 'shopmagic-for-woocommerce')">
    <div v-for="(filtersGroup, groupId) in automation?.filters" :key="groupId">
      <NText v-if="hasFilters && groupId < 1">
        {{ __("Run actions only if...", "shopmagic-for-woocommerce") }}
      </NText>
      <div v-else class="filters-group-or">
        <span>{{ __("or", "shopmagic-for-woocommerce") }}</span>
      </div>
      <ul>
        <li v-for="(filterId, i) in filtersGroup?.keys()" :key="i">
          <SingleFilter
            :id="filterId"
            :group-id="groupId"
            @remove="(id) => removeFilter(groupId, id)"
          />
        </li>
      </ul>
    </div>
    <template #below>
      <NButton type="primary" @click="addOrFilterGroup">
        <template #icon>
          <NIcon>
            <AddCircleOutline />
          </NIcon>
        </template>
        <span>
          {{ __("New filters group", "shopmagic-for-woocommerce") }}
        </span>
      </NButton>
    </template>
  </EditableCard>
</template>
<style scoped>
.filters-group-or {
  font-weight: bold;
  margin: 8px auto;
  text-align: center;
  text-transform: uppercase;
  color: #bbbbbb;
  position: relative;
}

.filters-group-or::before,
.filters-group-or::after {
  content: "";
  width: calc(50% - 1.5rem);
  position: absolute;
  height: 1px;
  background: #bbbbbb;
  top: 50%;
  transform: translateY(-50%);
}

.filters-group-or::before {
  left: 0;
}

.filters-group-or::after {
  right: 0;
}
</style>
