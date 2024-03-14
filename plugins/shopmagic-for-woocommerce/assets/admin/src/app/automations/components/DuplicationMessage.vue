<script lang="ts" setup>
import { NButton, NIcon, NPopover, useMessage } from "naive-ui";
import { CopyOutline } from "@vicons/ionicons5";
import { useSingleAutomation } from "../singleAutomation";
import { __ } from "@/plugins/i18n";

const props = defineProps<{ id: number }>();

const message = useMessage();
const { duplicate } = useSingleAutomation();

function duplicateAutomation() {
  const m = message.loading(__("Duplicating automation", "shopmagic-for-woocommerce"), {
    duration: 0,
  });
  duplicate(props.id)
    .then(() => {
      m.content = __("Automation duplicated", "shopmagic-for-woocommerce");
      m.type = "success";
    })
    .catch((e) => {
      m.content = e.message;
      m.type = "error";
    })
    .finally(() => {
      setTimeout(m.destroy, 1500);
    });
}
</script>
<template>
  <NPopover trigger="hover">
    <template #trigger>
      <NButton size="small" tertiary type="info" @click="duplicateAutomation">
        <template #icon>
          <NIcon>
            <CopyOutline />
          </NIcon>
        </template>
      </NButton>
    </template>
    {{ __("Duplicate", "shopmagic-for-woocommerce") }}
  </NPopover>
</template>
