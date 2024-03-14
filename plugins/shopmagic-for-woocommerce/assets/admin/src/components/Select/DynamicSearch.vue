<script lang="ts" setup>
import type { SelectOption } from "naive-ui";
import { NSelect } from "naive-ui";
import { ref, watchEffect } from "vue";
import { useDebounceFn } from "@vueuse/core";

defineEmits<{
  (e: "update:value", value: string): void;
}>();

const props = defineProps<{
  defaultOptions?: SelectOption[];
  loadOptions: (query: string) => SelectOption[] | Promise<SelectOption[]>;
  fetch?: () => Promise<void>;
  placeholder?: string;
  defaultValue?: any;
}>();

const selectOptions = ref<SelectOption[]>([]);

if (props.defaultOptions) {
  selectOptions.value = props.defaultOptions;
}

const defaultValueRef = ref<number | null>(props.defaultValue);
const debouncedSearch = useDebounceFn(
  (query) => {
    if (!query.length) {
      selectOptions.value = [];
      return;
    }
    const optionsLoader = props.loadOptions(query);
    if (optionsLoader instanceof Promise) {
      optionsLoader.then((options) => {
        selectOptions.value = options;
      });
    } else {
      selectOptions.value = optionsLoader;
    }
  },
  600,
  { maxWait: 5000 },
);

const search = (query: string) => {
  if (neverOpened.value === true) {
    neverOpened.value = false;
  }
  debouncedSearch(query);
};

const neverOpened = ref(true);
const loading = ref(false);

watchEffect(() => {
  loading.value = selectOptions.value.length === 0 && !neverOpened.value;
});

let initialized = false;

function resetSearch(show: boolean) {
  if (show) {
    selectOptions.value = [];
    if (!initialized && props.fetch) {
      props.fetch();
      initialized = true;
    }
  } else {
    loading.value = false;
  }
}
</script>
<template>
  <NSelect
    :loading="loading"
    :options="selectOptions"
    :placeholder="placeholder"
    :default-value="defaultValueRef"
    clearable
    filterable
    remote
    @search="search"
    @update:value="$emit('update:value', $event)"
    @update:show="resetSearch"
  >
    <template #empty>
      <div v-if="loading">
        {{ __("Searching...", "shopmagic-for-woocommerce") }}
      </div>
      <div v-else>
        {{ __("Type to search...", "shopmagic-for-woocommerce") }}
      </div>
    </template>
  </NSelect>
</template>
