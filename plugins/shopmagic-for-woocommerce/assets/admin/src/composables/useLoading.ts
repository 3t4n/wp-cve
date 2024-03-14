import useSwrvState from "@/composables/useSwrvState";
import { ref, watch } from "vue";

type UseLoadingParameters = {
  data: unknown;
  error: unknown;
  isValidating: unknown;
  initial: boolean;
};

export function useLoading({ data, error, isValidating, initial = true }: UseLoadingParameters) {
  const loading = ref(initial);
  const { state, STATES } = useSwrvState(data, error, isValidating);

  watch(state, () => {
    loading.value = state.value === STATES.PENDING;
  });

  return loading;
}
