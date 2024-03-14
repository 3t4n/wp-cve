<script lang="ts" setup>
import type { JsonSchema } from "@jsonforms/core";
import JsonForm from "@/components/JsonForm.vue";

defineProps<{
  values: any;
  schema: JsonSchema;
}>();

defineEmits<{
  (e: "change", data: { data: any; error: any }): void;
}>();
</script>
<template>
  <JsonForm :data="values" :schema="schema" layout="grid" @change="$emit('change', $event)" />
</template>
