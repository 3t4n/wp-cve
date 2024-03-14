import { ref } from "vue";
import { useSingleAutomation } from "@/app/automations/singleAutomation";

export function useAutomationEvent() {
  const previousEvent = ref<string | null>(null);
  return {
    onChange: (cb: (event: string, previous: string | null) => void) => {
      useSingleAutomation().$subscribe((mutation, state) => {
        if (state.automation === null) return;

        if (previousEvent.value !== state.automation?.event.name) {
          cb(state.automation.event.name, previousEvent.value);
          previousEvent.value = state.automation?.event.name;
        }
      });
    },
  };
}
