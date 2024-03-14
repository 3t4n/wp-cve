<script lang="ts" setup>
import RouterWrapper from "@/components/Layout/TabbedWrapper.vue";
import { computed } from "vue";
import { useRoute } from "vue-router";
import { __ } from "@/plugins/i18n";

const tabs = {
  lists: __("Lists", "shopmagic-for-woocommerce"),
  subscribers: __("Subscribers", "shopmagic-for-woocommerce"),
  transfer: __("Transfer", "shopmagic-for-woocommerce"),
};

const route = useRoute();
const showTabs = computed(() => route.name !== "marketing-list");
</script>
<template>
  <RouterWrapper :show-tabs="showTabs" :tabs="tabs" />
</template>
