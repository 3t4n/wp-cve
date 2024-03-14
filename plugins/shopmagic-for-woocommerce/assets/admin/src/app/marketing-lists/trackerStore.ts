import { defineStore } from "pinia";
import useSWRV from "@/_utils/swrv";
import { computed, ref, watchEffect } from "vue";
import useSwrvState from "@/composables/useSwrvState";

export const useTrackerStore = defineStore("tracker", () => {
  const loading = ref(false);
  const perAutomation = ref([]);

  const automations = computed(() => {
    const { data } = useSWRV("/automations/stats");

    return data.value;
  });

  const customers = computed(() => {
    const { data } = useSWRV("/clients/stats");

    return data.value;
  });

  function getAutomationStats() {
    loading.value = true;
    const { data: automationStats, error, isValidating } = useSWRV("/automations/stats");
    const { isAwating } = useSwrvState(automationStats, error, isValidating);

    watchEffect(() => {
      if (!isAwating.value) {
        perAutomation.value = automationStats.value;
        loading.value = false;
      }
    });
  }

  return {
    automations,
    customers,
    perAutomation,
    getAutomationStats,
    loading,
  };
});
