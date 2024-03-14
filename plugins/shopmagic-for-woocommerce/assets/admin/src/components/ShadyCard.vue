<script lang="ts" setup>
import { NCard } from "naive-ui";
</script>
<template>
  <NCard class="shadow" v-bind="$props">
    <template #cover>
      <slot name="cover" />
    </template>
    <template #header>
      <slot name="header" />
    </template>
    <template #header-extra>
      <slot name="header-extra" />
    </template>
    <template #action>
      <slot name="action" />
    </template>
    <slot />
  </NCard>
</template>
