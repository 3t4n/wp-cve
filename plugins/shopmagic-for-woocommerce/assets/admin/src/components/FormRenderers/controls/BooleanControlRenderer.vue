<template>
  <FieldWrapper :show-label="false" v-bind="controlWrapper">
    <NCheckbox
      :id="control.id + '-input'"
      :checked="!!control.data"
      :checked-value="true"
      :default-checked="control.schema.default"
      :disabled="!control.enabled"
      :label="control.label"
      :unchecked-value="false"
      @blur="isFocused = false"
      @focus="isFocused = true"
      @update:checked="onChange"
    />
  </FieldWrapper>
</template>

<script lang="ts">
import { NCheckbox } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";

export default defineComponent({
  name: "BooleanControlRenderer",
  components: {
    FieldWrapper,
    NCheckbox,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsControl(props), (target) => target);
  },
});
</script>
