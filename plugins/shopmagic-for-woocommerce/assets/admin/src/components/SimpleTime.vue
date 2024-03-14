<script lang="ts" setup>
import dayjs from "dayjs";

defineProps<{
  time: string;
}>();

function format(time: string): string {
  return dayjs(time).format("D MMM, YYYY");
}

function formatLong(time: string): string {
  return dayjs(time).format("D MMMM, YYYY HH:mm");
}
</script>
<template>
  <time :datetime="time" :title="formatLong(time)">{{ format(time) }}</time>
</template>
