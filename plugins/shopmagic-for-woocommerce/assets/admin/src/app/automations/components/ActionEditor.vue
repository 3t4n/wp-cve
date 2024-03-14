<script lang="ts" setup>
import { NButton, NIcon, NSpace } from "naive-ui";
import { AddCircleOutline } from "@vicons/ionicons5";
import EditableCard from "./EditableCard.vue";
import SingleAction from "./Action/SingleAction.vue";
import { useAutomationResourcesStore } from "../resourceStore";
import { storeToRefs } from "pinia";
import { useSingleAutomation } from "@/app/automations/singleAutomation";

const store = useSingleAutomation();
const { automation } = storeToRefs(store);
const { addAction, removeAction } = store;

const { actions: availableActions } = storeToRefs(useAutomationResourcesStore());
</script>
<template>
  <EditableCard :title="__('Actions', 'shopmagic-for-woocommerce')">
    <NSpace vertical>
      <ul>
        <li v-for="id in automation?.actions.keys()" :key="id">
          <SingleAction :id="id" :actions="availableActions" @remove="removeAction" />
        </li>
      </ul>
    </NSpace>
    <template #below>
      <NButton type="primary" @click="addAction()">
        <template #icon>
          <NIcon>
            <AddCircleOutline />
          </NIcon>
        </template>
        {{ __("Add action", "shopmagic-for-woocommerce") }}
      </NButton>
    </template>
  </EditableCard>
</template>
