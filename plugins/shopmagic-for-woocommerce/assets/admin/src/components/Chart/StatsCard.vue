<script lang="ts" setup>
import { NButton, NNumberAnimation, NSpace, NStatistic, NTooltip } from "naive-ui";
import { InformationCircleOutline } from "@vicons/ionicons5";
import MultipleLinesChart from "@/components/Chart/MultipleLinesChart.vue";
import type { ChartDataset } from "chart.js";
import ShadyCard from "@/components/ShadyCard.vue";

defineProps<{
  datasets: ChartDataset[];
  labels: string[];
}>();
</script>
<template>
  <ShadyCard>
    <template #header>
      <NSpace :size="[24, 8]">
        <NStatistic v-for="(header, i) in datasets" :key="i">
          <template #label>
            <div class="flex items-center align-center gap-1">
              <div
                :style="{
                  backgroundColor: header?.labelMarkColor || header.backgroundColor || '#50C878',
                }"
                class="w-[8px] h-[8px] rounded-full"
              ></div>
              {{ header.label }}
            </div>
          </template>
          <NNumberAnimation
            :active="header.data.reduce((acc, i) => acc + i, 0) !== 0"
            :from="0"
            :to="header.data.reduce((acc, i) => acc + i, 0)"
          />
        </NStatistic>
      </NSpace>
    </template>
    <template v-if="$slots.tooltip" #header-extra>
      <NTooltip :width="200" trigger="click">
        <template #trigger>
          <NButton text>
            <template #icon> <InformationCircleOutline /> </template>
          </NButton>
        </template>
        <slot name="tooltip" />
      </NTooltip>
    </template>
    <MultipleLinesChart :datasets="datasets" :labels="labels" />
  </ShadyCard>
</template>
