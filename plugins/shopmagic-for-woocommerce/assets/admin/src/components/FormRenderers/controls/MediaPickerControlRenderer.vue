<template>
  <FieldWrapper v-bind="controlWrapper">
    <div class="flex flex-col flex-wrap">
      <NButton class="w-max" tertiary @click="mediaFrame.open()">
        {{ __("Add PDF attachment", "shopmagic-for-woocommerce") }}
      </NButton>
      <NSpace vertical>
        <p v-for="(file, i) in selectedMedia" :key="i">
          <NA :href="file" target="_blank">
            {{ file }}
          </NA>
          <NButton text type="error" @click="removeAttachment(i)">
            <template #icon>
              <CloseOutline />
            </template>
          </NButton>
        </p>
      </NSpace>
    </div>
  </FieldWrapper>
</template>

<script lang="ts">
import { NA, NButton, NSpace } from "naive-ui";
import { CloseOutline } from "@vicons/ionicons5";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsOneOfEnumControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";

export default defineComponent({
  name: "MediaPickerControlRenderer",
  components: {
    FieldWrapper,
    NA,
    NButton,
    NSpace,
    CloseOutline,
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  data() {
    return {
      mediaFrame: wp.media({
        title: this.__("Pick attachments", "shopmagic-for-woocommerce"),
        button: {
          text: this.__("Add attachment", "shopmagic-for-woocommerce"),
        },
        multiple: this.schema.uniqueItems,
      }),
    };
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsOneOfEnumControl(props), (target) => target);
  },
  computed: {
    selectedMedia(): string[] {
      if (Array.isArray(this.control.data)) {
        return this.control.data.filter(Boolean) || [];
      }
      return [];
    },
  },
  methods: {
    removeAttachment(index: number) {
      this.onChange(this.selectedMedia.filter((_, i) => i !== index));
    },
  },
  created() {
    this.mediaFrame.on("select", () => {
      const { url /* name */ } = this.mediaFrame.state().get("selection").first().toJSON();
      this.onChange([...this.selectedMedia, url]);
    });
  },
});
</script>
