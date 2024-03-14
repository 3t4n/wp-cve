<script lang="ts" setup>
import {
  NA,
  NButton,
  NCard,
  NGrid,
  NGridItem,
  NH1,
  NIcon,
  NLayout,
  NLayoutContent,
  NLayoutFooter,
  NLayoutSider,
  NMenu,
  NNumberAnimation,
  NP,
  NSkeleton,
  NSpace,
  NStatistic,
  NText,
} from "naive-ui";
import { computed, h, inject, ref, watchEffect } from "vue";
import { useChartGradient } from "@/composables/useChartGradient";
import { useFetch } from "@vueuse/core";
import StatsCard from "@/components/Chart/StatsCard.vue";
import DataTable from "@/components/Table/DataTable.vue";
import ShadyCard from "@/components/ShadyCard.vue";
import { shortOutcomeColumns } from "@/app/logs/data/table";
import { useSingleAutomation } from "@/app/automations/singleAutomation";
import { RouterLink } from "vue-router";
import { modulesKey, userKey } from "@/provide";
import { sprintf } from "@/plugins/i18n";
import dashboardImg from "../../../assets/dashboard.svg?raw";
import useSWRV from "@/_utils/swrv";
import originalSWRV from "swrv";
import { ChevronForwardOutline } from "@vicons/ionicons5";
import { useSearchParams } from "@/composables/useSearchParams";

const { addAutomation } = useSingleAutomation();

const { data: outcomesData } = useSWRV(
  `/outcomes?${useSearchParams({
    pageSize: 3,
    filters: { status: "completed" },
  })}`,
);

type ChartData = {
  labels: string[];
  plot: number[] | { [k: string]: number }[];
};

const { data: topStats } = useSWRV("/analytics/top-stats");

type WpResponse = {
  title: {
    rendered: string;
  };
  content: {
    rendered: string;
  };
};
const { data: monthTips } = originalSWRV<WpResponse[]>(
  "https://shopmagic.app/wp-json/wp/v2/shopmagic-ads?per_page=1",
);
const tipOfTheMonth = ref<WpResponse | null>(null);

watchEffect(() => {
  if (monthTips.value) {
    tipOfTheMonth.value = monthTips.value.at(0) || null;
  }
});

const { data: outcomes } = useSWRV<ChartData>("/analytics/outcomes/aggregate");
const { data: emails } = useSWRV<ChartData>("/analytics/emails/aggregate");

const modules = inject(modulesKey);

const carts = ref<ChartData>({
  labels: [],
  plot: [],
});
if (modules.includes("shopmagic-abandoned-carts")) {
  const { data: cartsData } = useSWRV<ChartData>("/analytics/carts/aggregate");
  carts.value = cartsData.value;
}

const clicks = computed(() => Object.values(emails.value?.plot || {}).map(({ clicks }) => clicks));
const mails = computed(() => Object.values(emails.value?.plot || {}).map(({ sent }) => sent));
const opens = computed(() => Object.values(emails.value?.plot || {}).map(({ opens }) => opens));

const { data: posts } = originalSWRV("https://shopmagic.app/wp-json/wp/v2/posts?per_page=3");

const apiKey = "0e51628719d28d86f341edcbc4acdba9c463fafc";
const siteId = "5e305c242c7d3a7e9ae6db3e";
const gettingStartedCollection = "5e96112c2c7d3a7e9aeaede9";
const { data: docs } = useFetch(
  `https://docsapi.helpscout.net/v1/collections/${gettingStartedCollection}/articles?pageSize=3`,
  {
    beforeFetch: ({ options }) => {
      options.headers = {
        ...options.headers,
        Authorization: `Basic ${btoa(apiKey + ":X")}`,
      };
      return {
        options,
      };
    },
  },
).json();

const user = inject(userKey);
const articles = computed(() => docs.value?.articles.items);

function chevronIcon() {
  return () => h(NIcon, null, { default: () => h(ChevronForwardOutline) });
}
</script>
<template>
  <NLayout class="bg-transparent">
    <NLayout class="bg-transparent" content-style="margin-bottom: 24px;" has-sider>
      <NLayoutContent class="bg-transparent" content-style="padding-right: 24px">
        <NGrid :cols="3" :x-gap="24" :y-gap="24">
          <NGridItem :span="3">
            <NCard>
              <div class="flex flex-row items-center justify-between gap-4">
                <div class="max-w-2xl">
                  <NH1 v-if="user.name">{{
                    sprintf(
                      __("Great to have you onboard, %s!", "shopmagic-for-woocommerce"),
                      user.name,
                    )
                  }}</NH1>
                  <NH1 v-else>{{
                    __("Great to have you onboard!", "shopmagic-for-woocommerce")
                  }}</NH1>
                  <NP>
                    {{
                      __(
                        "It's the best place to create new automation, check logs and analytics, as well as access documentation. Not&nbsp;to mention our blog, and marketing lists.",
                        "shopmagic-for-woocommerce",
                      )
                    }}
                  </NP>
                  <NP>
                    {{ __("Have a fruitful day!", "shopmagic-for-woocommerce") }}
                  </NP>
                  <RouterLink
                    :to="{ name: 'automation', params: { id: 'new' } }"
                    @click="addAutomation"
                  >
                    <NButton type="primary">
                      {{ __("Create automation", "shopmagic-for-woocommerce") }}
                    </NButton>
                  </RouterLink>
                </div>
                <div v-html="dashboardImg" />
              </div>
            </NCard>
          </NGridItem>
          <NGridItem :span="1">
            <StatsCard
              :datasets="[
                {
                  data: outcomes?.plot || [],
                  label: __('Outcomes', 'shopmagic-for-woocommerce'),
                  backgroundColor: ({ chart }) => useChartGradient(chart),
                  borderColor: 'rgba(80,200,120,0.36)',
                  labelMarkColor: '#50C878',
                  fill: true,
                },
              ]"
              :labels="outcomes?.labels || []"
            >
              <template #tooltip
                >{{
                  __(
                    "Sum of all successfully and unsuccessfully sent emails and automations in the last month",
                    "shopmagic-for-woocommerce",
                  )
                }}
              </template>
            </StatsCard>
          </NGridItem>
          <NGridItem :span="1">
            <StatsCard
              :datasets="[
                {
                  label: __('Sent mails', 'shopmagic-for-woocommerce'),
                  data: mails,
                  backgroundColor: '#8bc99b',
                  borderColor: '#8bc99b',
                  fill: false,
                },
                {
                  label: __('Opened mails', 'shopmagic-for-woocommerce'),
                  data: opens,
                  backgroundColor: '#80b0c4',
                  borderColor: '#80b0c4',
                  fill: false,
                },
                {
                  label: __('Clicks', 'shopmagic-for-woocommerce'),
                  data: clicks,
                  backgroundColor: '#ccaf74',
                  borderColor: '#ccaf74',
                  fill: false,
                },
              ]"
              :labels="emails?.labels || []"
            >
              <template #tooltip>
                <p>
                  <b>{{ __("Sent Mails", "shopmagic-for-woocommerce") }}</b
                  >:
                  {{
                    __(
                      "Sum of all successfully sent and delivered emails in the last month.",
                      "shopmagic-for-woocommerce",
                    )
                  }}
                </p>
                <p>
                  <b>{{ __("Opened Mails", "shopmagic-for-woocommerce") }}</b
                  >:
                  {{
                    __("Sum of all emails opened by their recipients.", "shopmagic-for-woocommerce")
                  }}
                </p>
                <p>
                  <b>{{ __("Clicks", "shopmagic-for-woocommerce") }}</b
                  >:
                  {{
                    __(
                      "Sum of all clicks on the links included in emails during the last month.",
                      "shopmagic-for-woocommerce",
                    )
                  }}
                </p>
              </template>
            </StatsCard>
          </NGridItem>
          <NGridItem :span="1">
            <StatsCard
              :datasets="[
                {
                  label: __('Active carts', 'shopmagic-for-woocommerce'),
                  data: carts?.plot || [],
                  labelMarkColor: '#50C878',
                },
              ]"
              :labels="carts?.labels || []"
            >
              <template #tooltip>
                {{
                  __(
                    "Number of users who have not made a purchase in the past month but their carts contain products",
                    "shopmagic-for-woocommerce",
                  )
                }}
              </template>
            </StatsCard>
          </NGridItem>
        </NGrid>
      </NLayoutContent>
      <NLayoutSider :width="320" class="bg-transparent">
        <NSpace :size="24" vertical>
          <ShadyCard :title="__('Knowledge base', 'shopmagic-for-woocommerce')" size="small">
            <ul>
              <li v-for="(post, i) in posts" :key="i">
                <NA :href="post.link" target="_blank">
                  <span v-html="post.title.rendered" />
                </NA>
              </li>
            </ul>
          </ShadyCard>
          <ShadyCard :title="__('Documentation', 'shopmagic-for-woocommerce')" size="small">
            <ul>
              <li v-for="(doc, i) in articles" :key="i">
                <NA :href="doc.publicUrl" target="_blank">
                  {{ doc.name }}
                </NA>
              </li>
            </ul>
          </ShadyCard>
          <ShadyCard :title="__('Tip of the month 💡', 'shopmagic-for-woocommerce')" size="small">
            <NText strong>{{ tipOfTheMonth?.title.rendered }}</NText>
            <div v-html="tipOfTheMonth?.content.rendered" />
          </ShadyCard>
        </NSpace>
      </NLayoutSider>
    </NLayout>
    <NLayoutFooter class="bg-transparent">
      <NGrid :cols="12" :x-gap="24" :y-gap="32">
        <NGridItem :span="3">
          <ShadyCard :title="__('Quick links', 'shopmagic-for-woocommerce')">
            <NMenu
              :indent="4"
              :options="[
                {
                  label: () =>
                    h(
                      RouterLink,
                      {
                        to: {
                          name: 'marketing-list',
                          params: {
                            id: 'new',
                          },
                        },
                      },
                      {
                        default: () => __('Add new subscribers list', 'shopmagic-for-woocommerce'),
                      },
                    ),
                  key: 'add-subscriber-list',
                  icon: chevronIcon(),
                },
                {
                  label: () =>
                    h(
                      RouterLink,
                      {
                        to: {
                          name: 'lists',
                        },
                      },
                      {
                        default: () => __('View subscribers lists', 'shopmagic-for-woocommerce'),
                      },
                    ),
                  key: 'subscribers-list',
                  icon: chevronIcon(),
                },
                {
                  label: () =>
                    h(
                      RouterLink,
                      {
                        to: {
                          name: 'subscribers',
                        },
                      },
                      {
                        default: () => __('View subscribers', 'shopmagic-for-woocommerce'),
                      },
                    ),
                  key: 'subscribers',
                  icon: chevronIcon(),
                },
              ]"
            ></NMenu>
          </ShadyCard>
        </NGridItem>
        <NGridItem :span="6">
          <ShadyCard :title="__('Outcomes log', 'shopmagic-for-woocommerce')">
            <DataTable
              :bordered="false"
              :bottom-bordered="false"
              :columns="shortOutcomeColumns"
              :data="outcomesData || []"
              :show-pagination="false"
            />
            <template #header-extra>
              <RouterLink :to="{ name: 'outcomes' }">
                {{ __("View all", "shopmagic-for-woocommerce") }}
              </RouterLink>
            </template>
          </ShadyCard>
        </NGridItem>
        <NGridItem :span="3">
          <div class="grid grid-cols-2 gap-4">
            <ShadyCard
              v-for="(stat, k) in topStats?.top_stats || new Array(4)"
              :key="k"
              content-style="text-align: center;"
            >
              <NStatistic :label="stat?.name">
                <template v-if="stat?.value !== 0" #label>
                  <NSkeleton :width="140" text />
                </template>
                <NNumberAnimation :active="stat?.value !== 0" :from="0" :to="stat?.value" />
              </NStatistic>
            </ShadyCard>
          </div>
        </NGridItem>
      </NGrid>
    </NLayoutFooter>
  </NLayout>
</template>
