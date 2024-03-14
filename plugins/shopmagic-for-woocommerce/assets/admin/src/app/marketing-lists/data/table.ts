import type { DataTableColumns } from "naive-ui";
import { NButton, NInput, NSpace, NTag } from "naive-ui";
import { h } from "vue";
import { RouterLink } from "vue-router";
import type { List } from "@/app/marketing-lists/types";
import { TrashOutline } from "@vicons/ionicons5";
import { __ } from "@/plugins/i18n";
import { removeList } from "@/app/marketing-lists/store";

export const marketingListsTableColumns: DataTableColumns<List> = [
  {
    type: "selection",
  },
  {
    key: "name",
    title: __("Name", "shopmagic-for-woocommerce"),
    render: ({ id, name }) =>
      h(
        RouterLink,
        { to: { name: "marketing-list", params: { id } } },
        () => name || __("(Unnamed)", "shopmagic-for-woocommerce"),
      ),
  },
  {
    key: "actions",
    width: 120,
    title: "",
    render: ({ id }) =>
      h(NSpace, () => [
        h(
          NButton,
          {
            tertiary: true,
            type: "error",
            size: "small",
            onClick: () => void removeList(id),
          },
          { icon: () => h(TrashOutline) },
        ),
      ]),
  },
  {
    key: "shortcode",
    title: __("Shortcode", "shopmagic-for-woocommerce"),
    width: 400,
    render: ({ id, type }) => {
      return type === "opt_out"
        ? __("Subscription form is not supported for opt-out lists.", "shopmagic-for-woocommerce")
        : h(NInput, { value: `[shopmagic_form id="${id}"]`, readonly: true });
    },
  },
  {
    key: "type",
    title: __("Type", "shopmagic-for-woocommerce"),
    render: ({ type }) =>
      type === "opt_in"
        ? __("Opt-In", "shopmagic-for-woocommerce")
        : __("Opt-Out", "shopmagic-for-woocommerce"),
  },
  {
    key: "subscribersCount",
    title: __("Subscribers", "shopmagic-for-woocommerce"),
    render: ({ subscribersCount, id }) =>
      h(
        RouterLink,
        {
          to: {
            name: "subscribers",
            query: {
              list: id,
            },
          },
        },
        {
          default: () => subscribersCount || 0,
        },
      ),
  },
  ...(window.ShopMagic.modules.includes("multilingual-module")
    ? [
        {
          key: "language",
          title: __("Language", "shopmagic-for-woocommerce"),
          minWidth: 130,
          render: ({ language }) =>
            new Intl.DisplayNames(navigator.languages, {
              type: "language",
              style: "short",
              languageDisplay: "standard",
            }).of(language),
        },
      ]
    : []),
  {
    key: "status",
    title: __("Status", "shopmagic-for-woocommerce"),
    width: 105,
    render: ({ status }) =>
      h(
        NTag,
        {
          type: (() => {
            if (status === "publish") {
              return "info";
            } else if (status === "trash") {
              return "error";
            } else {
              return "";
            }
          })(),
        },
        {
          default: () => {
            if (status === "publish") {
              return __("published", "shopmagic-for-woocommerce");
            } else if (status === "draft") {
              return __("draft", "shopmagic-for-woocommerce");
            } else if (status === "trash") {
              return __("trash", "shopmagic-for-woocommerce");
            } else {
              return __("unknown", "shopmagic-for-woocommerce");
            }
          },
        },
      ),
  },
];
