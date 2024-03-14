export type FilterFn = (item: unknown) => boolean;

type Value = string | boolean | number;

export type FilterValue = null | string | boolean | number | FilterFn;

export type Filters = Record<string, FilterValue>;

type Filtered = Record<string, Value>;

export const useFilter =
  (filters: Filters) =>
  (filtered: Filtered): boolean => {
    return Object.entries(filters)
      .filter(([, filter]) => !!filter)
      .reduce((prev, [key, value]) => {
        if (Object.hasOwn(filtered, key)) {
          if (typeof value === "function") {
            return value(filtered[key]) && prev;
          }
          if (typeof filtered[key] === "string") {
            return filtered[key].includes(value) && prev;
          }
          return filtered[key] === value && prev;
        }
        return false;
      }, true);
  };
