import Fuse from "fuse.js";
import { h, isRef, ref, unref, watch } from "vue";
import { elementsAsOptions } from "@/_utils";
import { NText, type SelectRenderLabel, type SelectRenderTag } from "naive-ui";

export const useFuzzySearch = (options: readonly string[]) => {
  const optionsRef = ref(elementsAsOptions(unref(options)));

  const fuse = new Fuse(options, {
    keys: ["label", { name: "description", weight: 0.8 }],
    ignoreLocation: true,
  });

  if (isRef(options)) {
    watch(options, (o) => {
      fuse.setCollection(o);
      optionsRef.value = elementsAsOptions(o);
    });
  }

  // @todo debounce minimally
  function search(pattern: string) {
    if (pattern === "") {
      reset();
    } else {
      const search = fuse.search(pattern);
      const elements = search.map((match) => ({ ...match.item }));
      optionsRef.value = elementsAsOptions(elements);
    }
  }

  function reset() {
    optionsRef.value = elementsAsOptions(options);
  }

  const renderLabel: SelectRenderLabel = (option) => {
    if (option.type === "group") return option.label;
    return h("div", { className: "flex flex-col my-2 gap-1" }, [
      h("div", option?.label),
      h(NText, { depth: 3, tag: "div" }, { default: () => option?.description }),
    ]);
  };

  const renderTag: SelectRenderTag = ({ option }) => {
    return h("div", option.label);
  };

  return {
    search,
    reset,
    matches: optionsRef,
    renderLabel,
    renderTag,
  };
};
