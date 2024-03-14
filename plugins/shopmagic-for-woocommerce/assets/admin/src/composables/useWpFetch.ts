import { createFetch } from "@vueuse/core";

export const ErrorSource = {
  Internal: "internal",
  WordPress: "wordpress",
  Server: "server",
  Unknown: "unknown",
} as const;

export const fetchOptions = {
  headers: {
    Accept: "application/json, application/problem+json",
    "X-WP-Nonce": window.ShopMagic.nonce,
    "Accept-Language": window.ShopMagic.user.locale,
  },
};

export const useWpFetch = createFetch({
  baseUrl: window.ShopMagic.baseUrl.replace(/\/$/, ""),
  options: {
    async beforeFetch({ options }) {
      if (ShopMagic.requestCompatibilityMode === "") {
        return {};
      }

      switch (options.method) {
        case "PUT":
        case "PATCH":
        case "DELETE":
          options.headers = {
            ...options.headers,
            "X-HTTP-Method-Override": options.method,
          };
          options.method = "POST";
          break;
      }

      return {
        options,
      };
    },
    async afterFetch({ data, response }) {
      const contentType = response?.headers.get("content-type") || "";
      if (contentType.includes("json") && typeof data === "string" && data.length > 0) {
        // If it's a JSON response, parse it upfront
        data = JSON.parse(data);
      }

      return { data };
    },
    onFetchError({ data, error, response }) {
      const cause: { data: any; source: string } = {
        data,
        source: ErrorSource.Unknown,
      };
      const contentType = response?.headers.get("content-type") || "";

      if (contentType.includes("json") && typeof data === "string" && data.length > 0) {
        // If it's a JSON response, parse it upfront
        cause.data = JSON.parse(data);
      }

      if (contentType.includes("application/problem+json")) {
        // ShopMagic internally uses api problem format
        cause.source = ErrorSource.Internal;
      } else if (contentType.includes("application/json")) {
        cause.source = ErrorSource.WordPress;
      } else if (contentType.includes("text")) {
        cause.source = ErrorSource.Server;
      }

      return {
        error: Error(error.message, { cause }),
        // We don't want to propagate the error to data property
        // It's usually used in data stores and this would pollute our
        // correct data
        data: undefined,
      };
    },
  },
  fetchOptions,
});
