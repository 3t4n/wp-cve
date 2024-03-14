<script lang="ts" setup>
import { NA, NButton, NP, NText, NIcon, NProgress, NSkeleton, NSpace, NSpin, NTable } from "naive-ui";
import { Checkmark, CloseOutline } from "@vicons/ionicons5";
import { get } from "@/_utils";
import { computed, ref, watchEffect } from "vue";
import ShadyCard from "@/components/ShadyCard.vue";
import EditGroup from "../components/EditGroup.vue";
import { useAutomationResourcesStore } from "../resourceStore";
import { useWpFetch } from "@/composables/useWpFetch";
import { useSingleAutomation } from "@/app/automations/singleAutomation";
import { storeToRefs } from "pinia";
import { __, sprintf } from "@/plugins/i18n";

const { get: getAutomation } = useSingleAutomation();
const { automation } = storeToRefs(useSingleAutomation());
const { getAction } = useAutomationResourcesStore();

const props = defineProps<{
  id: number;
}>();

const STATES = {
  NOT_INITIALIZED: "notInitialized",
  LOADING: "loading",
  ERROR: "error",
  SUCCESS: "success",
} as const;

const matchingItems = ref([]);
const matchingErrors = ref([]);
const maxPossibleMatches = ref(0);
const matchesLoading = ref(true);
const queueDispatched = ref(STATES.NOT_INITIALIZED);
const BATCH_SIZE = 250;
const page = ref(1);
const maxPages = computed(() => Math.ceil(maxPossibleMatches.value / BATCH_SIZE));
const dispatcherError = ref<object | null>(null);
const automationName = computed(() => (
  automation.value?.name ||
    automation.value?.id && sprintf(__('Automation #%d'), automation.value?.id))
);
const automationActions = computed(() => {
  const rawActions = automation.value?.actions || [];
  return rawActions.map((action) => {
    const actionResource = getAction(action.name);
    return {
      ...action,
      name: actionResource?.label,
      description: action.settings?.description || "",
    };
  });
});
const processed = ref(0);
const processing = computed(() => Math.ceil((processed.value / maxPages.value) * 100));

watchEffect(() => {
  getAutomation(props.id);

  get<number>(`/automations/${props.id}/manual/max`)
    .then((max) => {
      maxPossibleMatches.value = max;
      if (max === 0) {
        matchesLoading.value = false;
      }
    })
    .then(async () => {
      /**
       * With ShopMagic Manual Actions 1.7.0 there's a new endpoint to count matches which envelopes data
       * and collects errors. Check if we are able to use it.
       */
      async function determineApiMatchEndpoint() {
        const { response } = await useWpFetch(`/automations/${props.id}/manual/match`).head()
        if ( response.value.ok ) {
          return `/automations/${props.id}/manual/match`
        }

        return `/automations/${props.id}/manual/matches`
      }
      const apiEndpoint = await determineApiMatchEndpoint();
      do {
        // TODO: abort controller when user leaves page
        await fetchPreviewRuns(apiEndpoint);
      } while (page.value <= maxPages.value)
    })
    .then(() => matchesLoading.value = false);
});

watchEffect(() => {
  if ( matchingItems.value.length !== 0 ) {
    matchesLoading.value = false
  }
})

async function fetchPreviewRuns(apiMatchEndpoint: string) {
  const CONCURRENT_REQS = 5;
  const batchSize = Math.min(CONCURRENT_REQS, maxPages.value - page.value + 1);
  const promises = Array.from({length: batchSize}, (_, i) => {
    return get(`${apiMatchEndpoint}?page_size=${BATCH_SIZE}&page=${page.value
+ i}`)
  });
  const result = await Promise.all(promises);
  page.value += batchSize;
  if( apiMatchEndpoint.endsWith(`automations/${props.id}/manual/match`) ) {
    for (const {data: matches, errors: matchErrors} of result) {
      matchingItems.value.push(...matches);
      matchingErrors.value.push(...matchErrors);
      processed.value++;
    }
  } else {
    for (const matches of result) {
      matchingItems.value.push(...matches);
      processed.value++;
    }
  }
}

async function dispatchAutomations() {
  if (queueDispatched.value === STATES.SUCCESS) {
    return;
  }
  queueDispatched.value = STATES.LOADING;
  const { data: response, error: responseError } = useWpFetch(
    `/automations/${props.id}/manual/run`,
  ).post(
    {
      resources: matchingItems.value.map(({ id, object }) => {
        return { id, object };
      }),
    },
    "json",
  );

  watchEffect(() => {
    if (response.value === undefined) {
      queueDispatched.value = STATES.LOADING;
      return;
    }

    if (!responseError.value) {
      queueDispatched.value = STATES.SUCCESS;
      return;
    }

    queueDispatched.value = STATES.ERROR;
    dispatcherError.value = JSON.parse(response);
    return;
  });
}
</script>
<template>
  <EditGroup>
    <ShadyCard>
      <template #header>
        {{ __("Manual trigger sources for:", 'shopmagic-for-woocommerce') + " " }}
        <span v-if="automationName">
          {{ automationName }}
        </span>
        <NSkeleton v-else :width="150" text></NSkeleton>
      </template>
      <NSpin :show="matchesLoading">
        <ul class="h-[300px] overflow-y-scroll shadow-inner p-1">
          <li v-if="!matchesLoading && matchingItems.length === 0" class="p-3">
            <NText type="warning">
              {{ __('There are no data sources matching current automation settings. Make sure your settings are correct and rerun the process.', 'shopmagic-for-woocommerce') }}
            </NText>
          </li>
          <li v-for="item in matchingItems" :key="item.id" class="p-1 rounded [&:nth-child(2n)]:bg-gray-50">
            <NA v-if="item.link" :href="item.link" target="_blank">
              {{ item.name }} #{{ item.id }}
            </NA>
            <span v-else> {{ item.name }} #{{ item.id }} </span>
          </li>
        </ul>
      </NSpin>
      <template #action>
        <NP>{{ sprintf( __( "Total items: %d", 'shopmagic-for-woocommerce' ), matchingItems.length ) }}</NP>
        <NP type="error" v-if="matchingErrors.length > 0">
          <NText type="error">
            {{ sprintf( _n("There was %d error, when trying to match sources applicable for automation.", "There were %d errors, when trying to match sources applicable for automation.", matchingErrors.length, 'shopmagic-for-woocommerce'), matchingErrors.length ) }} {{ __("You still can execute the automation with all errorneous sources skipped. Otherwise, change your automation settings or check ShopMagic's error logs.", 'shopmagic-for-woocommerce') }}
          </NText>
        </NP>
        <NProgress
          :height="12"
          :percentage="processing"
          :processing="processing !== 100"
          :show-indicator="false"
          type="line"
        />
      </template>
    </ShadyCard>
    <ShadyCard :title="__('Actions', 'shopmagic-for-woocommerce')">
      <NTable>
        <thead>
          <tr>
            <th>{{ __("Type", "shopmagic-for-woocommerce") }}</th>
            <th>{{ __("Description", "shopmagic-for-woocommerce") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="automationActions.length === 0">
            <td>
              <NSkeleton :width="120" text />
            </td>
            <td>
              <NSkeleton :width="150" text />
            </td>
          </tr>
          <tr v-for="(action, i) in automationActions" :key="i">
            <td>{{ action.name }}</td>
            <td>{{ action.description }}</td>
          </tr>
        </tbody>
      </NTable>
    </ShadyCard>
    <NSpace>
      <NButton type="primary" @click="dispatchAutomations">
        <NSpin :show="queueDispatched === STATES.LOADING" :size="12" stroke="#eaeaea">
          <span v-if="queueDispatched === STATES.NOT_INITIALIZED">{{ __("Run actions", 'shopmagic-for-woocommerce' ) }}</span>
          <span v-else-if="queueDispatched === STATES.SUCCESS">
            <NIcon><Checkmark /> </NIcon>
          </span>
          <span v-else-if="queueDispatched === STATES.ERROR">
            <NIcon><CloseOutline /> </NIcon>
          </span>
        </NSpin>
      </NButton>
      <RouterLink :to="{ name: 'automation', params: props }">
        <NButton>{{ __("Return to automation editor", "shopmagic-for-woocommerce") }}</NButton>
      </RouterLink>
    </NSpace>
  </EditGroup>
</template>
