<script lang="ts" setup>
import { NImage, NPopover } from "naive-ui";
import type { CartProduct } from "@/app/carts/types";

defineProps<{
  products: CartProduct[];
}>();
</script>
<template>
  <ul class="flex flex-wrap gap-2">
    <li v-for="product in products" :key="product.id">
      <NPopover>
        <template #trigger>
          <NImage :src="product.image" height="40" width="40" />
        </template>
        <span v-html="product.name"></span>,
        {{ __("qty.", "shopmagic-for-woocommerce") }}
        {{ product.quantity }}
      </NPopover>
    </li>
  </ul>
</template>
