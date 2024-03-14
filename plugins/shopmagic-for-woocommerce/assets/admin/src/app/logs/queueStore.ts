import { acceptHMRUpdate, defineStore } from "pinia";
import { ref, unref, watch } from "vue";
import type { Query } from "@/_utils";
import { appendSearchParams } from "@/composables/useSearchParams";
import { useWpFetch } from "@/composables/useWpFetch";
import useSWRV from "@/_utils/swrv";

export const useQueueStore = defineStore("queue", () => {
  const url = ref<string | null>(null);
  const countUrl = ref<string | null>(null);
  const loading = ref(false);
  const { data: queue, error, mutate } = useSWRV(url);
  const { data: queueTotal } = useSWRV(countUrl);

  watch(queue, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  function getQueue(query?: Query) {
    loading.value = true;
    const previousUrl = unref(url);
    if (query) {
      url.value = appendSearchParams("/queue", query);
      if (query.filters) {
        countUrl.value = appendSearchParams("/queue/count", query);
      }
    } else {
      url.value = `/queue`;
      countUrl.value = `/queue/count`;
    }
    if (previousUrl === url.value) {
      loading.value = false;
    }
  }

  async function cancelQueue(id: number) {
    loading.value = true;
    const { data /** error */ } = await useWpFetch(`/queue/${id}`).delete();
    await mutate();
    loading.value = false;

    return data;
  }

  return {
    queue,
    queueTotal,
    loading,
    error,
    getQueue,
    cancelQueue,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useQueueStore, import.meta.hot));
}
