<script lang="ts" setup>
import { NCard } from "naive-ui";
</script>

<template>
  <NCard class="shadow" hoverable>
    <slot></slot>
    <template #action>
      <slot name="below" />
    </template>
  </NCard>
</template>
