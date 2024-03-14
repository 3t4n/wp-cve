import type { DataTableColumns } from "naive-ui";
import { NButton, NIcon, NPopover, NSpace, NTag } from "naive-ui";
import type { AutomationData } from "@/types/automation";
import { h } from "vue";
import { RouterLink } from "vue-router";
import { DownloadOutline } from "@vicons/ionicons5";
import { __ } from "@/plugins/i18n";
import DuplicationMessage from "../components/DuplicationMessage.vue";
import DeleteButtonWithNotification from "../components/DeleteButtonWithNotification.vue";
import { downloadAutomation } from "@/app/automations/singleAutomation";
import AutomationSubTable from "@/app/automations/components/AutomationSubTable.vue";

export const automationTableColumns: DataTableColumns<AutomationData> = [
  {
    type: "selection",
  },
  ...(window.ShopMagic.modules.includes("multilingual-module")
    ? [
        {
          type: "expand",
          // Expand only if automation has reference to any children
          expandable: (automation) => automation?._links?.children,
          renderExpand: (automation) => h(AutomationSubTable, { automation }),
        },
      ]
    : []),
  {
    key: "name",
    title: __("Name", "shopmagic-for-woocommerce"),
    render: ({ id, name }) =>
      h(
        RouterLink,
        { to: { name: "automation", params: { id } } },
        () => name || __("(Unnamed)", "shopmagic-for-woocommerce"),
      ),
    sorter: (a, b) => a.name.localeCompare(b.name),
  },
  {
    key: "actionable",
    width: 164,
    render: ({ id }) =>
      h(NSpace, () => [
        h(DuplicationMessage, { id }),
        h(
          NPopover,
          { trigger: "hover" },
          {
            trigger: () =>
              h(
                NButton,
                {
                  tertiary: true,
                  type: "info",
                  size: "small",
                  onClick: () => void downloadAutomation(id),
                },
                {
                  icon: () => h(NIcon, () => h(DownloadOutline)),
                },
              ),
            default: () => __("Export", "shopmagic-for-woocommerce"),
          },
        ),
        h(
          NPopover,
          { trigger: "hover" },
          {
            trigger: () => h(DeleteButtonWithNotification, { id }),
            default: () => __("Delete", "shopmagic-for-woocommerce"),
          },
        ),
      ]),
  },
  {
    key: "event",
    title: __("Event", "shopmagic-for-woocommerce"),
  },
  {
    key: "actions",
    title: __("Actions", "shopmagic-for-woocommerce"),
    render: ({ actions }) => actions?.join(", ") || "",
  },
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
