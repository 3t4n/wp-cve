import type { DataTableColumn, DataTableColumns } from "naive-ui";
import { h } from "vue";
import { RouterLink } from "vue-router";
import type { Queue } from "@/app/logs/types";
import { DateTime } from "luxon";
import { __ } from "@/plugins/i18n";

const automationColumn: DataTableColumn<Queue> = {
  key: "automation",
  title: () => __("Automation", "shopmagic-for-woocommerce"),
  sorter: "default",
  render: ({ automation }) =>
    automation?.id
      ? h(
          RouterLink,
          { to: { name: "automation", params: { id: automation.id } } },
          () => automation.name || __("(Unnamed)", "shopmagic-for-woocommerce"),
        )
      : __("Automation does not exists", "shopmagic-for-woocommerce"),
};

const customerColumn: DataTableColumn<Queue> = {
  key: "customer",
  title: "Customer",
  render: ({ customer }) =>
    customer?.email ||
    __("No customer is associated with this automation.", "shopmagic-for-woocommerce"),
};

export const queueTableColumns: DataTableColumns<Queue> = [
  {
    type: "selection",
  },
  automationColumn,
  customerColumn,
  {
    key: "action",
    title: () => __("Action", "shopmagic-for-woocommerce"),
    render: ({ automation }) => {
      if (!automation) return __("Action does not exist", "shopmagic-for-woocommerce");

      return (
        Object.values(automation.actions)[0] ||
        __("Action doesn't exist", "shopmagic-for-woocommerce")
      );
    },
  },
  {
    key: "timestamp",
    title: () => __("Run date", "shopmagic-for-woocommerce"),
    render: ({ schedule }) =>
      DateTime.fromISO(schedule, { setZone: true }).toFormat("dd LLL, yyyy HH:mm"),
  },
];
