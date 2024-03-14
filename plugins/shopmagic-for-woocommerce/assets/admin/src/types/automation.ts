import type { PostStatus } from "@/types/index";

type Automation = {
  id: number | null;
  parent: number | null;
  name: string;
  event: Event;
  filters: FilterOrGroup;
  actions: Action[];
  status: PostStatus;
  language: string;
  recipe: boolean;
  _links: LinkCollection<"parent" | "children">;
};

type Link = {
  href: string;
};

type LinkCollection<T extends string> = {
  [k in T]: Link;
};

type Event = {
  name: string | null;
  settings: { [k: string]: string | null };
};

type Filter = {
  id: string | null;
  condition?: string | null;
  [k: string]: string | number | string[] | number[] | null | undefined;
};

type FilterGroup = Filter[];

type FilterOrGroup = FilterGroup[];

type Action = {
  name: string | null;
  settings?: { [k: string]: string | null };
};

type Automations = Map<number, Automation | null>;

type AutomationData = Readonly<{
  id: number;
  name: string;
  event: string;
  actions: string[];
  status: PostStatus;
  language: string | null;
  recipe: boolean;
}>;

export type { Automation, Automations, AutomationData, Filter, FilterOrGroup, Action };
