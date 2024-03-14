<template>
  <FieldWrapper v-bind="controlWrapper">
    <NDatePicker
      :id="control.id + '-input'"
      :default-value="defaultValue"
      :disabled="!control.enabled"
      :value="dataTime"
      class="w-full"
      type="datetime"
      @blur="isFocused = false"
      @focus="isFocused = true"
      @update:value="onChange"
    />
  </FieldWrapper>
</template>

<script lang="ts">
import { NDatePicker } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { DateTime } from "luxon";

export default defineComponent({
  name: "DatetimeControlRenderer",
  components: {
    FieldWrapper,
    NDatePicker,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsControl(props), (target) =>
      DateTime.fromMillis(target).toISO(),
    );
  },
  computed: {
    defaultValue(): number {
      return DateTime.fromISO(this.control.schema.default).toMillis();
    },
    dataTime(): number {
      if (this.control.data) return DateTime.fromISO(this.control.data).toMillis();

      return DateTime.now().toMillis();
    },
  },
});
</script>
