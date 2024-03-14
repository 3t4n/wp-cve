<script lang="ts" setup>
import { NFormItem } from "naive-ui";
import { ref } from "vue";

const props = withDefaults(
  defineProps<{
    errors?: string;
    description?: string;
    label?: string;
    required?: boolean;
    visible?: boolean;
    showLabel?: boolean;
    data?: any;
  }>(),
  {
    errors: "",
    visible: true,
    showLabel: true,
    required: false,
    description: "",
    label: "",
    data: undefined,
  },
);

const rule = ref({});
if (props.required) {
  rule.value = {
    required: props.required,
    trigger: ["blur"],
    validator: (rule) => !!(rule?.required && props.data),
  };
}
</script>
<template>
  <NFormItem
    :class="visible === false ? 'hidden' : ''"
    :label="label"
    :rule="rule"
    :show-feedback="description.length > 0 || errors.length > 0"
    :show-label="showLabel && !!label"
  >
    <slot />
    <template #feedback>
      <div v-if="errors">
        {{ label + " " + errors }}
      </div>
      <div v-else v-html="description" />
    </template>
  </NFormItem>
</template>
