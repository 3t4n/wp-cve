import type { DataTableColumns } from "naive-ui";
import { NIcon } from "naive-ui";
import type { ReadSubscriber } from "@/stores/marketingLists/subscribers";
import dayjs from "dayjs";
import { CheckmarkSharp, Close } from "@vicons/ionicons5";
import { h } from "vue";
import { __ } from "@/plugins/i18n";
import SimpleTime from "@/components/SimpleTime.vue";

export const subscribersTableColumns: DataTableColumns<ReadSubscriber> = [
  {
    type: "selection",
  },
  {
    key: "user",
    title: () => __("Email", "shopmagic-for-woocommerce"),
    render: ({ email }) => email || __("A customer does not exist", "shopmagic-for-woocommerce"),
  },
  {
    key: "list",
    title: () => __("List", "shopmagic-for-woocommerce"),
    render: ({ list }) => list?.name || __("A list does not exists", "shopmagic-for-woocommerce"),
  },
  {
    key: "signed",
    title: () => __("Signed up", "shopmagic-for-woocommerce"),
    render: ({ active }) => (active ? h(NIcon, () => h(CheckmarkSharp)) : h(NIcon, () => h(Close))),
  },
  {
    key: "created",
    title: () => __("Created", "shopmagic-for-woocommerce"),
    render: ({ created }) => h(SimpleTime, { time: created }),
    sorter: (a, b) => dayjs(a.updated).diff(b.updated),
  },
  {
    key: "updated",
    title: () => __("Updated", "shopmagic-for-woocommerce"),
    render: ({ updated }) => h(SimpleTime, { time: updated }),
    sorter: (a, b) => dayjs(a.updated).diff(b.updated),
  },
];
