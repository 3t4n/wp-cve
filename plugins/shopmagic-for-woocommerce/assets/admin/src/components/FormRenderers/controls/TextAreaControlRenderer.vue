<script lang="ts" setup>
import type { ControlElement } from "@jsonforms/core";
import { NInput } from "naive-ui";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";

const props = defineProps(rendererProps<ControlElement>());

const { control, controlWrapper, onChange } = useVanillaControl(useJsonFormsControl(props));
</script>
<template>
  <FieldWrapper v-bind="controlWrapper">
    <NInput
      :id="control.id + '-input'"
      :default-value="control.schema.default"
      :disabled="!control.enabled"
      :placeholder="control.schema.examples[0]"
      :readonly="control.schema.readOnly"
      :value="control.data"
      type="textarea"
      @update:value="onChange"
    />
  </FieldWrapper>
</template>
