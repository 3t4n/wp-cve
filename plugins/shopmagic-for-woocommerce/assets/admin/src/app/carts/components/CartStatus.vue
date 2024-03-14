<script lang="ts" setup>
import { computed } from "vue";
import { NPopover, NTag } from "naive-ui";
import { __ } from "@/plugins/i18n";

const props = defineProps<{
  status: "abandoned" | "ordered" | "active" | "recovered" | "submitted";
}>();

const cartStatus = computed(() => {
  let status = {};
  switch (props.status) {
    case "ordered":
      status = {
        name: __("Ordered", "shopmagic-for-woocommerce"),
        tooltip: __("Ordered immediately", "shopmagic-for-woocommerce"),
      };
      break;
    case "abandoned":
      status = {
        name: __("Abandoned", "shopmagic-for-woocommerce"),
        tooltip: __("Cart abandoned", "shopmagic-for-woocommerce"),
        type: "error",
      };
      break;
    case "active":
      status = {
        name: __("Active", "shopmagic-for-woocommerce"),
        tooltip: __("Cart currently active", "shopmagic-for-woocommerce"),
        type: "info",
      };
      break;
    case "recovered":
      status = {
        name: __("Recovered", "shopmagic-for-woocommerce"),
        tooltip: __("Cart recovered", "shopmagic-for-woocommerce"),
        type: "success",
      };
      break;
    case "submitted":
      status = {
        name: __("Submitted order", "shopmagic-for-woocommerce"),
        tooltip: __("Order is submitted, but not paid", "shopmagic-for-woocommerce"),
        type: "warning",
      };
      break;
  }
  return Object.assign(
    {
      name: __("Unknown", "shopmagic-for-woocommerce"),
      tooltip: __("Unknown", "shopmagic-for-woocommerce"),
      type: "default",
    },
    status,
  );
});
</script>
<template>
  <NPopover trigger="hover">
    <template #trigger>
      <NTag :bordered="false" :type="cartStatus.type">
        {{ cartStatus.name }}
      </NTag>
    </template>
    {{ cartStatus.tooltip }}
  </NPopover>
</template>
