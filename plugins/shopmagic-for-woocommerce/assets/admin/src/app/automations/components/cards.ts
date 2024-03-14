import type { InjectionKey, Ref } from "vue";

type Cards = {
  openedCard: Ref<boolean>;
  toggle: () => void;
  close: () => void;
};

export const editGroupCards = Symbol() as InjectionKey<() => Cards>;
