<script lang="ts" setup>
import type { ControlElement } from "@jsonforms/core";
import { NButton, useMessage } from "naive-ui";
import { rendererProps, useJsonFormsControl } from "@jsonforms/vue";
import { useVanillaControl } from "../util";
import FieldWrapper from "./FieldWrapper.vue";
import { fetchOptions } from "@/composables/useWpFetch";
import { useFetch } from "@vueuse/core";
import { __ } from "@/plugins/i18n";
import * as log from "@/_utils/log";

const props = defineProps(rendererProps<ControlElement>());

const { control, controlWrapper } = useVanillaControl(useJsonFormsControl(props), (t) => t || undefined);

const message = useMessage()

function handleAction() {
  useFetch(control.value.schema.presentation.callback, {
    beforeFetch: ({ options }) => {
      options.headers = {
        ...options.headers,
        ...fetchOptions.headers,
      };

      return {
        options,
      };
    },
  })
    .post()
    .json()
    .then(({data, response}) => {
      if ( response.value.status === 204 ) {
        message.success(__('Action performed successfully', 'shopmagic-for-woocommerce'), { duration: 3000 });
      } else if ( response.value.status >= 200 && response.value.status < 300 ) {
        message.success(data.value.message, { duration: 3000 });
      } else {
        message.error(data.value.message, { duration: 3000 });
        log.error("Error performing action", data.value);
      }
    })
    .catch((e) => {
      console.log(e);
    });
}
</script>
<template>
  <FieldWrapper :show-label="false" v-bind="controlWrapper">
    <NButton
      :id="control.id + '-action'"
      :disabled="!control.enabled"
      :readonly="control.schema.readOnly"
      secondary
      type="info"
      @click="handleAction"
      >{{ control.label }}</NButton
    >
  </FieldWrapper>
</template>
