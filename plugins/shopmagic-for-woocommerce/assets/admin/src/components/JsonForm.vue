<script lang="ts" setup>
import { JsonForms, type JsonFormsChangeEvent } from "@jsonforms/vue";
import { createAjv, type JsonSchema } from "@jsonforms/core";
import { naiveUiRenderers } from "@/_utils/renderers";
import { useGeneratedSchemaUI } from "@/composables/useGeneratedSchemaUI";
import { ref, watchEffect } from "vue";

const props = withDefaults(
  defineProps<{
    schema: JsonSchema;
    layout?: "vertical" | "horizontal" | "grid";
    data: object;
  }>(),
  {
    layout: "vertical",
  },
);

defineEmits<{
  (e: "change", event: JsonFormsChangeEvent): void;
}>();

const ajv = createAjv({
  validateFormats: false,
  coerceTypes: true,
  useDefaults: true,
  keywords: [
    {
      keyword: "extendedCoerce",
      type: "string",
      schemaType: "boolean",
      errors: "full",
      compile: (schema) => (value, obj) => {
        if (schema === true && obj) {
          obj.parentData[obj.parentDataProperty] =
            value === "yes" || value === "on" || value === "1";
        }
        return true;
      },
    },
  ],
});

const uischema = ref(useGeneratedSchemaUI(props.schema));
uischema.value.type = determineLayout(props.layout);

watchEffect(() => {
  uischema.value = useGeneratedSchemaUI(props.schema);
  uischema.value.type = determineLayout(props.layout);
});

function determineLayout(layoutString: string) {
  let layout;
  switch (layoutString) {
    case "horizontal":
      layout = "HorizontalLayout";
      break;
    case "grid":
      layout = "GridHorizontalLayout";
      break;
    default:
      layout = "VerticalLayout";
  }
  return layout;
}
</script>
<template>
  <JsonForms
    :ajv="ajv"
    :renderers="Object.freeze(naiveUiRenderers)"
    :uischema="uischema"
    v-bind="$props"
    validation-mode="ValidateAndHide"
    @change="$emit('change', $event)"
  />
</template>
