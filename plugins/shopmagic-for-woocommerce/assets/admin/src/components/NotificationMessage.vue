<script setup lang="ts">
import { NText } from "naive-ui";

const props = defineProps<{
  title: string;
  message?: string;
}>();

let sanitizedMessage = "";

if (props.message !== undefined) {
  sanitizedMessage = new DOMParser().parseFromString(
    props.message.replace("\\n", "\n"),
    "text/html",
  ).body.innerHTML;
}
</script>
<template>
  <NText strong>{{ title }}</NText>
  <div v-if="message" style="white-space: pre-line" v-html="sanitizedMessage"></div>
</template>
