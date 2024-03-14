<template>
    <div>

        <NavigationTabs v-model="currentActiveTabSlug"/>

        <transition name="fade" mode="out-in" appear>
            <component :is="currentTab"></component>
        </transition>

    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import NavigationTabs from "./components/NavigationTabs/NavigationTabs.vue";
import TabTools from "./components/Tabs/TabTools.vue";
import TabGeneral from "./components/Tabs/TabGeneral.vue";
import TabPro from "./components/Tabs/TabPro.vue";

export default defineComponent({
    name: "PageOptionsBody",
    components: { NavigationTabs, TabGeneral, TabTools, TabPro },
    data(){
        return {
            currentActiveTabSlug: 'general'
        }
    },
    computed: {
        currentTab(){
            const lib = {
                general:    'TabGeneral',
                tools:      'TabTools',
                pro:        'TabPro'
            }

            return lib?.[this.currentActiveTabSlug] || lib.general;
        }
    }
})
</script>

<style lang="scss" scoped>

    ::v-deep(.field-disabled) {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    ::v-deep(p) {
        margin: 0 0 1em;
        padding: 0;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.5s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        margin-top: 20px;
    }

</style>