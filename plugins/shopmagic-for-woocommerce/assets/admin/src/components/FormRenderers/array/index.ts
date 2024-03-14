import type { JsonFormsRendererRegistryEntry } from "@jsonforms/core";
import { and, isScoped, rankWith, resolveSchema, schemaTypeIs } from "@jsonforms/core";

import ArrayListRenderer from "./ArrayListRenderer.vue";
import ProductArrayRenderer from "./ProductArrayRenderer.vue";

export const arrayRenderers: JsonFormsRendererRegistryEntry[] = [
  {
    renderer: ProductArrayRenderer,
    tester: rankWith(
      2,
      and(schemaTypeIs("array"), (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return elementSchema?.presentation.type === "products";
      }),
    ),
  },
  {
    renderer: ArrayListRenderer,
    tester: rankWith(2, schemaTypeIs("array")),
  },
];
