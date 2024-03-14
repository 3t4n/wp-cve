import type { SelectBaseOption } from "naive-ui/es/select/src/interface";
import type { SelectOption } from "naive-ui";
import type { JsonSchema } from "@jsonforms/core";

type Groupable = {
  group: string;
};

type InputTextType = "text" | "textarea";

type InputSelectType = "select";

type InputCheckboxType = "checkbox";

type InputNumberType = "number";

type PluginModuleField = "plugin-module";

type ButtonType = "button";

type InputType =
  | InputTextType
  | InputSelectType
  | InputCheckboxType
  | InputNumberType
  | ButtonType
  | PluginModuleField;

type AutomationConfig = SelectBaseOption;

type AutomationConfigElement = AutomationConfig & Groupable;

export type { AutomationConfigElement, AutomationConfig };

type EventConfig = {
  value: string;
  settings?: JsonSchema;
} & Field &
  AutomationConfigElement;

export type Field = {
  id: string;
  type: InputType;
  label: string;
  description?: string;
  value?: string | number | boolean | null;
  defaultValue?: string | number | boolean;
};

type FilterConfig = AutomationConfigElement & Conditions;

type FilterCondition = {
  options?: SelectOption[];
} & Field;

type Conditions = {
  settings: FilterCondition[];
};

type ActionConfig = SelectOption & {
  settings?: ActionConfigOption[] | ActionConfigSelectOption[];
};

type ActionConfigOption = Field;

type ActionConfigSelectOption = {
  type: InputSelectType;
  options: SelectOption[];
};

type PlaceholderConfig = {
  label: string;
  description?: string;
  fields: JsonSchema;
} & Groupable;

export type { EventConfig, FilterConfig, ActionConfig, PlaceholderConfig, EventConfigOption };

export type { InputType, InputTextType, InputSelectType, InputCheckboxType };
