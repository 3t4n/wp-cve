<script lang="ts" setup>
import { NButton, NInput, NModal, NSkeleton, NSpace, NTag } from "naive-ui";
import { computed, ref, unref } from "vue";
import type { PlaceholderConfig } from "@/types/automationConfig";
import { storeToRefs } from "pinia";
import { useAutomationResourcesStore } from "../resourceStore";
import { useAutomationEvent } from "@/composables/useAutomationEvent";
import { useSingleAutomation } from "@/app/automations/singleAutomation";
import JsonForm from "@/components/JsonForm.vue";

const { placeholders } = storeToRefs(useAutomationResourcesStore());
const { automation } = storeToRefs(useSingleAutomation());
const { fetchAvailablePlaceholders } = useAutomationResourcesStore();

const { onChange } = useAutomationEvent();

onChange(fetchAvailablePlaceholders);
fetchAvailablePlaceholders(automation.value?.event.name);

const currentPlaceholder = ref<PlaceholderConfig | null>(null);
const parameters = ref<Record<string, string>>({});
const modalShown = ref(false);
const searchString = ref<string | null>(null);

function showModal(placeholder: PlaceholderConfig) {
  modalShown.value = true;
  parameters.value = {};
  currentPlaceholder.value = placeholder;
}

const filteredPlaceholders = computed(() => {
  if (searchString.value !== null) {
    return placeholders.value.filter((placeholder) =>
      placeholder.label.includes(searchString.value),
    );
  }
  return placeholders.value;
});

function randomInt(min: number, max: number): number {
  return Math.floor(Math.random() * (max - min) + min);
}

const placeholderText = ref();

function copyPlaceholder() {
  placeholderText.value.select();
  document.execCommand("copy");
  modalShown.value = false;
}

const rawLabel = (label: string) => `{{ ${unref(label)} }}`;

const labelWithParams = computed(() => {
  const paramsString = Object.entries(parameters.value)
    .map(([key, value]) => `${key}: '${value}'`)
    .join(", ");

  if (paramsString.length === 0) return `{{ ${currentPlaceholder.value?.label} }}`;

  return `{{ ${currentPlaceholder.value?.label} | ${paramsString} }}`;
});

function updateParameters({ data }) {
  parameters.value = data;
}
</script>
<template>
  <NInput
    v-model:value="searchString"
    :placeholder="__('Search for placeholders...', 'shopmagic-for-woocommerce')"
    class="mb-4"
  />
  <div class="flex flex-wrap gap-2 max-h-[65vh] overflow-clip overflow-y-scroll">
    <NSpace v-if="!placeholders || placeholders.length === 0" vertical>
      <NSkeleton
        v-for="n in 6"
        :key="n"
        :sharp="false"
        :width="randomInt(100, 250)"
        height="28px"
        size="medium"
      />
    </NSpace>
    <NTag
      v-for="(placeholder, i) in filteredPlaceholders"
      v-else
      :key="i"
      class="hover:bg-gray-200 cursor-pointer"
      round
      @click="showModal(placeholder)"
    >
      {{ rawLabel(placeholder.label) }}
    </NTag>
  </div>
  <NModal
    v-model:show="modalShown"
    :title="rawLabel(currentPlaceholder?.label)"
    class="w-[600px]"
    preset="card"
    size="huge"
  >
    <p v-html="currentPlaceholder?.description"></p>
    <JsonForm
      v-if="currentPlaceholder"
      :data="parameters"
      :schema="currentPlaceholder?.fields"
      validation-mode="NoValidation"
      @change="updateParameters"
    />
    <template #footer>
      <NInput
        ref="placeholderText"
        v-model:value="labelWithParams"
        class="w-full justify-center text-center"
        readonly
        size="large"
      />
      <NButton :block="true" secondary @click="copyPlaceholder"
        >{{ __("Copy placeholder and close", "shopmagic-for-woocommerce") }}
      </NButton>
    </template>
  </NModal>
</template>
