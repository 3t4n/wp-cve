<script lang="ts" setup>
import { NButton, NCollapse, NCollapseItem, NFormItem, NInput, NSwitch } from "naive-ui";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";
import { inject } from "vue";
import { modulesKey } from "@/provide";
import LanguageSelector from "@/components/Select/LanguageSelector.vue";
import { useMarketingStore } from "@/app/marketing-lists/store";

const store = useMarketingStore();
const { list } = storeToRefs(store);
const { useList } = store;

const modules = inject(modulesKey);

if (typeof list.value === "undefined") {
  const route = useRoute();
  useList(parseInt(route.params.id));
}

defineProps<{
  publish: boolean;
}>();

defineEmits<{
  (e: "save"): void;
  (e: "delete"): void;
  (e: "update:name", value: string): void;
  (e: "update:publish", value: boolean): void;
  (e: "update:language", value: string): void;
}>();
</script>
<template>
  <NCollapse class="max-w-[264px] pt-2 px-2" default-expanded-names="settings">
    <NCollapseItem :title="__('Settings', 'shopmagic-for-woocommerce')" name="settings">
      <NFormItem
        :label="__('Marketing list published', 'shopmagic-for-woocommerce')"
        label-placement="left"
      >
        <NSwitch :value="publish" @update:value="$emit('update:publish', $event)" />
      </NFormItem>
      <NFormItem :label="__('Title', 'shopmagic-for-woocommerce')">
        <NInput
          :placeholder="__('My awesome marketing list')"
          :value="list?.name"
          @update:value="$emit('update:name', $event)"
        />
      </NFormItem>
      <NFormItem
        v-if="modules.includes('multilingual-module')"
        :label="__('Language', 'shopmagic-for-woocommerce')"
      >
        <LanguageSelector
          :value="list?.language"
          @update:value="$emit('update:language', $event)"
        />
      </NFormItem>
      <div class="flex flex-wrap gap-2">
        <NButton ghost type="info" @click="$emit('save')">
          {{ __("Save", "shopmagic-for-woocommerce") }}
        </NButton>
        <NButton ghost type="error" @click="$emit('delete')">
          {{ __("Discard", "shopmagic-for-woocommerce") }}
        </NButton>
      </div>
    </NCollapseItem>
  </NCollapse>
</template>
