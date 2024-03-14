import type { DataTableColumns } from "naive-ui";
import { NButton, NIcon, NPopover, NSpace, NTag } from "naive-ui";
import type { AutomationData } from "@/types/automation";
import { h } from "vue";
import { RouterLink } from "vue-router";
import { DownloadOutline, TrashOutline } from "@vicons/ionicons5";
import { __ } from "@/plugins/i18n";
import DuplicationMessage from "../components/DuplicationMessage.vue";
import { downloadAutomation, removeAutomation } from "@/app/automations/singleAutomation";

export const automationSubTable: DataTableColumns<AutomationData> = [
  {
    key: "name",
    title: __("Name", "shopmagic-for-woocommerce"),
    render: ({ id, name }) =>
      h(
        RouterLink,
        { to: { name: "automation", params: { id } } },
        () => name || __("(Unnamed)", "shopmagic-for-woocommerce"),
      ),
  },
  {
    key: "actionable",
    width: 164,
    render: ({ id }) =>
      h(NSpace, () => [
        h(DuplicationMessage, { id }),
        h(
          NPopover,
          {
            trigger: "hover",
          },
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
          {
            trigger: "hover",
          },
          {
            trigger: () =>
              h(
                NButton,
                {
                  tertiary: true,
                  type: "error",
                  size: "small",
                  onClick: () => void removeAutomation(id),
                },
                { icon: () => h(NIcon, () => h(TrashOutline)) },
              ),
            default: () => __("Delete", "shopmagic-for-woocommerce"),
          },
        ),
      ]),
  },
  {
    key: "language",
    title: __("Language", "shopmagic-for-woocommerce"),
    minWidth: 130,
    render: ({ language }) => {
      if (language === null) return;
      return new Intl.DisplayNames(navigator.languages, {
        type: "language",
        style: "short",
        languageDisplay: "standard",
      }).of(language);
    },
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
