<script lang="ts" setup>
import { NSelect } from "naive-ui";
import { type ControlElement } from "@jsonforms/core";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "@/components/FormRenderers/controls/FieldWrapper.vue";

const props = defineProps(rendererProps<ControlElement>());

const { controlWrapper, onChange } = useVanillaControl(useJsonFormsControl(props));
</script>
<template>
  <FieldWrapper v-bind="controlWrapper">
    <NSelect
      :show="false"
      :show-arrow="false"
      clearable
      filterable
      multiple
      tag
      @update:value="onChange"
    />
  </FieldWrapper>
</template>
