<script lang="ts" setup>
import RouterWrapper from "@/components/Layout/TabbedWrapper.vue";
import { computed } from "vue";
import { useRoute } from "vue-router";
import { __ } from "@/plugins/i18n";

const tabs = {
  automations: __("Automations", "shopmagic-for-woocommerce"),
  recipes: __("Recipes", "shopmagic-for-woocommerce"),
};

const route = useRoute();

const showTabs = computed(() => !["automation", "manual-run"].includes(route.name));
</script>
<template>
  <RouterWrapper :show-tabs="showTabs" :tabs="tabs" />
</template>
