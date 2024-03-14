import type { DataTableColumns } from "naive-ui";
import { h } from "vue";
import { RouterLink } from "vue-router";
import { __ } from "@/plugins/i18n";

export const trackerPerAutomation: DataTableColumns = [
  {
    key: "automation",
    title: __("Automation", "shopmagic-for-woocommerce"),
    render: ({ automation }) => {
      if (automation.name === null)
        return __("Automation does not exist", "shopmagic-for-woocommerce");

      return h(
        RouterLink,
        { to: { name: "automation", params: { id: automation.id } } },
        () => automation.name || __("(Unnamed)", "shopmagic-for-woocommerce"),
      );
    },
  },
  {
    key: "count",
    title: __("Emails Delivered", "shopmagic-for-woocommerce"),
  },
  {
    key: "openRate",
    title: __("Open Rate", "shopmagic-for-woocommerce"),
    render: ({ openRate }) =>
      Intl.NumberFormat("en-US", { style: "percent" }).format(openRate || 0),
  },
  {
    key: "clickRate",
    title: __("Click Through Rate", "shopmagic-for-woocommerce"),
    render: ({ clickRate }) =>
      Intl.NumberFormat("en-US", { style: "percent" }).format(clickRate || 0),
  },
];

export const trackerPerCustomer: DataTableColumns = [
  {
    key: "automation",
    title: __("Customer", "shopmagic-for-woocommerce"),
    render: ({ customer }) => customer.email,
  },
  {
    key: "count",
    title: __("Emails Delivered", "shopmagic-for-woocommerce"),
  },
  {
    key: "openRate",
    title: __("Open Rate", "shopmagic-for-woocommerce"),
    render: ({ openRate }) =>
      Intl.NumberFormat("en-US", { style: "percent" }).format(openRate || 0),
  },
  {
    key: "clickRate",
    title: __("Click Through Rate", "shopmagic-for-woocommerce"),
    render: ({ clickRate }) =>
      Intl.NumberFormat("en-US", { style: "percent" }).format(clickRate || 0),
  },
];
