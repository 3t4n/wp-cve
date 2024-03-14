import { type SelectGroupOption, type SelectOption } from "naive-ui";
import type { AutomationConfig, AutomationConfigElement } from "@/types/automationConfig";
import { useSorter } from "@/composables/useSorter";
import type { Filters } from "@/composables/useFilter";
import { useFilter } from "@/composables/useFilter";
import { ErrorSource, useWpFetch } from "@/composables/useWpFetch";
import { type Ref, unref } from "vue";
import { __ } from "@/plugins/i18n";
import type { HttpProblem } from "@/types";

export function elementsAsOptions(
  elements: AutomationConfigElement[] | undefined,
): Array<SelectOption | SelectGroupOption> {
  if (typeof elements === "undefined") return [];

  if (!isGroupable(elements)) return elements;

  const groups: Map<string, SelectGroupOption> = new Map();
  elements?.forEach((element) => {
    if (typeof element.group === "undefined") return;
    if (!groups.has(element.group)) {
      groups.set(element.group, {
        type: "group",
        label: element.group,
        key: element.group,
        children: [],
      });
    }

    const group = groups.get(element.group) as SelectGroupOption;
    group.children?.push(element);
  });
  return [...groups.values()];
}

const isGroupable = (config: AutomationConfig[]): config is AutomationConfigElement[] =>
  config.every((i) => typeof i.group !== "undefined");

type MaybeRef<T> = Ref<T> | T;

export async function get<Data = unknown>(url: MaybeRef<string>): Promise<Data> {
  const { data, error } = await useWpFetch<Data>(url);
  if (error.value) {
    switch (error.value.cause.source) {
      case ErrorSource.WordPress:
        throw new Error(__("WordPress error", "shopmagic-for-woocommerce"), {
          cause: error.value.cause.data.message,
        });
      case ErrorSource.Server:
        throw new Error("Failed to update automation", {
          cause: __(
            "Failed to update automation due to server issues. Enable compatibility mode in plugin settings and try again.",
            "shopmagic-for-woocommerce",
          ),
        });
      case ErrorSource.Internal: {
        const errorMessage: HttpProblem = error.value.cause.data;
        throw new Error(errorMessage.title, { cause: errorMessage.detail });
      }
      default:
        throw new Error("Internet connection error");
    }
  }

  return unref(data);
}
type SortOrder = false | "ascend" | "descend";

export type SortQuery = {
  [q: string]: SortOrder;
};

export type Query = Partial<{
  page: number;
  pageSize: number;
  filters: Filters | null;
  order: SortQuery | null;
}>;

type UnknownObject = {
  [p: string]: unknown;
};

export function query<T extends UnknownObject>(
  rawData: Iterable<T>,
  { page = 1, pageSize = 20, filters, order }: Query,
): T[] {
  const sortedResult = order ? [...rawData].sort(useSorter(order)) : [...rawData];
  const filteredResult =
    filters && Object.keys(filters).length ? sortedResult.filter(useFilter(filters)) : sortedResult;
  return filteredResult.slice((page - 1) * pageSize, page * pageSize);
}

type KeyedItem = {
  id: number;
} & UnknownObject;

export function toMap<TObject extends KeyedItem>(array: TObject[]): Map<TObject["id"], TObject> {
  return new Map(array.map((a) => [a.id, { ...a }]));
}
