import type { PostStatus } from "@/types";

export type Shortcode = {
  name: boolean;
  labels: boolean;
  double_optin: boolean;
  agreement: string;
};

export type List = {
  id: number | null;
  name: string | null;
  type: "opt_in" | "opt_out";
  subscribers?: Subscriber[];
  subscribersCount?: number;
  shortcode?: Shortcode | null;
  status: PostStatus;
  checkout: {
    checkout_available: boolean;
    checkout_label: string;
    checkout_description: string;
  };
  language: string;
};

export type Subscriber = {
  id: number;
  email: string;
  list: Pick<List, "id" | "name">;
  active: boolean;
  type: boolean;
  created: string;
  updated: string;
};
