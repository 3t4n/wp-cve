<script setup lang="ts">
import { NButton, NIcon, useMessage } from "naive-ui";
import { TrashOutline } from "@vicons/ionicons5";
import { removeAutomation } from "@/app/automations/singleAutomation";
import { h } from "vue";
import NotificationMessage from "@/components/NotificationMessage.vue";
import { __ } from "@/plugins/i18n";

const message = useMessage();

const props = defineProps<{
  id: number;
}>();

async function deleteAutomation() {
  const m = message.loading(__("Deleting automation", "shopmagic-for-woocommerce"), {
    duration: 0,
    keepAliveOnHover: true,
  });

  try {
    await removeAutomation(props.id);
    m.content = () =>
      h(NotificationMessage, {
        title: __("Automation deleted", "shopmagic-for-woocommerce"),
      });
    m.type = "success";
  } catch (e) {
    m.content = () =>
      h(NotificationMessage, {
        title: e.message,
        message: typeof e.cause === "string" ? e.cause : undefined,
      });
    m.type = "error";
    throw e;
  } finally {
    setTimeout(m.destroy, 4500);
  }
}
</script>
<template>
  <NButton size="small" type="error" tertiary @click="deleteAutomation">
    <template #icon>
      <NIcon>
        <TrashOutline />
      </NIcon>
    </template>
  </NButton>
</template>
