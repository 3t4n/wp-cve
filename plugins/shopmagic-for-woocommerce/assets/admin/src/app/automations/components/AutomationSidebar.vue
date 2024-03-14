<script lang="ts" setup>
import { NButton, NCollapse, NCollapseItem, NFormItem, NInput, NSwitch } from "naive-ui";
import PlaceholdersSearch from "./PlaceholdersSearch.vue";
import HelpSearch from "@/app/automations/components/HelpSearch.vue";
import { inject, ref, toRef, watch } from "vue";
import { modulesKey } from "@/provide";
import LanguageSelector from "@/components/Select/LanguageSelector.vue";
import AutomationSearch from "@/components/Select/AutomationSearch.vue";
import { get } from "@/_utils";
import type { Automation } from "@/types/automation";

const props = defineProps<{
  publish: boolean;
  automation: Automation | null;
}>();

const automation = toRef(props, "automation");

const modules = inject(modulesKey);

const parentAutomationRef = ref<Automation | null>(null);

watch(
  () => automation.value?.parent,
  (parent: number | null) => {
    if (parent && (automation?.value?._links?.parent?.href ?? false)) {
      get(automation.value._links.parent.href).then((a) => {
        parentAutomationRef.value = a;
      });
    }
  },
);

defineEmits<{
  (e: "save"): void;
  (e: "delete"): void;
  (e: "update:name", value: string): void;
  (e: "update:publish", value: boolean): void;
  (e: "update:language", value: string): void;
  (e: "update:parent", value: number): void;
}>();
</script>
<template>
  <NCollapse accordion class="max-w-[264px] pt-2 px-2" default-expanded-names="placeholders">
    <NCollapseItem :title="__('Placeholders', 'shopmagic-for-woocommerce')" name="placeholders">
      <PlaceholdersSearch />
    </NCollapseItem>
    <NCollapseItem :title="__('Guide', 'shopmagic-for-woocommerce')">
      <HelpSearch />
    </NCollapseItem>
    <NCollapseItem :title="__('Settings', 'shopmagic-for-woocommerce')">
      <NFormItem
        :label="__('Automation published', 'shopmagic-for-woocommerce')"
        label-placement="left"
      >
        <NSwitch :value="publish" @update:value="$emit('update:publish', $event)" />
      </NFormItem>
      <NFormItem :label="__('Title', 'shopmagic-for-woocommerce')">
        <NInput
          :placeholder="__('My awesome automation')"
          :value="automation?.name"
          @update:value="$emit('update:name', $event)"
        />
      </NFormItem>
      <NFormItem
        v-if="modules.includes('multilingual-module')"
        :label="__('Language', 'shopmagic-for-woocommerce')"
      >
        <LanguageSelector
          :value="automation?.language"
          @update:value="$emit('update:language', $event)"
        />
      </NFormItem>
      <NFormItem
        v-if="modules.includes('multilingual-module')"
        :label="__('Set a parent for this automation', 'shopmagic-for-woocommerce')"
      >
        <AutomationSearch
          :default-options="
            parentAutomationRef
              ? [
                  {
                    label: parentAutomationRef?.name,
                    value: parentAutomationRef?.id,
                  },
                ]
              : []
          "
          v-if="modules.includes('multilingual-module')"
          :value="automation?.parent"
          @update:value="$emit('update:parent', $event)"
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
