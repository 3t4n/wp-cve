<template>
  <div v-if="layout.visible" :class="[layoutClassObject, 'flex gap-4', 'renderer-container']">
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
<style scoped>
.renderer-container {
  width: 100%;
}
.renderer-container > div {
  flex-grow: 1;
}
</style>

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
  computed: {
    layoutClassObject(): string {
      return this.layout.direction === "row" ? "flex-row" : "flex-col";
    },
  },
});
</script>
