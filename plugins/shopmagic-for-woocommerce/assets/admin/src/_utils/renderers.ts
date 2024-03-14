import { controlRenderers } from "@/components/FormRenderers/controls";
import { layoutRenderers } from "@/components/FormRenderers/layouts";
import { arrayRenderers } from "@/components/FormRenderers/array";

export const naiveUiRenderers = [...arrayRenderers, ...controlRenderers, ...layoutRenderers];
