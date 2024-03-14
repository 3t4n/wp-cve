import { isReactive, isRef, toRaw, unref } from "vue";

type PreviousParam = {
  [key: string]: any;
  previousObject?: PreviousParam;
};

function traverse(object: object, previousObject?: PreviousParam): string {
  return Object.entries(object).reduce((previous, [key, value]) => {
    if (isReactive(value)) {
      value = toRaw(value);
    }
    if (isRef(value)) {
      value = unref(value);
    }
    if (value === null) return previous;

    if (typeof value === "object") {
      return previous + traverse(value, { [key]: value, previousObject });
    }

    if (typeof value === "function") {
      value = value();
    }

    const keyString = buildParamName(key, previousObject);

    return previous + "&" + `${keyString}=${encodeURIComponent(value)}`;
  }, "");
}

function buildParamName(key: string, parts?: PreviousParam) {
  if (parts === undefined) {
    return key;
  }

  let root = undefined;
  if (parts.previousObject !== undefined) {
    root = Object.keys(parts.previousObject)[0];
  }

  root = root ? `${root}[${Object.keys(parts)[0]}]` : Object.keys(parts)[0];

  return `${root}[${key}]`;
}

export function useSearchParams(query: object): string {
  return traverse(toRaw(query)).slice(1);
}

const glue = window?.ShopMagic?.permalinkStructure === "plain" ? "&" : "?";

/**
 * This function relies on global flag, which determines, if we should
 * append "?" or "&" to the url. This changed based on current WordPress
 * permalink settings, which may be plain (usually, already including "?") or
 * slugified.
 */
export function appendSearchParams(url: string, query: object) {
  return url + glue + useSearchParams(query);
}
