<script lang="ts" setup>
import { NP } from "naive-ui";
import type { ControlElement } from "@jsonforms/core";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";

const props = defineProps(rendererProps<ControlElement>());

const { control } = useVanillaControl(useJsonFormsControl(props));
</script>
<template>
  <NP>
    <div v-html="control.description" />
  </NP>
</template>
