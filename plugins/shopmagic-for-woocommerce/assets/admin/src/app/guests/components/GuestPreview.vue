<script lang="ts" setup>
import { NButton, NModal } from "naive-ui";
import SimpleTime from "@/components/SimpleTime.vue";
import { ref } from "vue";

type Guest = {
  email: string;
  created: string;
  lastActive: string;
  meta: Record<string, string>;
};

defineProps<{ guest: Guest }>();

const showModal = ref(false);
</script>
<template>
  <NButton @click="showModal = true">
    {{ __("View details", "shopmagic-for-woocommerce") }}
  </NButton>
  <NModal v-model:show="showModal" aria-modal="true" class="w-[600px]" preset="card" title="Guest">
    <NTable>
      <tr>
        <th>{{ __("Email", "shopmagic-for-woocommerce") }}</th>
        <td>{{ guest.email }}</td>
      </tr>
      <tr v-if="guest.meta.first_name">
        <th>{{ __("First name", "shopmagic-for-woocommerce") }}</th>
        <td>{{ guest.meta.first_name }}</td>
      </tr>
      <tr v-if="guest.meta.last_name">
        <th>{{ __("Last name", "shopmagic-for-woocommerce") }}</th>
        <td>{{ guest.meta.last_name }}</td>
      </tr>
      <tr v-if="guest.meta.billing_phone">
        <th>{{ __("Phone", "shopmagic-for-woocommerce") }}</th>
        <td>{{ guest.meta.billing_phone }}</td>
      </tr>
      <tr v-if="guest.meta.billing_address_1">
        <th>{{ __("Address", "shopmagic-for-woocommerce") }}</th>
        <td>
          <p>
            {{ guest.meta.billing_address_1 }}
            {{ guest.meta.billing_address_2 }}
          </p>
          <p v-if="guest.meta.billing_city">
            {{ guest.meta.billing_postcode }} {{ guest.meta.billing_city }}
          </p>
        </td>
      </tr>
      <tr>
        <th>{{ __("Created", "shopmagic-for-woocommerce") }}</th>
        <td><SimpleTime :time="guest.created" /></td>
      </tr>
      <tr>
        <th>{{ __("Last active", "shopmagic-for-woocommerce") }}</th>
        <td><SimpleTime :time="guest.lastActive" /></td>
      </tr>
    </NTable>
  </NModal>
</template>
