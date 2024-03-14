import StringControlRenderer from "@/components/FormRenderers/controls/StringControlRenderer.vue";
import {
  and,
  isBooleanControl,
  isDateControl,
  isDateTimeControl,
  isEnumControl,
  isIntegerControl,
  isNumberControl,
  isOneOfEnumControl,
  isScoped,
  isStringControl,
  isTimeControl,
  type JsonFormsRendererRegistryEntry,
  or,
  rankWith,
  resolveSchema,
  uiTypeIs,
} from "@jsonforms/core";
import GoogleWorksheetsControlRenderer from "@/components/FormRenderers/controls/GoogleWorksheetsControlRenderer.vue";
import ActionControlRenderer from "@/components/FormRenderers/controls/ActionControlRenderer.vue";
import MultipleChoiceCheckboxControlRenderer from "@/components/FormRenderers/controls/MultipleChoiceCheckboxControlRenderer.vue";
import EnumOneOfControlRenderer from "@/components/FormRenderers/controls/EnumOneOfControlRenderer.vue";
import TimeControlRenderer from "@/components/FormRenderers/controls/TimeControlRenderer.vue";
import MediaPickerControlRenderer from "@/components/FormRenderers/controls/MediaPickerControlRenderer.vue";
import NumberControlRenderer from "@/components/FormRenderers/controls/NumberControlRenderer.vue";
import DateControlRenderer from "@/components/FormRenderers/controls/DateControlRenderer.vue";
import TextControlRenderer from "@/components/FormRenderers/controls/TextControlRenderer.vue";
import EditorControlRenderer from "@/components/FormRenderers/controls/EditorControlRenderer.vue";
import BooleanControlRenderer from "@/components/FormRenderers/controls/BooleanControlRenderer.vue";
import DateTimeControlRenderer from "@/components/FormRenderers/controls/DateTimeControlRenderer.vue";
import EnumControlRenderer from "@/components/FormRenderers/controls/EnumControlRenderer.vue";
import IntegerControlRenderer from "@/components/FormRenderers/controls/IntegerControlRenderer.vue";
import ManualActionControlRenderer from "./ManualActionControlRenderer.vue";
import PluginModuleRenderer from "./PluginModuleRenderer.vue";
import ButtonControlRenderer from "./ButtonControlRenderer.vue";
import RawHtmlControlRenderer from "./RawHtmlControlRenderer.vue";
import TextAreaControlRenderer from "@/components/FormRenderers/controls/TextAreaControlRenderer.vue";

export const controlRenderers: JsonFormsRendererRegistryEntry[] = [
  {
    renderer: StringControlRenderer,
    tester: rankWith(1, isStringControl),
  },
  {
    renderer: NumberControlRenderer,
    tester: rankWith(1, isNumberControl),
  },
  {
    renderer: IntegerControlRenderer,
    tester: rankWith(1, isIntegerControl),
  },
  {
    renderer: EnumControlRenderer,
    tester: rankWith(2, isEnumControl),
  },
  {
    renderer: EnumOneOfControlRenderer,
    tester: rankWith(2, isOneOfEnumControl),
  },
  {
    renderer: DateControlRenderer,
    tester: rankWith(2, isDateControl),
  },
  {
    renderer: DateTimeControlRenderer,
    tester: rankWith(2, isDateTimeControl),
  },
  {
    renderer: TimeControlRenderer,
    tester: rankWith(2, isTimeControl),
  },
  {
    renderer: PluginModuleRenderer,
    tester: rankWith(3, (uischema, schema, context) => {
      if (!isScoped(uischema)) return false;
      const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
      return elementSchema?.format === "plugin-module";
    }),
  },
  {
    renderer: BooleanControlRenderer,
    tester: rankWith(
      3,
      or(isBooleanControl, (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return elementSchema?.extendedCoerce === true;
      }),
    ),
  },
  {
    renderer: MultipleChoiceCheckboxControlRenderer,
    tester: rankWith(
      3,
      and(isOneOfEnumControl, (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return (elementSchema.uniqueItems && elementSchema?.format !== "select") || false;
      }),
    ),
  },
  {
    renderer: MediaPickerControlRenderer,
    tester: rankWith(
      2,
      and(isStringControl, (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return elementSchema?.format === "file";
      }),
    ),
  },
  {
    renderer: TextAreaControlRenderer,
    tester: rankWith(
      2,
      and(isStringControl, (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return (
          (elementSchema?.format === "textarea" && elementSchema?.presentation?.type === "plain") ||
          false
        );
      }),
    ),
  },
  {
    renderer: EditorControlRenderer,
    tester: rankWith(
      2,
      and(isStringControl, (uischema, schema, context) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
        return (
          (elementSchema?.format === "textarea" && elementSchema?.presentation?.type === "rich") ||
          false
        );
      }),
    ),
  },
  {
    renderer: TextControlRenderer,
    tester: rankWith(
      3,
      and(isStringControl, (ui, schema, { rootSchema }) => {
        if (!isScoped(ui)) return false;
        const element = resolveSchema(schema, ui.scope, rootSchema);
        return element?.format === "error";
      }),
    ),
  },
  {
    renderer: ActionControlRenderer,
    tester: rankWith(5, (uischema, schema, context) => {
      if (!isScoped(uischema)) return false;
      const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
      return (
        elementSchema?.format === "action" &&
        typeof elementSchema.presentation.callback !== "undefined"
      );
    }),
  },
  {
    renderer: GoogleWorksheetsControlRenderer,
    tester: rankWith(
      5,
      and(uiTypeIs("Group"), (uischema, schema, { rootSchema }) => {
        if (!isScoped(uischema)) return false;
        const elementSchema = resolveSchema(schema, uischema.scope, rootSchema);
        return elementSchema?.format === "google-sheets";
      }),
    ),
  },
  {
    renderer: ManualActionControlRenderer,
    tester: rankWith(7, (uischema, schema, context) => {
      if (!isScoped(uischema)) return false;
      const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
      return elementSchema?.format === "manual-action" && elementSchema.type === "null";
    }),
  },
  {
    renderer: RawHtmlControlRenderer,
    tester: rankWith(5, (uischema, schema, context) => {
      if (!isScoped(uischema)) return false;
      const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
      return elementSchema?.format === "advertisement" || elementSchema.type === "null";
    }),
  },
  {
    renderer: ButtonControlRenderer,
    tester: rankWith(6, (uischema, schema, context) => {
      if (!isScoped(uischema)) return false;
      const elementSchema = resolveSchema(schema, uischema.scope, context.rootSchema);
      return elementSchema?.format === "button" && elementSchema.type === "null";
    }),
  },
];
