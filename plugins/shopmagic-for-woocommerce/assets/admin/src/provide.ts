import type { InjectionKey } from "vue";

export const userKey = Symbol() as InjectionKey<{
  name: string;
  email: string;
}>;

export const modulesKey = Symbol() as InjectionKey<string[]>;

export const proEnabledKey = Symbol() as InjectionKey<boolean>;
