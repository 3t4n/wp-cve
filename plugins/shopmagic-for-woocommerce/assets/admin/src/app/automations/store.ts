import { defineStore, storeToRefs } from "pinia";
import type { Automation } from "@/types/automation";
import { useAutomationResourcesStore } from "./resourceStore";
import type { Query } from "@/_utils";
import { get } from "@/_utils";
import { useWpFetch } from "@/composables/useWpFetch";
import { computed, ref, unref, watch, watchEffect } from "vue";
import { appendSearchParams } from "@/composables/useSearchParams";
import useSWRV from "@/_utils/swrv";

export function revalidateAutomations() {
  void useAutomationCollectionStore().revalidateAutomations();
}

export async function fetchAutomations(query: Query): Promise<Automation[]> {
  return useAutomationCollectionStore().fetchItems(query);
}

export async function findAutomationsBy(query: Query): Promise<Automation[]> {
  return useAutomationCollectionStore().findBy(query);
}

export function getAutomations() {
  return storeToRefs(useAutomationCollectionStore()).automations;
}

export const useAutomationCollectionStore = defineStore("automationCollection", () => {
  const automationsUrl = ref<string | null>(null);
  const countUrl = ref<string | null>(null);
  const loading = ref(false);
  const { data, mutate } = useSWRV<Automation[]>(() => automationsUrl.value);

  watch(data, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });

  const { data: count } = useSWRV<number>(countUrl);

  /**
   * Get automations without side effects.
   */
  async function findBy(query: Query) {
    return get<Automation[]>(appendSearchParams("/automations", query));
  }

  /**
   * Fetch automations and put it in the global store. Be careful, as this function should be
   * called only if you mean to modify the content displayed to the user.
   */
  async function fetchItems(queryArgs?: Query) {
    loading.value = true;
    const previousUrl = unref(automationsUrl);
    if (queryArgs) {
      automationsUrl.value = appendSearchParams("/automations", queryArgs);
      if (queryArgs.filters) {
        countUrl.value = appendSearchParams("/automations/count", queryArgs);
      }
    } else {
      automationsUrl.value = "/automations";
      countUrl.value = "/automations/count";
    }
    if (previousUrl === automationsUrl.value) {
      loading.value = false;
    }
    return new Promise((resolve) => {
      watchEffect(() => {
        if (data.value !== undefined) {
          resolve(data.value);
        }
      });
    });
  }

  async function revalidateAutomations() {
    loading.value = true;
    await mutate().finally(() => {
      loading.value = false;
    });
  }

  async function downloadAutomations(ids: number[]) {
    findBy({ filters: { ids: ids } }).then((automations: Automation[]) => {
      const exportAutomations = automations.map((automation) => {
        return assign(unref(automation), { id: null });
      });
      const blob = new Blob([JSON.stringify(exportAutomations)], {
        type: "application/json",
      });
      const url = URL.createObjectURL(blob);
      const anchor = document.createElement("a");
      anchor.href = url;
      anchor.download = "automations";
      document.body.appendChild(anchor);
      anchor.click();
      document.body.removeChild(anchor);
      URL.revokeObjectURL(url);
    });
  }

  const assign = <T extends object>(...sources: T[]) => {
    return sources.reduce((result, current) => {
      return {
        ...result,
        ...current,
      };
    }, {});
  };

  async function deleteAutomations(ids: number[]): Promise<{ deleted: number; errors: number }> {
    loading.value = true;
    return Promise.allSettled(
      ids.map(async (id) => {
        const { data, error } = await useWpFetch(`/automations/${id}`).delete();
        if (error.value instanceof Error) {
          throw error.value;
        }
        return data.value;
      }),
    )
      .then((values) => {
        void mutate();
        return values.reduce(
          (prev: { deleted: number; errors: number }, curr) => {
            if (curr.status === "rejected") {
              return {
                deleted: prev.deleted || 0,
                errors: prev.errors + 1,
              };
            } else if (curr.status === "fulfilled") {
              return {
                deleted: prev.deleted + 1,
                errors: prev.errors || 0,
              };
            }
          },
          { deleted: 0, errors: 0 },
        );
      })
      .finally(() => (loading.value = false));
  }

  const toArray = computed(() => {
    if (typeof data.value === "undefined") {
      return [];
    }
    const { events, actions: actionsStore } = storeToRefs(useAutomationResourcesStore());
    return data.value.map((automation) => {
      const event = events.value?.find((e) => automation?.event.name === e.value);
      const actions = actionsStore.value
        ?.filter((a) => {
          return automation?.actions.map((au) => au.name).includes(a.value);
        })
        .map((a) => a.label as string);
      return {
        ...automation,
        event: event?.label || "No event selected",
        actions,
      };
    });
  });
  return {
    automations: data,
    toArray,
    loading,
    count,
    fetchItems,
    deleteAutomations,
    downloadAutomations,
    revalidateAutomations,
    findBy,
  };
});
