import type { DataTableColumns } from "naive-ui";
import GuestPreview from "../components/GuestPreview.vue";
import type { Client } from "@/stores/clients";
import { __ } from "@/plugins/i18n";
import { h } from "vue";
import SimpleTime from "@/components/SimpleTime.vue";

export const guestsTableColumns: DataTableColumns<Client> = [
  {
    type: "selection",
  },
  {
    key: "email",
    title: __("Email", "shopmagic-for-woocommerce"),
  },
  {
    key: "lastActive",
    title: __("Last active", "shopmagic-for-woocommerce"),
    render: ({ lastActive }) => h(SimpleTime, { time: lastActive }),
  },
  {
    key: "created",
    title: __("Created", "shopmagic-for-woocommerce"),
    render: ({ created }) => h(SimpleTime, { time: created }),
  },
  {
    key: "details",
    width: 150,
    title: __("Details", "shopmagic-for-woocommerce"),
    render: (guest) => h(GuestPreview, { guest }),
  },
];
