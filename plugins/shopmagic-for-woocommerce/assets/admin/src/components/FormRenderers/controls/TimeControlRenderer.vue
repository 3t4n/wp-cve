<template>
  <FieldWrapper v-bind="controlWrapper">
    <NTimePicker
      :id="control.id + '-input'"
      :disabled="!control.enabled"
      :placeholder="control.schema.examples[0]"
      :value="time"
      class="w-full"
      type="time"
      @blur="isFocused = false"
      @focus="isFocused = true"
      @update:value="onChange"
    />
  </FieldWrapper>
</template>

<script lang="ts">
import { NTimePicker } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { DateTime } from "luxon";

export default defineComponent({
  name: "TimeControlRenderer",
  components: {
    FieldWrapper,
    NTimePicker,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(
      useJsonFormsControl(props),
      (target) => DateTime.fromMillis(target).toSQLTime({ includeOffset: false }) || undefined,
    );
  },
  computed: {
    time() {
      if (!this.control.data) return null;
      if (typeof this.control.data === "number") {
        const timestamp = DateTime.fromMillis(this.control.data);
        if (timestamp.isValid) {
          return timestamp.toMillis();
        }
      }
      return DateTime.fromISO(this.control.data).toMillis();
    },
  },
});
</script>
