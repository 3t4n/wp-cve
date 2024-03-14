<script lang="ts" setup>
import RouterWrapper from "@/components/Layout/TabbedWrapper.vue";
import { __ } from "@/plugins/i18n";

const tabs = {
  outcomes: __("Outcomes", "shopmagic-for-woocommerce"),
  queue: __("Queue", "shopmagic-for-woocommerce"),
  tracker: __("Email tracker", "shopmagic-for-woocommerce"),
};
</script>
<template>
  <RouterWrapper :tabs="tabs" />
</template>
