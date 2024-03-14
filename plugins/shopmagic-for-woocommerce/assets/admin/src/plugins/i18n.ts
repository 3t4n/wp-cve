import type { App } from "vue";

export const i18n = {
  install: (app: App, options: { domain?: string }) => {
    app.config.globalProperties.__ = (text: string, domain?: string) => {
      return wp.i18n.__(text, domain || options.domain || "default");
    };

    app.config.globalProperties._n = (singular: string, plural: string, count: number, domain?: string) => {
      return wp.i18n._n(singular, plural, count, domain || options.domain || "default");
    };

    app.config.globalProperties.sprintf = (format: string, ...args: any[]) => {
      return wp.i18n.sprintf(text, domain || options.domain || "default");
    };
  },
};

export function __(text: string, domain?: string) {
  return wp.i18n.__(text, domain || "default");
}

export function _n(singular: string, plural: string, count: number, domain?: string) {
  return wp.i18n._n(singular, plural, count, domain || "default");
}

export function sprintf(format: string, ...args: any[]) {
  return wp.i18n.sprintf(format, args);
}

declare module "@vue/runtime-core" {
  export interface ComponentCustomProperties {
    __: (text: string, domain?: string) => string;
    _n: (singular: string, plural: string, count: number, domain?: string) => string;
    sprintf: (format: string, ...args: any[]) => string;
  }
}
