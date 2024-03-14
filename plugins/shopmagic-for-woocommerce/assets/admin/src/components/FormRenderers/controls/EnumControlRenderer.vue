<template>
  <control-wrapper
    :applied-options="appliedOptions"
    :is-focused="isFocused"
    :styles="styles"
    v-bind="controlWrapper"
  >
    <select
      :id="control.id + '-select'"
      :autofocus="appliedOptions.focus"
      :class="styles.control.select"
      :disabled="!control.enabled"
      :value="control.data"
      @blur="isFocused = false"
      @change="onChange"
      @focus="isFocused = true"
    >
      <option key="empty" :class="styles.control.option" value="" />
      <option
        v-for="optionElement in control.options"
        :key="optionElement.value"
        :class="styles.control.option"
        :label="optionElement.label"
        :value="optionElement.value"
      ></option>
    </select>
  </control-wrapper>
</template>

<script lang="ts">
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsEnumControl } from "@jsonforms/vue";
import { default as ControlWrapper } from "./ControlWrapper.vue";
import { useVanillaControl } from "../util";

export default defineComponent({
  name: "EnumControlRenderer",
  components: {
    ControlWrapper,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsEnumControl(props), (target) =>
      target.selectedIndex === 0 ? undefined : target.value,
    );
  },
});
</script>
