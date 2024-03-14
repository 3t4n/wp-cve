<script lang="ts" setup>
import type { DataTableColumns } from "naive-ui";
import { NButton, NH1, NP, NText, useMessage } from "naive-ui";
import { type Recipe, useRecipesStore } from "@/stores/recipes";
import { h } from "vue";
import DataTable from "@/components/Table/DataTable.vue";
import { __ } from "@/plugins/i18n";
import { storeToRefs } from "pinia";
import { useRouter } from "vue-router";

const { recipes, loading } = storeToRefs(useRecipesStore());
const { createAutomation } = useRecipesStore();
const message = useMessage();
const router = useRouter();

function createRecipe(name: string) {
  createAutomation(name)
    .then((id) => {
      router.push({
        name: "automation",
        params: {
          id: id?.value,
        },
      });
    })
    .catch(() => {
      message.error(__("Failed to use recipe", "shopmagic-for-woocommerce"));
    });
}

const columns: DataTableColumns<Recipe> = [
  {
    key: "recipe",
    title: () => __("Recipe", "shopmagic-for-woocommerce"),
    render: ({ name, description }) =>
      h("div", [h(NText, { strong: true }, { default: () => name }), h(NP, () => description)]),
  },
  {
    key: "action",
    title: () => __("Action", "shopmagic-for-woocommerce"),
    render: ({ name }) =>
      h(
        NButton,
        {
          tertiary: true,
          type: "info",
          onClick: () => createRecipe(name),
        },
        { default: () => __("Use recipe", "shopmagic-for-woocommerce") },
      ),
    width: 150,
  },
];
</script>

<template>
  <NH1>{{ __("Recipes", "shopmagic-for-woocommerce") }}</NH1>
  <DataTable
    :columns="columns"
    :data="recipes"
    :error="null"
    :loading="loading"
    :show-pagination="false"
  />
</template>
