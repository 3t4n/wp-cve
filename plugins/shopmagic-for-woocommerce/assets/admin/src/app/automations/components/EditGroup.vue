<script lang="ts" setup>
import { NSpace } from "naive-ui";
import { computed, provide, ref } from "vue";
import { editGroupCards } from "./cards";

const openedCard = ref<symbol | null>(null);
provide(editGroupCards, () => {
  const id = Symbol();
  return {
    openedCard: computed(() => openedCard.value === id),
    toggle: () => (openedCard.value = openedCard.value === id ? null : id),
    close: () => (openedCard.value = null),
  };
});
</script>

<template>
  <NSpace
    :size="48"
    align="center"
    class="py-24"
    item-style="width: 70%"
    justify="start"
    style="min-height: calc(100vh - 60px - 32px)"
    vertical
  >
    <slot></slot>
  </NSpace>
</template>
