<template>
    <h2 class="nav-tab-wrapper">
        <NavigationTabsEntry tab-slug="general" v-model="value">
            General settings
        </NavigationTabsEntry>
        <NavigationTabsEntry tab-slug="pro" v-model="value">
            API Key
            <span v-if="hasCode && !isCodeActive" class="dashicons dashicons-lock" style="color: #da7979;"></span>
            <span v-if="!hasCode && !isCodeActive" class="dashicons dashicons-lock" style="color: #a6a6a6;"></span>
            <span v-if="isCodeActive" class="dashicons dashicons-unlock" style="color: #2ecc71;"></span>
        </NavigationTabsEntry>
    </h2>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import NavigationTabsEntry from "./NavigationTabsEntry.vue"
import {isCodeActive, hasCode} from "../../settings";

export default defineComponent({
    name: "NavigationTabs",
    components: {NavigationTabsEntry},
    emits: ['update:modelValue'],
    props: {
        modelValue: String
    },
    setup(){

        return {
            isCodeActive: isCodeActive(),
            hasCode: hasCode()
        }
    },
    computed: {
        value: {
            get(){
                return this.modelValue;
            },
            set(val){
                this.$emit('update:modelValue', val);
            }
        }
    }
})
</script>

<style scoped>

</style>