import { computed, ref, watchEffect } from "vue";

const STATES = {
  VALIDATING: "VALIDATING",
  PENDING: "PENDING",
  SUCCESS: "SUCCESS",
  ERROR: "ERROR",
  STALE_IF_ERROR: "STALE_IF_ERROR",
} as const;

export default function (data, error, isValidating) {
  const state = ref("idle");
  const isLoading = computed<boolean>(() => state.value === STATES.PENDING);
  const isAwating = computed<boolean>(() => STATES.PENDING === state.value);
  watchEffect(() => {
    if (data.value && isValidating.value) {
      state.value = STATES.VALIDATING;
      return;
    }
    if (data.value && error.value) {
      state.value = STATES.STALE_IF_ERROR;
      return;
    }
    if (data.value === undefined && !error.value) {
      state.value = STATES.PENDING;
      return;
    }
    if (data.value && !error.value) {
      state.value = STATES.SUCCESS;
      return;
    }
    if (data.value === undefined && error) {
      state.value = STATES.ERROR;
      return;
    }
  });

  return {
    state,
    STATES,
    isLoading,
    isAwating,
  };
}
