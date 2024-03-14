import { acceptHMRUpdate, defineStore } from "pinia";
import type { Query } from "@/_utils";
import { ref, unref, watch } from "vue";
import useSWRV from "@/_utils/swrv";
import { appendSearchParams } from "@/composables/useSearchParams";
import { useWpFetch } from "@/composables/useWpFetch";

export const useCartsStore = defineStore("carts", () => {
  const cartsUrl = ref("/carts");
  const countUrl = ref("/carts/count");
  const loading = ref(true);
  const { data, mutate } = useSWRV(cartsUrl);
  const { data: count } = useSWRV(countUrl);

  watch(data, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  function fetchItems(queryArgs?: Query) {
    loading.value = true;
    const previousUrl = unref(cartsUrl);
    if (queryArgs) {
      cartsUrl.value = appendSearchParams("/carts", queryArgs);
      if (queryArgs.filters) {
        countUrl.value = appendSearchParams("/carts/count", queryArgs);
      }
    } else {
      cartsUrl.value = "/carts";
      countUrl.value = "/carts/count";
    }
    if (previousUrl === cartsUrl.value) {
      loading.value = false;
    }
  }

  function deleteCarts(ids: number[]) {
    loading.value = true;
    Promise.all(ids.map((id) => useWpFetch(`/carts/${id}`).delete()))
      .then(
        async () => mutate(),
        () => {},
      )
      .finally(() => (loading.value = false));
  }

  return { carts: data, loading, count, fetchItems, delete: deleteCarts };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useCartsStore, import.meta.hot));
}
