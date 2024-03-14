import type { DataTableColumn, DataTableColumns } from "naive-ui";
import { NTag } from "naive-ui";
import type { ReadOutcome } from "../types";
import dayjs from "dayjs";
import { h } from "vue";
import { RouterLink } from "vue-router";
import { DateTime, Interval } from "luxon";
import SimpleTime from "@/components/SimpleTime.vue";
import OutcomeError from "../components/OutcomeError.vue";
import { __ } from "@/plugins/i18n";

const rtf = new Intl.RelativeTimeFormat(navigator.languages);

function toAbsoluteNegativeValue(value: number): number {
  return parseInt(Number(value * -1).toFixed());
}

const automationColumn: DataTableColumn<ReadOutcome> = {
  key: "automation",
  title: () => __("Automation", "shopmagic-for-woocommerce"),
  render: ({ automation }) =>
    automation?.id
      ? h(
          RouterLink,
          { to: { name: "automation", params: { id: automation.id } } },
          () => automation.name || __("(Unnamed)", "shopmagic-for-woocommerce"),
        )
      : __("Automation does not exists", "shopmagic-for-woocommerce"),
};

const customerColumn: DataTableColumn<ReadOutcome> = {
  key: "customer",
  title: () => __("Customer", "shopmagic-for-woocommerce"),
  render: ({ customer }) =>
    customer?.email || __("A customer does not exist", "shopmagic-for-woocommerce"),
};

export const shortOutcomeColumns: DataTableColumns<ReadOutcome> = [
  automationColumn,
  {
    key: "status",
    title: __("Status", "shopmagic-for-woocommerce"),
    render: ({ status }) =>
      h(
        NTag,
        {
          type: status === "completed" ? "success" : "error",
        },
        () =>
          status === "completed"
            ? __("Completed", "shopmagic-for-woocommerce")
            : __("Failed", "shopmagic-for-woocommerce"),
      ),
  },
  {
    title: () => __("Date", "shopmagic-for-woocommerce"),
    key: "updated",
    render: ({ updated }) => {
      const now = DateTime.now();
      const run = DateTime.fromISO(updated);
      const i = Interval.fromDateTimes(run, now);
      if (i.length("minutes") <= 60) {
        return rtf.format(toAbsoluteNegativeValue(i.length("minutes")), "minutes");
      } else if (i.length("hours") <= 24) {
        return rtf.format(toAbsoluteNegativeValue(i.length("hours")), "hours");
      } else if (i.length("days") <= 7) {
        return rtf.format(toAbsoluteNegativeValue(i.length("days")), "days");
      } else if (i.length("weeks") <= 4) {
        return rtf.format(toAbsoluteNegativeValue(i.length("weeks")), "weeks");
      }

      return rtf.format(toAbsoluteNegativeValue(i.length("months")), "months");
    },
  },
];

export const outcomeColumns: DataTableColumns<ReadOutcome> = [
  {
    type: "selection",
  },
  {
    key: "id",
    title: "ID",
  },
  {
    key: "status",
    title: () => __("Status", "shopmagic-for-woocommerce"),
    render: ({ status }) =>
      h(
        NTag,
        {
          type: status === "completed" ? "success" : "error",
        },
        () =>
          status === "completed"
            ? __("Success", "shopmagic-for-woocommerce")
            : __("Failure", "shopmagic-for-woocommerce"),
      ),
  },
  automationColumn,
  customerColumn,
  {
    key: "action",
    title: () => __("Action", "shopmagic-for-woocommerce"),
  },
  {
    key: "updated",
    title: () => __("Date", "shopmagic-for-woocommerce"),
    render: ({ updated }) => h(SimpleTime, { time: updated }),
    sorter: (a, b) => dayjs(a.updated).diff(b.updated),
  },
  {
    type: "expand",
    title: () => __("Errors", "shopmagic-for-woocommerce"),
    minWidth: 65,
    expandable: ({ error }) => !!error,
    renderExpand: ({ error }) => h(OutcomeError, { note: error.note, trace: error.context.Trace }),
  },
];
