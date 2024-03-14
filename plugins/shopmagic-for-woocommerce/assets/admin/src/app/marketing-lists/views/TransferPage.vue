<script lang="ts" setup>
import { useWpFetch } from "@/composables/useWpFetch";
import { computed, ref } from "vue";
import { NButton, NSelect } from "naive-ui";
import { useMarketingStore } from "@/app/marketing-lists/store";
import { storeToRefs } from "pinia";
import { sprintf } from "@/plugins/i18n";

const store = useMarketingStore();
const { lists } = storeToRefs(store);
const { fetchItems } = store;

fetchItems();
const importList = ref<number | null>(null);
const importResult = ref(null);
const exportList = ref<number | null>(null);

const l = computed(() => lists.value?.map((i) => ({ label: i.name, value: i.id })));

const importFile = ref<File | null>(null);

function selectFile(e: Event) {
  importFile.value = e.target?.files[0] as File;
}

async function exportSubscribers() {
  const file = await useWpFetch(`/lists/${exportList.value}/subscribers`, {
    beforeFetch({ options }) {
      options.headers = {
        ...options.headers,
        Accept: "text/csv",
      };

      return {
        options,
      };
    },
  })
    .get()
    .blob();

  const url = URL.createObjectURL(file.data.value);
  const anchor = document.createElement("a");
  anchor.href = url;
  anchor.download = "subscribers";
  document.body.appendChild(anchor);
  anchor.click();
  document.body.removeChild(anchor);
  URL.revokeObjectURL(url);
}

async function importAutomation() {
  const form = new FormData();
  form.append("file", importFile.value);
  const { data } = await useWpFetch(`/lists/${importList.value}/subscribers`).post(form);
  importResult.value = data.value;
}
</script>
<template>
  <div class="flex gap-8">
    <div class="w-1/2">
      <h2>{{ __("Import", "shopmagic-for-woocommerce") }}</h2>
      <form class="flex flex-col gap-4">
        <NFormItem class="bg-gray-50 p-4">
          <label class="sr-only" for="automation-file">{{ __("Import file") }}</label>
          <input id="automation-file" type="file" @change="selectFile" />
        </NFormItem>
        <NFormItem>
          <NSelect v-model:value="importList" :options="l" />
        </NFormItem>
        <NButton secondary type="info" @click="importAutomation">
          {{ __("Import subscribers", "shopmagic-for-woocommerce") }}
        </NButton>
        <div v-if="importResult">
          {{ __("Import finished.", "shopmagic-for-woocommerce") }}
          <span v-if="importResult.imported">
            {{
              sprintf(
                __("Successfully imported %d contacts.", "shopmagic-for-woocommerce"),
                importResult.imported,
              )
            }}
          </span>
          <span v-if="importResult.errors">
            {{
              sprintf(
                __("Errors in %d imported contacts.", "shopmagic-for-woocommerce"),
                importResult.errors,
              )
            }}
          </span>
        </div>
      </form>
    </div>
    <div class="w-1/2">
      <h2>{{ __("Export", "shopmagic-for-woocommerce") }}</h2>
      <form class="flex flex-col gap-4">
        <NFormItem>
          <NSelect v-model:value="exportList" :options="l" />
        </NFormItem>
        <NFormItem>
          <NButton secondary type="info" @click="exportSubscribers">
            {{ __("Export Subscribers", "shopmagic-for-woocommerce") }}
          </NButton>
        </NFormItem>
      </form>
    </div>
  </div>
</template>
