import { defineStore } from "pinia";
import useSWRV from "@/_utils/swrv";
import { ref, unref, watch } from "vue";
import type { Query } from "@/_utils";
import { appendSearchParams } from "@/composables/useSearchParams";
import { useWpFetch } from "@/composables/useWpFetch";

export const useOutcomesStore = defineStore("outcomesStore", () => {
  const url = ref("/outcomes");
  const countUrl = ref("/outcomes/count");
  const { data: outcomes, mutate } = useSWRV(url);
  // loading initially is true, as we fetch data immediately.
  const loading = ref(true);
  const { data: count } = useSWRV(countUrl);

  watch(outcomes, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  function fetch(query?: Query) {
    loading.value = true;
    const previousUrl = unref(url);
    if (query) {
      url.value = appendSearchParams("/outcomes/", query);
      if (query.filters) {
        countUrl.value = appendSearchParams("/outcomes/count/", query);
      }
    } else {
      url.value = `/outcomes`;
      countUrl.value = `/outcomes/count`;
    }
    if (previousUrl === url.value) {
      loading.value = false;
    }
  }

  function deleteOutcomes(ids: number[]) {
    loading.value = true;
    Promise.all(ids.map((id) => useWpFetch(`/outcomes/${id}`).delete()))
      .then(
        async () => mutate(),
        () => {},
      )
      .finally(() => (loading.value = false));
  }

  return {
    outcomes,
    count,
    loading,
    fetch,
    deleteOutcomes,
  };
});
