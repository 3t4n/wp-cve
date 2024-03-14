<template>
  <NText type="error">{{ control.description }}</NText>
</template>

<script lang="ts">
import type { ControlElement } from "@jsonforms/core";
import { NText } from "naive-ui";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";

export default defineComponent({
  name: "TextControlRenderer",
  components: {
    NText,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsControl(props), (target) => target || undefined);
  },
});
</script>
