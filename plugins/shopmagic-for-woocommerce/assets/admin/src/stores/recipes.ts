import { defineStore } from "pinia";
import type { Automation } from "@/types/automation";
import useSWRV from "@/_utils/swrv";
import { useSingleAutomation } from "@/app/automations/singleAutomation";
import useSwrvState from "@/composables/useSwrvState";

type LegacyRecipe = {
  name: string;
  description: string;
  event: {
    slug: string;
    data: Record<string, string>;
  };
  filters?: {
    [k: number]: {
      [k: number]: {
        data: Record<string, string>;
        filter_slug: string;
      };
    };
  };
  actions: Record<string, string>[];
};
export type Recipe = (Automation & { description: string }) | LegacyRecipe;

export const useRecipesStore = defineStore("recipes", () => {
  const { data: recipes, error, isValidating } = useSWRV<Recipe[]>("/automations/recipes");

  const { isAwating } = useSwrvState(recipes, error, isValidating);

  async function createAutomation(name: string) {
    const { addAutomation, save } = useSingleAutomation();

    const recipe = recipes.value?.find((r) => r.name === name);
    if (!recipe) return;
    addAutomation(recipe);
    return save();
  }

  return {
    recipes,
    loading: isAwating,
    createAutomation,
  };
});
