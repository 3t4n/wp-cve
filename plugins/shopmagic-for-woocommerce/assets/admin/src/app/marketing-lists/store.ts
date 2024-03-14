import { acceptHMRUpdate, defineStore } from "pinia";
import { get, type Query } from "@/_utils";
import { ref, unref, watch } from "vue";
import type { List } from "./types";
import { useWpFetch } from "@/composables/useWpFetch";
import { appendSearchParams } from "@/composables/useSearchParams";
import useSWRV from "@/_utils/swrv";

function createNullList(): List {
  return {
    id: null,
    name: null,
    type: "opt_in",
    subscribers: [],
    status: "draft",
    checkout: {
      checkout_available: true,
      checkout_description: "",
      checkout_label: "",
    },
  };
}

export async function removeList(id: number) {
  const store = useMarketingStore();
  return store.remove(id).finally(store.revalidate);
}

export const useMarketingStore = defineStore("marketingStore", () => {
  const url = ref<string | null>(null);
  const countUrl = ref<string | null>(null);
  const { data: lists, mutate } = useSWRV<List[]>(url);
  const { data: listsTotal } = useSWRV<number>(countUrl);
  const list = ref<List | null>(null);
  const loading = ref(false);
  const error = ref<any | null>(null);

  watch(lists, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  function revalidate() {
    loading.value = true;
    void mutate().finally(() => {
      loading.value = false;
    });
  }

  function fetchItems(queryArgs?: Query) {
    loading.value = true;
    const prevUrl = unref(url);
    if (queryArgs) {
      url.value = appendSearchParams("/lists", queryArgs);
      if (queryArgs.filters) {
        countUrl.value = appendSearchParams("/lists/count", queryArgs);
      }
    } else {
      url.value = "/lists";
      countUrl.value = "/lists/count";
    }
    if (prevUrl === url.value) {
      loading.value = false;
    }
  }

  async function getListsByName(name: string) {
    return get<List[]>(appendSearchParams("/lists", { filters: { name } }));
  }

  async function getList(id: number) {
    if (lists.value !== undefined) {
      const cachedList = lists.value.find((a) => a.id === id);

      if (cachedList !== undefined) {
        return list;
      }
    }
    return get<List>(`/lists/${id}`);
  }

  async function useList(id: number) {
    if (lists.value !== undefined) {
      const cachedList = lists.value.find((a) => a.id === id);

      if (cachedList !== undefined) {
        list.value = cachedList;
        return list;
      }
    }
    list.value = await get<List>(`/lists/${id}`);
    return list;
  }

  function addList() {
    list.value = createNullList();
  }

  async function save() {
    if (list.value === null) {
      console.error("Currently no list to save.");
      return;
    }

    const { data /* error */ } = await useWpFetch("/lists").post(list, "json");
    await mutate();
    return unref(data);
  }

  async function update() {
    const { data /* error */ } = await useWpFetch(`/lists/${list.value.id}`).put(list, "json");
    await mutate();
    return unref(data);
  }

  async function remove(id: number) {
    const { data /* error */ } = await useWpFetch(`/lists/${id}`).delete();
    return unref(data);
  }

  function removeMultiple(ids: number[]) {
    loading.value = true;
    Promise.allSettled(ids.map(remove))
      .then(
        async () => mutate(),
        () => {},
      )
      .finally(() => (loading.value = false));
  }

  return {
    lists,
    listsTotal,
    list,
    error,
    loading,
    useList,
    addList,
    getList,
    save,
    update,
    remove,
    removeMultiple,
    revalidate,
    fetchItems,
    getListsByName,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useMarketingStore, import.meta.hot));
}
