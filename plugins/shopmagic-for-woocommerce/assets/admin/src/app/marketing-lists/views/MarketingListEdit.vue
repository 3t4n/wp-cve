<script lang="ts" setup>
import EditGroup from "@/app/automations/components/EditGroup.vue";
import EditBar from "@/components/EditBar.vue";
import { NLayout, NLayoutContent, NLayoutHeader, useMessage } from "naive-ui";
import { storeToRefs } from "pinia";
import { onBeforeRouteLeave, useRoute, useRouter } from "vue-router";
import EditableCard from "@/app/automations/components/EditableCard.vue";
import { useMarketingStore } from "@/app/marketing-lists/store";
import JsonForm from "@/components/JsonForm.vue";
import { useMarketingResources } from "@/app/marketing-lists/resourceStore";
import { computed } from "vue";
import { __ } from "@/plugins/i18n";
import MarketingListSidebar from "@/app/marketing-lists/components/MarketingListSidebar.vue";

const store = useMarketingStore();
const {
  fields,
  shortcode,
  loading: fieldsLoading,
  shortcodeLoading,
} = storeToRefs(useMarketingResources());
const { list } = storeToRefs(store);
const { addList, useList, save, update, remove } = store;
const route = useRoute();

const data = computed(() => list.value?.checkout || {});

onBeforeRouteLeave(() => {
  store.$patch((state) => (state.list = null));
});

if (route.params.id === "new" && list.value === null) {
  addList();
} else if (!isNaN(parseInt(route.params.id))) {
  useList(parseInt(route.params.id));
}

const message = useMessage();
const router = useRouter();

async function saveList() {
  const m = message.loading(__("Saving marketing list", "shopmagic-for-woocommerce"), {
    duration: 0,
  });
  try {
    if (!isNaN(parseInt(route.params.id))) {
      await update();
    } else {
      const id = await save();
      if (route.params.id === "new") {
        router.replace({
          name: "marketing-list",
          params: {
            id: id,
          },
        });
      }
    }
    m.content = __("Marketing list saved", "shopmagic-for-woocommerce");
    m.type = "success";
  } catch (e) {
    m.content = e.message;
    m.type = "error";
  } finally {
    setTimeout(m.destroy, 1500);
  }
}

async function updatePublish(value: boolean) {
  store.$patch((state) => (state.list.status = value ? "publish" : "draft"));
  await saveList();
}

function handleUpdate({ data }) {
  store.$patch((state) => {
    if (state.list === null) return;
    state.list.checkout = data;
    if (data.type) {
      state.list.type = data.type;
    }
  });
}

function handleShortcodeUpdate({ data }) {
  store.$patch((state) => {
    if (state.list === null) return;
    state.list.shortcode = data;
  });
}

function updateName(value: string) {
  store.$patch((state) => (state.list.name = value));
}

function updateLanguage(value: string) {
  store.$patch((state) => (state.list.language = value));
}

function deleteAutomation() {
  if (list.value?.id) {
    remove(list.value.id).then(() => {
      router.push({
        name: "marketing-lists",
      });
    });
  }
}
</script>
<template>
  <NLayout class="shadow-lg">
    <NLayoutHeader bordered class="drop-shadow-lg py-3 px-4">
      <EditBar
        :name="list?.name"
        :publish="list?.status === 'publish'"
        @save="saveList"
        @update:name="(name) => store.$patch((state) => (state.list.name = name))"
        @update:publish="updatePublish"
      />
    </NLayoutHeader>
    <NLayout has-sider>
      <NLayoutContent class="bg-gray-200">
        <EditGroup>
          <EditableCard :title="__('Settings', 'shopmagic-for-woocommerce')">
            <JsonForm
              v-if="!fieldsLoading"
              :data="data"
              :schema="fields || {}"
              @change="handleUpdate"
            />
          </EditableCard>
          <EditableCard
            v-if="list?.type === 'opt_in'"
            :title="__('Form shortcode', 'shopmagic-for-woocommerce')"
          >
            <JsonForm
              v-if="!shortcodeLoading"
              :data="list?.shortcode || {}"
              :schema="shortcode || {}"
              @change="handleShortcodeUpdate"
            />
          </EditableCard>
        </EditGroup>
      </NLayoutContent>
      <NLayoutSider :width="280">
        <MarketingListSidebar
          :publish="list?.status === 'publish'"
          @delete="deleteAutomation"
          @save="saveList"
          @update:publish="updatePublish"
          @update:name="updateName"
          @update:language="updateLanguage"
        />
      </NLayoutSider>
    </NLayout>
  </NLayout>
</template>
