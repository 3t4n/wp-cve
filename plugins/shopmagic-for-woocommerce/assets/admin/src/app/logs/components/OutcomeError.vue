<script lang="ts" setup>
import { NButton, NP, NText } from "naive-ui";
import { ref } from "vue";

defineProps<{
  note: string;
  trace: string;
}>();

const showStack = ref(false);
</script>
<template>
  <NP class="text-md" strong>{{ note }}</NP>
  <NButton quaternary type="info" @click="showStack = !showStack">
    {{ __("Show stack trace", "shopmagic-for-woocommerce") }}
  </NButton>
  <NText v-show="showStack" code>
    <pre class="whitespace-pre-wrap">{{ trace }}</pre>
  </NText>
</template>
