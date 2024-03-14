import LayoutRenderer from "@/components/FormRenderers/layouts/LayoutRenderer.vue";
import GridLayoutRenderer from "@/components/FormRenderers/layouts/GridLayoutRenderer.vue";
import GroupRenderer from "@/components/FormRenderers/layouts/GroupRenderer.vue";
import { markRaw } from "vue";
import { and, isLayout, rankWith, uiTypeIs } from "@jsonforms/core";

export const layoutRenderers = [
  {
    renderer: markRaw(GridLayoutRenderer),
    tester: rankWith(1, uiTypeIs("GridHorizontalLayout")),
  },
  {
    renderer: markRaw(LayoutRenderer),
    tester: rankWith(1, isLayout),
  },
  {
    renderer: markRaw(GroupRenderer),
    tester: rankWith(2, and(isLayout, uiTypeIs("Group"))),
  },
];
