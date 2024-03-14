<script lang="ts" setup>
import type { ControlElement } from "@jsonforms/core";
import { NButton } from "naive-ui";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { useSingleAutomation } from "@/app/automations/singleAutomation";
import { storeToRefs } from "pinia";
import { useRouter } from "vue-router";

const { automation } = storeToRefs(useSingleAutomation());
const router = useRouter();

const props = defineProps(rendererProps<ControlElement>());

const { control, controlWrapper } = useVanillaControl(useJsonFormsControl(props));

function callback() {
  router.push({
    name: "manual-run",
    params: {
      id: automation.value?.id,
    },
  });
}
</script>
<template>
  <FieldWrapper :show-label="false" v-bind="controlWrapper">
    <NButton
      :id="control.id + '-action'"
      :disabled="typeof automation?.id !== 'number'"
      :readonly="control.schema.readOnly"
      type="info"
      @click="callback"
      >{{ control.label }}</NButton
    >
  </FieldWrapper>
</template>
