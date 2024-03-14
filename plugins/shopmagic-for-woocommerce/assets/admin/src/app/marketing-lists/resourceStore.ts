import { defineStore } from "pinia";
import { ref, watch } from "vue";
import useSWRV, { cache } from "@/_utils/swrv";
import { get } from "@/_utils";

export const useMarketingResources = defineStore("marketingResources", () => {
  const { data: fields } = useSWRV("/resources/marketing-list", get, {
    cache,
    revalidateOnFocus: false,
  });
  const { data: shortcode } = useSWRV("/resources/shortcode", get, {
    cache,
    revalidateOnFocus: false,
  });
  const loading = ref(true);
  const shortcodeLoading = ref(true);

  watch(fields, (data) => {
    if (data !== undefined) {
      loading.value = false;
    }
  });
  watch(shortcode, (data) => {
    if (data !== undefined) {
      shortcodeLoading.value = false;
    }
  });

  return {
    fields,
    shortcode,
    loading,
    shortcodeLoading,
  };
});
