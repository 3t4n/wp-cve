<script lang="ts" setup>
import { Line } from "vue-chartjs";
import {
  CategoryScale,
  Chart as ChartJS,
  Legend,
  LinearScale,
  LineElement,
  PointElement,
  TimeScale,
  Title,
  Tooltip,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  TimeScale,
  PointElement,
  LineElement,
);
defineProps<{ labels: string[]; plot: number[] }>();
</script>
<template>
  <Line
    :data="{
      labels,
      datasets: [
        {
          label: 'My First Dataset',
          data: plot,
          borderColor: 'rgb(75, 192, 192)',
        },
      ],
    }"
    :options="{
      elements: {
        line: {
          tension: 0.25,
          fill: true,
        },
      },
      plugins: {
        legend: {
          display: false,
        },
      },
      scales: {
        y: {
          grid: {
            display: false,
          },
          beginAtZero: true,
        },
        x: {
          grid: {
            display: false,
          },
          type: 'time',
          time: {
            unit: 'day',
          },
          padding: 10,
          ticks: {
            major: {
              enabled: true,
            },
          },
        },
      },
    }"
  />
</template>
