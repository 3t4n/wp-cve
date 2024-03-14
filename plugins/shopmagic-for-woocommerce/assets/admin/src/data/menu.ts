import type { MenuOption } from "naive-ui";
import { NIcon } from "naive-ui";
import { h } from "vue";
import { RouterLink } from "vue-router";
import { ChevronDownOutline } from "@vicons/ionicons5";
import { __ } from "@/plugins/i18n";

const automationsGroup: MenuOption = {
  key: "automations",
  label: () =>
    h(
      "div",
      { class: "flex items-center" },
      {
        default: () => [
          h("span", __("Automations", "shopmagic-for-woocommerce")),
          h(NIcon, { class: "ml-1" }, { default: () => h(ChevronDownOutline) }),
        ],
      },
    ),
  children: [
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "automations",
            },
            class: ["flex", "gap-2", "items-center"],
          },
          {
            default: () => h("span", __("All automations", "shopmagic-for-woocommerce")),
          },
        ),
      key: "all-automations",
    },
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "automation",
              params: { id: "new" },
            },
          },
          { default: () => __("Add automation", "shopmagic-for-woocommerce") },
        ),
      key: "new-automation",
    },
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "recipes",
            },
          },
          { default: () => __("Recipes", "shopmagic-for-woocommerce") },
        ),
      key: "recipes",
    },
  ],
};

const marketingGroup: MenuOption = {
  key: "marketing",
  label: () =>
    h(
      "div",
      { class: "flex items-center" },
      {
        default: () => [
          h("span", __("Marketing", "shopmagic-for-woocommerce")),
          h(NIcon, { class: "ml-1" }, { default: () => h(ChevronDownOutline) }),
        ],
      },
    ),
  children: [
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "lists",
            },
            class: ["flex", "gap-2", "items-center"],
          },
          {
            default: () => __("Lists", "shopmagic-for-woocommerce"),
          },
        ),
      key: "lists",
    },
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "subscribers",
            },
          },
          { default: () => __("Subscribers", "shopmagic-for-woocommerce") },
        ),
      key: "subscribers",
    },
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "transfer",
            },
          },
          { default: () => __("Transfer", "shopmagic-for-woocommerce") },
        ),
      key: "transfer",
    },
  ],
};

const logsGroup: MenuOption = {
  key: "logs",
  label: () =>
    h(
      "div",
      { class: "flex items-center" },
      {
        default: () => [
          h("span", __("Logs", "shopmagic-for-woocommerce")),
          h(NIcon, { class: "ml-1" }, { default: () => h(ChevronDownOutline) }),
        ],
      },
    ),
  children: [
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "outcomes",
            },
            class: ["flex", "gap-2", "items-center"],
          },
          {
            default: () => __("Outcomes", "shopmagic-for-woocommerce"),
          },
        ),
      key: "outcomes",
    },
    {
      label: () =>
        h(
          RouterLink,
          {
            to: {
              name: "queue",
            },
          },
          { default: () => __("Queue", "shopmagic-for-woocommerce") },
        ),
      key: "queue",
    },
    ...(window.ShopMagic.emailTrackingEnabled == "1"
      ? [
          {
            label: () =>
              h(
                RouterLink,
                {
                  to: {
                    name: "tracker",
                  },
                },
                {
                  default: () => __("Email tracker", "shopmagic-for-woocommerce"),
                },
              ),
            key: "tracker",
          },
        ]
      : []),
  ],
};

const menu: MenuOption[] = [
  automationsGroup,
  marketingGroup,
  logsGroup,
  ...(window.ShopMagic.modules.includes("shopmagic-abandoned-carts")
    ? [
        {
          label: () =>
            h(RouterLink, { to: { name: "carts" } }, () =>
              __("Carts", "shopmagic-for-woocommerce"),
            ),
          key: "carts",
        },
      ]
    : [
        {
          disabled: true,
          label: () => __("Carts", "shopmagic-for-woocommerce"),
          key: "carts",
        },
      ]),
  {
    label: () =>
      h(
        RouterLink,
        {
          to: {
            name: "guests",
          },
        },
        () => __("Guests", "shopmagic-for-woocommerce"),
      ),
    key: "guests",
  },
  {
    label: () =>
      h(RouterLink, { to: { name: "settings" } }, () =>
        __("Settings", "shopmagic-for-woocommerce"),
      ),
    key: "settings",
  },
  ...(window.ShopMagic.proEnabled !== "1"
    ? [
        {
          label: () =>
            h(
              "a",
              {
                class: "!text-white",
                href: "https://shopmagic.app/?utm_source=plugin&utm_medium=pro-button&utm_campaign=upgrade-pro",
              },
              {
                default: () => __("Upgrade to PRO", "shopmagic-for-woocommerce"),
              },
            ),
          key: "pro",
        },
      ]
    : []),
];

export { menu };
