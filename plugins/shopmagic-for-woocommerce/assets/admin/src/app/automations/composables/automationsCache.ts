import { useAutomationCollectionStore } from "@/app/automations/store";
import { storeToRefs } from "pinia";
import type { Automation } from "@/types/automation";

export function useAutomationCache(id: number): Automation | null {
  const { automations } = storeToRefs(useAutomationCollectionStore());

  if (automations.value !== undefined) {
    const cachedAutomation = automations.value.find((a) => a.id === id);

    if (cachedAutomation !== undefined) {
      return cachedAutomation;
    }
  }

  return null;
}
