import { isScoped, type JsonSchema, type Layout, RuleEffect } from "@jsonforms/core";
import { generateDefaultUISchema } from "@/_utils/uiSchemaGenerator";

export function useGeneratedSchemaUI(jsonSchema: JsonSchema) {
  const uiSchemaElement = generateDefaultUISchema(jsonSchema) as Layout;
  uiSchemaElement.elements = uiSchemaElement.elements.map((element) => {
    if (!isScoped(element)) return element;

    if (
      element.scope.includes("_action_schedule_type") ||
      element.scope.includes("_action_available_days")
    ) {
      element.rule = {
        effect: RuleEffect.SHOW,
        condition: {
          scope: "#/properties/_action_delayed",
          schema: {
            const: true,
          },
        },
      };
    }

    if (element.scope.includes("_action_fixed_date")) {
      element.rule = {
        effect: RuleEffect.SHOW,
        condition: {
          scope: "#/properties/_action_schedule_type",
          schema: {
            const: "fixed",
          },
        },
      };
    }

    if (element.scope.includes("_action_variable_string")) {
      element.rule = {
        effect: RuleEffect.SHOW,
        condition: {
          scope: "#/properties/_action_schedule_type",
          schema: {
            const: "placeholder",
          },
        },
      };
    }

    if (
      element.scope.includes("_action_delay_interval") ||
      element.scope.includes("_action_delay_nbr") ||
      element.scope.includes("_action_delay_step")
    ) {
      element.rule = {
        effect: RuleEffect.SHOW,
        condition: {
          scope: "#",
          schema: {
            allOf: [
              {
                type: "object",
                properties: {
                  _action_schedule_type: {
                    const: "delay",
                  },
                },
              },
              {
                type: "object",
                properties: {
                  _action_delayed: {
                    const: true,
                  },
                },
              },
            ],
          },
        },
      };
    }

    if (element.scope.includes("_action_schedule_time")) {
      element.rule = {
        effect: RuleEffect.SHOW,
        condition: {
          scope: "#/properties/_action_schedule_type",
          schema: {
            const: "scheduled",
          },
        },
      };
    }
    return element;
  });
  return uiSchemaElement;
}
