<template>
  <div v-if="layout.visible" class="grid grid-cols-3 2xl:grid-cols-4 gap-4">
    <dispatch-renderer
      v-for="(element, index) in layout.uischema.elements"
      :key="`${layout.path}-${index}`"
      :cells="layout.cells"
      :enabled="layout.enabled"
      :path="layout.path"
      :renderers="layout.renderers"
      :schema="layout.schema"
      :uischema="element"
    />
  </div>
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
  name: "LayoutRenderer",
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
