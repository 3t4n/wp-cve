<template>
  <control-wrapper
    :applied-options="appliedOptions"
    :is-focused="isFocused"
    :styles="styles"
    v-bind="controlWrapper"
  >
    <input
      :id="control.id + '-input'"
      :autofocus="appliedOptions.focus"
      :class="styles.control.input"
      :disabled="!control.enabled"
      :placeholder="appliedOptions.placeholder"
      :step="1"
      :value="control.data"
      type="number"
      @blur="isFocused = false"
      @change="onChange"
      @focus="isFocused = true"
    />
  </control-wrapper>
</template>

<script lang="ts">
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { default as ControlWrapper } from "./ControlWrapper.vue";
import { useVanillaControl } from "../util";

export default defineComponent({
  name: "IntegerControlRenderer",
  components: {
    ControlWrapper,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsControl(props), (target) =>
      target.value === "" ? undefined : parseInt(target.value, 10),
    );
  },
});
</script>
