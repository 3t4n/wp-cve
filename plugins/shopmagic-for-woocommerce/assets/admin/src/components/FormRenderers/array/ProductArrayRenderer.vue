<script lang="ts" setup>
import { NSelect, NText } from "naive-ui";
import { type ControlElement } from "@jsonforms/core";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import { useDebounceFn } from "@vueuse/core";
import FieldWrapper from "@/components/FormRenderers/controls/FieldWrapper.vue";
import { ref, watchEffect } from "vue";
import { useWpFetch } from "@/composables/useWpFetch";

const props = defineProps(rendererProps<ControlElement>());

const { controlWrapper, control, onChange } = useVanillaControl(useJsonFormsControl(props));

const selectOptions = ref([]);
const loading = ref(false);
const initialized = ref(false);

watchEffect(() => {
  if (Array.isArray(control.value.data) && control.value.data.length !== 0 && !initialized.value) {
    initialized.value = true;
    loading.value = true;
    useWpFetch(`/products?include=${control.value.data.join(",")}`)
      .get()
      .then(({ data }) => {
        selectOptions.value = data.value;
        loading.value = false;
      });
  }
});

const debouncedSearch = useDebounceFn(
  (query) => {
    loading.value = true;
    if (!query.length) {
      selectOptions.value = [];
      loading.value = false;
      return;
    }
    useWpFetch(`/products/search?s=${query}`)
      .get()
      .then(({ data }) => {
        selectOptions.value = data.value;
        loading.value = false;
      });
  },
  350,
  { maxWait: 5000 },
);

const showEmpty = ref(false);

const search = (query: string) => {
  if (query.length < 3) {
    showEmpty.value = true;
  } else {
    showEmpty.value = false;
    debouncedSearch(query);
  }
};
</script>
<template>
  <FieldWrapper v-bind="controlWrapper">
    <NSelect
      :id="control.id + '-input'"
      :consistent-menu-width="false"
      :disabled="!control.enabled"
      :loading="loading"
      :options="selectOptions"
      :placeholder="control.schema.examples[0]"
      :value="control.data"
      class="min-w-[320px]"
      clearable
      filterable
      multiple
      remote
      tag
      @search="search"
      @update:value="onChange"
    >
      <template v-if="showEmpty" #empty>
        <NText depth="3">
          {{
            __(
              "You must enter at least 3 characters to search for product.",
              "shopmagic-for-woocommerce",
            )
          }}
        </NText>
      </template>
    </NSelect>
  </FieldWrapper>
</template>
