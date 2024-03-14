<script lang="ts" setup>
import { Line } from "vue-chartjs";
import type { ChartDataset, ChartOptions } from "chart.js";
import {
  CategoryScale,
  Chart as ChartJS,
  Filler,
  Legend,
  LinearScale,
  LineElement,
  PointElement,
  TimeScale,
  Tooltip,
} from "chart.js";

ChartJS.register(
  Filler,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  TimeScale,
  PointElement,
  LineElement,
);
defineProps<{
  labels: string[];
  datasets: ChartDataset[];
}>();

const options: ChartOptions = {
  aspectRatio: 1.4,
  elements: {
    line: {
      tension: 0.25,
    },
  },
  interaction: {
    mode: "nearest",
  },
  plugins: {
    legend: {
      display: false,
    },
  },
  scales: {
    y: {
      suggestedMax: 10,
      grid: {
        display: false,
      },
      beginAtZero: true,
    },
    x: {
      suggestedMax: 10,
      grid: {
        display: false,
      },
      type: "time",
      time: {
        tooltipFormat: "MMM dd, yyyy",
        unit: "day",
        stepSize: 7,
      },
      ticks: {
        maxTicksLimit: 5,
        autoSkip: false,
      },
    },
  },
};
</script>
<template>
  <Line
    :data="{
      labels,
      datasets,
    }"
    :options="options"
  />
</template>
