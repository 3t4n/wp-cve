<script lang="ts" setup>
import { NSelect } from "naive-ui";
import { computed, toRef } from "vue";
import EditableCard from "./EditableCard.vue";
import type { EventConfig } from "@/types/automationConfig";
import { useSingleAutomation } from "../singleAutomation";
import JsonForm from "@/components/JsonForm.vue";
import { useAutomationEvent } from "@/composables/useAutomationEvent";
import { useFuzzySearch } from "@/composables/useFuzzySearch";
import type { Automation } from "@/types/automation";

const props = defineProps<{
  events: EventConfig[];
  automation: Automation;
}>();

const store = useSingleAutomation();
const automation = toRef(props, "automation");

const currentEvent = computed(() => {
  return props.events?.find((e) => e.value === automation.value?.event.name);
});

const eventName = computed<string | null>({
  get: () => automation.value?.event.name || null,
  set: (event: string | null) => {
    store.$patch((state) => {
      state.automation.event.name = event;
    });
  },
});

const eventValues = computed(() => automation.value?.event.settings || {});

const { onChange } = useAutomationEvent();

onChange((_, prev) => {
  if (prev !== null) {
    automation.value.event.settings = {};
  }
});

function updateData({ data /** errors */ }) {
  store.$patch((state) => {
    if ( data instanceof Array ) {
      // Somehow sometimes data is passes as Array proxy, not an object.
      state.automation.event.settings = Object.assign({}, data);
    } else {
      state.automation.event.settings = data;
    }
  });
}

const { search, matches, renderLabel, renderTag } = useFuzzySearch(props.events);
</script>
<template>
  <EditableCard :hide-default="false" :title="__('Event', 'shopmagic-for-woocommerce')">
    <NSelect
      v-model:value="eventName"
      :loading="events.length === 0"
      :options="matches"
      :placeholder="__('Select event', 'shopmagic-for-woocommerce')"
      :render-label="renderLabel"
      :render-tag="renderTag"
      filterable
      remote
      @search="search"
    />
    <JsonForm
      v-if="typeof currentEvent?.settings !== 'undefined'"
      :data="eventValues"
      :schema="currentEvent.settings"
      class="my-4"
      @change="updateData"
    />
  </EditableCard>
</template>
