type SortOrder = false | "descend" | "ascend";

type SortOrderFlag = 0 | 1 | -1;

export type SortOptions = {
  columnKey: string;
  sorter: SortFn | "default";
  order: SortOrder;
};

type SortFn = (a: InternalData, b: InternalData) => number;

type InternalData = {
  [k: string]: unknown;
};

export const useSorter =
  ({ columnKey, sorter, order }: SortOptions) =>
  (a: InternalData, b: InternalData): number => {
    const sorterFn = getSortFunction({ sorter, columnKey });
    return sorterFn(a, b) * getOrderFlag(order);
  };

function getSortFunction({ sorter, columnKey }: Pick<SortOptions, "sorter" | "columnKey">) {
  if (typeof sorter === "function") {
    return sorter;
  }
  return getDefaultSorterFn(columnKey);
}

function getDefaultSorterFn(columnKey: string): SortFn {
  return (a, b) => {
    const value1 = a[columnKey];
    const value2 = b[columnKey];
    if (typeof value1 === "string" && typeof value2 === "string") {
      return value1.localeCompare(value2);
    }
    if (typeof value1 === "number" && typeof value2 === "number") {
      return value1 - value2;
    }
    return 0;
  };
}

function getOrderFlag(order: SortOrder): SortOrderFlag {
  if (order === "ascend") return 1;
  else if (order === "descend") return -1;
  return 0;
}
