<script lang="ts" setup>
import { NA, NInput } from "naive-ui";
import { useDebounceFn, useFetch } from "@vueuse/core";
import { ref } from "vue";

const apiKey = "0e51628719d28d86f341edcbc4acdba9c463fafc";
const siteId = "5e305c242c7d3a7e9ae6db3e";
const debouncedSearch = useDebounceFn((query: string) => {
  useFetch(
    `https://docsapi.helpscout.net/v1/search/articles?query=${query}&siteId=${siteId}&status=published&visibility=public`,
    {
      beforeFetch: ({ options }) => {
        options.headers = {
          ...options.headers,
          Authorization: `Basic ${btoa(apiKey + ":X")}`,
        };
        return {
          options,
        };
      },
    },
  )
    .json()
    .then(({ data }) => {
      articles.value = data.value?.articles.items;
    });
}, 600);
const gettingStartedCollection = "5e96112c2c7d3a7e9aeaede9";
const articles = ref([]);
</script>
<template>
  <NInput
    :placeholder="__('Search support articles...', 'shopmagic-for-woocommerce')"
    class="mb-4"
    @update:value="debouncedSearch"
  />
  <ul>
    <li v-for="(doc, i) in articles" :key="i">
      <NA :href="doc.url" target="_blank">
        {{ doc.name }}
      </NA>
    </li>
  </ul>
</template>
