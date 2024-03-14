<script lang="ts" setup>
import { NButton, NCard, NFormItem, NIcon, NInput, NModal, NSelect } from "naive-ui";
import { CloseOutline } from "@vicons/ionicons5";
import { computed, inject, ref } from "vue";
import type { ActionConfig } from "@/types/automationConfig";
import { storeToRefs } from "pinia";
import { useWpFetch } from "@/composables/useWpFetch";
import { useSingleAutomation } from "../../singleAutomation";
import JsonForm from "@/components/JsonForm.vue";
import { userKey } from "@/provide";
import { useFuzzySearch } from "@/composables/useFuzzySearch";

const automationStore = useSingleAutomation();
const { automation } = storeToRefs(automationStore);

function updateData({ data /** errors */ }) {
  automationStore.$patch((state) => {
    state.automation.actions[props.id].settings = data;
  });
}

const props = defineProps<{
  actions: ActionConfig[] | undefined;
  id: number;
}>();

const selectedAction = computed({
  get: () => automation.value?.actions[props.id].name || null,
  set: (actionName: string) => {
    const action = automation.value?.actions[props.id];
    action.name = actionName;
  },
});

const currentAction = computed(() => {
  return props.actions?.find((action) => action.value === selectedAction.value);
});

const actionValues = computed(() => automation.value?.actions[props.id].settings || {});

const emit = defineEmits<{ (e: "remove", id: number): void }>();
const editing = ref(false);

const testLoading = ref(false);
const testSuccess = ref<boolean | null>(null);
const testFailure = ref<{ title: string; detail: string } | null>(null);
async function dispatchTest() {
  testLoading.value = true;
  testSuccess.value = null;
  testFailure.value = null;
  const { data, error } = await useWpFetch(
    `/resources/actions/${currentAction.value?.value}/test`,
  ).post({
    automation: automation.value?.id,
    config: {
      email: emailRecipent.value,
      ...automation.value?.actions[props.id].settings,
    },
  });

  if (error.value === null) {
    testSuccess.value = true;
  } else {
    testSuccess.value = false;
    testFailure.value = JSON.parse(data.value);
  }

  testLoading.value = false;
}

const showModal = ref(false);
const emailRecipent = ref<string | null>(inject(userKey)?.email || null);

const { search, renderLabel, renderTag, matches } = useFuzzySearch(props.actions);
</script>

<template>
  <div class="flex items-center justify-between gap-2">
    <NSelect
      v-model:value="selectedAction"
      :options="matches"
      :placeholder="__('Select action', 'shopmagic-for-woocommerce')"
      :render-label="renderLabel"
      :render-tag="renderTag"
      filterable
      @search="search"
      @update:value="editing = true"
    ></NSelect>
    <NButton tertiary type="info" @click="editing = !editing">{{
      __("Edit", "shopmagic-for-woocommerce")
    }}</NButton>
    <NButton tertiary type="error" @click="emit('remove', props.id)">
      <NIcon>
        <CloseOutline />
      </NIcon>
    </NButton>
  </div>
  <div v-show="editing" class="bg-gray-50 flex flex-col px-8 py-4 gap-y-4">
    <NButton
      v-if="currentAction?.testable"
      secondary
      type="info"
      @click="
        showModal = true;
        testSuccess = null;
        testFailure = null;
      "
    >
      {{ __("Send test", "shopmagic-for-woocommerce") }}
    </NButton>
    <NModal v-if="currentAction?.testable" v-model:show="showModal">
      <NCard
        :bordered="false"
        :title="__('Send test', 'shopmagic-for-woocommerce')"
        aria-modal="true"
        closable
        role="dialog"
        size="huge"
        style="width: 600px"
        @close="showModal = false"
      >
        <NFormItem
          :label="__('Email recipient', 'shopmagic-for-woocommerce')"
          :show-feedback="false"
        >
          <NInput
            v-model:value="emailRecipent"
            :placeholder="__('Email recipient', 'shopmagic-for-woocommerce')"
          />
        </NFormItem>
        <template #action>
          <NButton :loading="testLoading" primary type="primary" @click="dispatchTest">
            {{ __("Send test", "shopmagic-for-woocommerce") }}
          </NButton>
          <div v-if="testSuccess !== null">
            <p v-if="testSuccess">
              {{ __("Test executed successfully! Check your inbox.", "shopmagic-for-woocommerce") }}
            </p>
            <div v-else>
              <p>{{ testFailure.title }}</p>
              <p>{{ __("Possible reason", "shopmagic-for-woocommerce") }}:</p>
              <p>{{ testFailure.detail }}</p>
            </div>
          </div>
        </template>
      </NCard>
    </NModal>
    <JsonForm
      v-if="typeof currentAction?.settings !== 'undefined'"
      :data="actionValues"
      :schema="currentAction.settings"
      @change="updateData"
    />
  </div>
</template>
