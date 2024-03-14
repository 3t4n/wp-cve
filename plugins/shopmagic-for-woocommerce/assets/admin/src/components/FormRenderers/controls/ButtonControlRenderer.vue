<script lang="ts" setup>
import { NButton } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "@/components/FormRenderers/controls/FieldWrapper.vue";

const props = defineProps(rendererProps<ControlElement>());

const { control } = useVanillaControl(useJsonFormsControl(props));
</script>
<template>
  <FieldWrapper>
    <NButton secondary type="info">
      <div v-html="control.description" />
    </NButton>
  </FieldWrapper>
</template>
