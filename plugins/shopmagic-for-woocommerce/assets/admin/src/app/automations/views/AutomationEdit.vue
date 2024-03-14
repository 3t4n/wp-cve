<script lang="ts" setup>
import EventsPicker from "../components/EventsPicker.vue";
import ActionEditor from "../components/ActionEditor.vue";
import EditGroup from "../components/EditGroup.vue";
import AutomationSidebar from "../components/AutomationSidebar.vue";
import { NLayout, NLayoutContent, NLayoutHeader, NLayoutSider, useMessage } from "naive-ui";
import FilterEditor from "../components/FilterEditor.vue";
import NotificationMessage from "@/components/NotificationMessage.vue";
import { useAutomationResourcesStore } from "../resourceStore";
import { storeToRefs } from "pinia";
import { useSingleAutomation } from "../singleAutomation";
import { onBeforeRouteLeave, useRoute, useRouter } from "vue-router";
import EditBar from "@/components/EditBar.vue";
import { __ } from "@/plugins/i18n";
import { h } from "vue";

const message = useMessage();

const { events } = storeToRefs(useAutomationResourcesStore());

const { get, save, update, remove, addAutomation, $patch: patchAutomation } = useSingleAutomation();
const { automation } = storeToRefs(useSingleAutomation());

onBeforeRouteLeave(() => {
  patchAutomation((state) => (state.automation = null));
});

const route = useRoute();
const router = useRouter();

if (route.params.id === "new" && automation.value === null) {
  addAutomation();
} else if (!isNaN(parseInt(route.params.id))) {
  get(parseInt(route.params.id)).catch(() => {
    // @todo redirect on 404
  });
}

async function saveAutomation() {
  const m = message.loading(__("Saving automation", "shopmagic-for-woocommerce"), {
    duration: 0,
    keepAliveOnHover: true,
  });
  try {
    if (!isNaN(parseInt(route.params.id))) {
      await update();
    } else {
      const id = await save();
      if (route.params.id === "new") {
        router.replace({
          name: "automation",
          params: {
            id: id.value,
          },
        });
      }
    }
    m.content = () =>
      h(NotificationMessage, { title: __("Automation saved", "shopmagic-for-woocommerce") });
    m.type = "success";
  } catch (e) {
    m.content = () =>
      h(NotificationMessage, {
        title: e.message,
        message: typeof e.cause === "string" ? e.cause : undefined,
      });
    m.type = "error";
  } finally {
    m.closable = true;
    setTimeout(m.destroy, 4500);
  }
}

async function updatePublish(value: boolean) {
  patchAutomation({
    automation: {
      status: value ? "publish" : "draft",
    },
  });
  await saveAutomation();
}

function updateName(value: string) {
  patchAutomation({
    automation: {
      name: value,
    },
  });
}

function updateLanguage(value: string) {
  patchAutomation({
    automation: {
      language: value,
    },
  });
}

function updateParent(value: string) {
  patchAutomation({
    automation: {
      parent: value,
    },
  });
}

async function deleteAutomation() {
  const m = message.loading(__("Deleting automation", "shopmagic-for-woocommerce"), {
    duration: 0,
    keepAliveOnHover: true,
  });
  if (automation.value?.id) {
    try {
      await remove(automation.value.id);
      router.push({
        name: "automations",
      });
      m.content = () =>
        h(NotificationMessage, { title: __("Automation removed", "shopmagic-for-woocommerce") });
      m.type = "success";
    } catch (e) {
      m.content = () =>
        h(NotificationMessage, {
          title: e.message,
          message: typeof e.cause === "string" ? e.cause : undefined,
        });
      m.type = "error";
    } finally {
      m.closable = true;
      setTimeout(m.destroy, 4500);
    }
  }
}
</script>
<template>
  <NLayout class="shadow-lg">
    <NLayoutHeader bordered class="drop-shadow-lg py-3 px-4">
      <EditBar
        :name="automation?.name"
        :name-placeholder="__('My awesome automation', 'shopmagic-for-woocommerce')"
        :publish="automation?.status === 'publish'"
        @save="saveAutomation"
        @update:publish="updatePublish"
        @update:name="updateName"
      />
    </NLayoutHeader>
    <NLayout has-sider>
      <NLayoutContent class="bg-gray-200">
        <EditGroup>
          <EventsPicker :events="events || []" :automation="automation" />
          <FilterEditor />
          <ActionEditor />
        </EditGroup>
      </NLayoutContent>
      <NLayoutSider :width="280">
        <AutomationSidebar
          :automation="automation"
          :publish="automation?.status === 'publish'"
          @delete="deleteAutomation"
          @save="saveAutomation"
          @update:publish="updatePublish"
          @update:name="updateName"
          @update:language="updateLanguage"
          @update:parent="updateParent"
        />
      </NLayoutSider>
    </NLayout>
  </NLayout>
</template>
