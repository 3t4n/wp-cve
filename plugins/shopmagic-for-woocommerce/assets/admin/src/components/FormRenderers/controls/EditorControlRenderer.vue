<template>
  <FieldWrapper v-bind="controlWrapper">
    <Editor
      :init="{
        plugins: [
          'table',
          'nonbreaking',
          'image',
          'directionality',
          'emoticons',
          'link',
          'autolink',
          'lists',
          'advlist',
          'code',
        ],
        menu: {
          edit: {
            title: 'Edit',
            items: 'undo redo | cut copy paste pastetext | selectall | searchreplace',
          },
          view: {
            title: 'View',
            items:
              'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments',
          },
          insert: {
            title: 'Insert',
            items:
              'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime',
          },
          format: {
            title: 'Format',
            items:
              'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat',
          },
          tools: {
            title: 'Tools',
            items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount',
          },
          table: {
            title: 'Table',
            items: 'inserttable | cell row column | advtablesort | tableprops deletetable',
          },
        },
        menubar: 'edit insert view format table tools',
        toolbar: [
          { name: 'history', items: ['undo', 'redo'] },
          { name: 'styles', items: ['styles'] },
          { name: 'formatting', items: ['bold', 'italic'] },
          {
            name: 'alignment',
            items: ['alignleft', 'aligncenter', 'alignright', 'alignjustify'],
          },
          { name: 'indentation', items: ['outdent', 'indent'] },
          { name: 'directionality', items: ['ltr', 'rtl'] },
        ],
        convert_urls: false,
        relative_urls: false,
        width: '100%',
        entity_encoding: 'raw',
        file_picker_callback: filePickerCb,
        promotion: false,
      }"
      :model-value="control.data"
      class="w-full"
      tinymce-script-src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.3.2/tinymce.min.js"
      @update:modelValue="onChange"
    />
  </FieldWrapper>
</template>

<script lang="ts">
import Editor from "@tinymce/tinymce-vue";
import type { ControlElement } from "@jsonforms/core";
import { defineComponent } from "vue";
import { rendererProps, type RendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";

export default defineComponent({
  name: "EditorControlRenderer",
  components: {
    FieldWrapper,
    Editor,
  },
  methods: {
    filePickerCb: function (cb) {
      const wp = window.wp;
      if (typeof wp === "undefined") return;
      const media = wp.media({
        title: wp.i18n.__("Pick attachments", "shopmagic-for-woocommerce"),
        button: {
          text: wp.i18n.__("Add attachment", "shopmagic-for-woocommerce"),
        },
        library: {
          type: ["image"],
        },
        multiple: false,
      });
      media.on("select", () => {
        const { /* sizes ,*/ alt, url } = media.state().get("selection").first().toJSON();
        cb(url, { alt });
      });

      media.open();
    },
  },
  props: {
    ...rendererProps<ControlElement>(),
  },
  setup(props: RendererProps<ControlElement>) {
    return useVanillaControl(useJsonFormsControl(props), (target) => target);
  },
});
</script>
