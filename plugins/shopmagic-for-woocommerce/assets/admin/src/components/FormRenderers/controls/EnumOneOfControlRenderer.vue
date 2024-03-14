<script lang="ts" setup>
import { NSelect, type SelectRenderTag } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { rendererProps, useJsonFormsOneOfEnumControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { ref } from "vue";

const props = defineProps(rendererProps<ControlElement>());
const { control, controlWrapper, onChange } = useVanillaControl(
  useJsonFormsOneOfEnumControl(props),
);
const controlRef = ref(control);
const renderTag: SelectRenderTag = ({ option }) => {
  // Value may be string or int. Compare loosely
  const label = controlRef.value.options?.find((formOption) => formOption.value == option.value);
  return label?.label || option.label;
};
</script>
<template>
  <FieldWrapper v-bind="controlWrapper">
    <NSelect
      :id="control.id + '-input'"
      :consistent-menu-width="false"
      :default-value="control.schema.default"
      :disabled="!control.enabled"
      :filterable="control.schema.oneOf.length > 5"
      :multiple="control.schema?.uniqueItems"
      :options="control.options"
      :placeholder="control.schema.examples[0]"
      :readonly="control.schema.readOnly"
      :value="control.data"
      :render-tag="renderTag"
      class="min-w-[140px] w-full"
      @update:value="onChange"
    />
  </FieldWrapper>
</template>
