<template>
    <a
        :class="[
            'nav-tab',
            {'nav-tab-active' : isActive}
        ]"
        @click.prevent="$emit('update:modelValue', this.tabSlug)"
        href="#"
    >
        <slot></slot>
    </a>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "NavigationTabsEntry",
    emits: ['update:modelValue'],
    props: {
        modelValue: String,
        tabSlug: {
            type: String,
            required: true
        }
    },
    computed: {
        isActive(): boolean {
            return this.modelValue === this.tabSlug;
        }
    }
})
</script>

<style scoped>
    .nav-tab:focus {
        box-shadow: none;
    }
</style>