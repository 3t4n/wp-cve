<script lang="ts" setup>
import JsonForm from "@/components/JsonForm.vue";

defineProps<{
  values: any;
  schema: any;
}>();

defineEmits<{
  (e: "change", data: { data: any; error: any }): void;
}>();
</script>
<template>
  <JsonForm :data="values" :schema="schema" @change="$emit('change', $event)" />
</template>
