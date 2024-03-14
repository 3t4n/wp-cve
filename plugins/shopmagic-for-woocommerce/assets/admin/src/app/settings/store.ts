import { defineStore } from "pinia";
import { computed, h, ref, watchEffect } from "vue";
import useSWRV from "@/_utils/swrv";
import { useWpFetch } from "@/composables/useWpFetch";
import useSwrvState from "@/composables/useSwrvState";
import { RouterLink } from "vue-router";
import type { JsonSchema } from "@jsonforms/core";
import { get } from "@/_utils";

type SettingTab = {
  label: string;
  fields: JsonSchema[];
  data: Record<string, any>;
};

type Settings = {
  [k: string]: SettingTab;
};

export const useSettingsStore = defineStore("settingsStore", () => {
  const {
    data: settings,
    error,
    isValidating,
    mutate,
  } = useSWRV<Settings>("/settings", get, { revalidateOnFocus: false });
  const { isAwating } = useSwrvState(settings, error, isValidating);
  const values = ref({});

  watchEffect(() => {
    if (settings.value !== undefined && Object.keys(values.value).length === 0) {
      Object.entries(settings.value).forEach(([tab, { data }]) => {
        values.value[tab] = data;
      });
    }
  });

  const tabs = computed(() => {
    if (settings.value === undefined) return [];
    return Object.keys(settings.value);
  });
  const tab = computed(() => (tab?: string) => {
    if (tab === undefined) return tabs.value.at(0);
    return settings.value[tab];
  });

  const asMenuItems = computed(() =>
    tabs.value.map((setting) => ({
      label: () =>
        h(
          RouterLink,
          { to: { name: "settings", params: { page: setting } } },
          () => settings.value[setting].label,
        ),
      key: setting,
    })),
  );

  async function save(values) {
    await useWpFetch("/settings").post(values, "json");
    void mutate();
  }

  return {
    settings,
    tabs,
    tab,
    save,
    loading: isAwating,
    asMenuItems,
    values,
  };
});
