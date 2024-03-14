import { defineStore } from "pinia";
import type { Query } from "@/_utils";
import { appendSearchParams } from "@/composables/useSearchParams";
import { ref, watch } from "vue";
import useSWRV from "@/_utils/swrv";
import { useWpFetch } from "@/composables/useWpFetch";

export const useGuestsStore = defineStore("guests", () => {
  const url = ref("/guests");
  const countUrl = ref("/guests/count");
  const { data: guests, mutate } = useSWRV(url);
  const { data: guestsTotal } = useSWRV<number>(countUrl);
  const loading = ref(true);

  watch(guests, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  const error = ref(null);
  function fetchItems(query?: Query) {
    loading.value = true;
    const previousUrl = url.value;
    if (query) {
      url.value = appendSearchParams("/guests", query);
      if (query.filters) {
        countUrl.value = appendSearchParams("/guests/count", query);
      }
    } else {
      url.value = "/guests";
      countUrl.value = "/guests/count";
    }
    if (previousUrl === url.value) {
      loading.value = false;
    }
  }

  function deleteGuests(ids: number[]) {
    loading.value = true;
    Promise.all(ids.map((id) => useWpFetch(`/guests/${id}`).delete()))
      .then(
        async () => mutate(),
        () => {},
      )
      .finally(() => (loading.value = false));
  }

  return {
    guests,
    guestsTotal,
    loading,
    error,
    getGuests: fetchItems,
    deleteGuests,
  };
});
