<template>
  <FieldWrapper v-bind="controlWrapper">
    <NCheckboxGroup
      :default-value="[control.schema.default]"
      :value="control.data"
      @update:value="onChange"
    >
      <NCheckbox
        v-for="(checkbox, i) in control.options"
        :key="i"
        :label="checkbox.label"
        :value="checkbox.value"
      >
      </NCheckbox>
    </NCheckboxGroup>
  </FieldWrapper>
</template>

<script lang="ts">
import { NCheckbox, NCheckboxGroup } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsOneOfEnumControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";

export default defineComponent({
  name: "MultipleChoiceCheckboxControlRenderer",
  components: {
    FieldWrapper,
    NCheckbox,
    NCheckboxGroup,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsOneOfEnumControl(props), (target) => target);
  },
});
</script>
