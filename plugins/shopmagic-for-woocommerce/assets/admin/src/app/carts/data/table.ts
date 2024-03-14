import type { DataTableColumn, DataTableColumns } from "naive-ui";
import { h } from "vue";
import dayjs from "dayjs";
import type { Cart } from "@/app/carts/types";
import { __ } from "@/plugins/i18n";
import CartProducts from "../components/CartProducts.vue";
import CartStatus from "../components/CartStatus.vue";
import SimpleTime from "@/components/SimpleTime.vue";

const customerColumn: DataTableColumn<Readonly<Cart>> = {
  key: "customer",
  title: __("Customer", "shopmagic-for-woocommerce"),
  render: ({ customer }) =>
    customer?.email || __("A customer does not exist", "shopmagic-for-woocommerce"),
};

export const cartColumns: DataTableColumns<Readonly<Cart>> = [
  {
    type: "selection",
  },
  customerColumn,
  {
    key: "products",
    title: __("Products", "shopmagic-for-woocommerce"),
    width: 560,
    render: ({ products }) => h(CartProducts, { products }),
  },
  {
    key: "amount",
    title: __("Amount", "shopmagic-for-woocommerce"),
    render: ({ value }) =>
      Intl.NumberFormat("en-US", {
        currency: value.currency || "USD",
        currencyDisplay: "symbol",
        style: "currency",
      }).format(value.price || 0),
  },
  {
    key: "last_modified",
    title: __("Last active", "shopmagic-for-woocommerce"),
    render: ({ updated }) => h(SimpleTime, { time: updated }),
    sorter: (a, b) => dayjs(a.updated).diff(b.updated),
  },
  {
    key: "status",
    title: __("Status", "shopmagic-for-woocommerce"),
    render: ({ status }) => h(CartStatus, { status }),
  },
];
