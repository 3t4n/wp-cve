<template>
  <fieldset v-if="layout.visible" :class="styles.group.root">
    <legend v-if="layout.label" :class="styles.group.label">
      {{ layout.label }}
    </legend>
    <div
      v-for="(element, index) in layout.uischema.elements"
      :key="`${layout.path}-${index}`"
      :class="styles.group.item"
    >
      <dispatch-renderer
        :cells="layout.cells"
        :enabled="layout.enabled"
        :path="layout.path"
        :renderers="layout.renderers"
        :schema="layout.schema"
        :uischema="element"
      />
    </div>
  </fieldset>
</template>

<script lang="ts">
import type { Layout } from "@jsonforms/core";
import { defineComponent } from "vue";
import {
  DispatchRenderer,
  rendererProps,
  type RendererProps,
  useJsonFormsLayout,
} from "@jsonforms/vue";
import { useVanillaLayout } from "../util";

export default defineComponent({
  name: "GroupRenderer",
  components: {
    DispatchRenderer,
  },
  props: {
    ...rendererProps<Layout>(),
  },
  setup(props: RendererProps<Layout>) {
    return useVanillaLayout(useJsonFormsLayout(props));
  },
});
</script>
