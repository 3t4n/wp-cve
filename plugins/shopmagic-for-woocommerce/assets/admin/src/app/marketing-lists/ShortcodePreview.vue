<script lang="ts" setup>
import { NInput } from "naive-ui";
import type { Shortcode } from "./types";
import { computed } from "vue";

const props = defineProps<{
  id: number;
  shortcode: Shortcode;
}>();

type ParameterKey = keyof Shortcode & "id";

function buildShortcode(s: Shortcode & { id: number }) {
  let shortcode = "[shopmagic_form";
  for (const param of Object.keys(s) as Array<ParameterKey>) {
    if (param === "id" || param === "agreement") {
      if (s[param] === "") continue;
      shortcode += ` ${param}="${s[param]}"`;
    } else {
      if (s[param]) {
        shortcode += ` ${param}`;
      } else if (param !== "double_optin") {
        shortcode += ` ${param}=false`;
      }
    }
  }
  shortcode += "]";

  return shortcode;
}
const shortcodeWithoutId = computed(() => {
  const copy = Object.assign({}, props.shortcode);
  if (Object.hasOwn(copy, "id")) {
    delete copy.id;
  }
  return copy;
});
</script>
<template>
  <NInput :value="buildShortcode({ id, ...shortcodeWithoutId })" readonly />
</template>
