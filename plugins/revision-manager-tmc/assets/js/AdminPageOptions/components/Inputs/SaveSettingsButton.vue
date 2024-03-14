<template>
    <span class="component-save-settings-button">
        <button
            type="submit"
            @click.prevent="onSubmit"
            :class="[
                'components-button button-primary',
                {'is-loading' : isLoading}
            ]"
        >
            Update settings
        </button>
        <span v-if="isLoading" class="spinner is-active"></span>
    </span>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {saveSettings} from "../../settings";

export default defineComponent({
    name: "SaveSettingsButton",
    emits: ['beginSubmit', 'endSubmit'],
    data(){
        return {
            isLoading: false
        }
    },
    methods: {
        onSubmit(){

            if(this.isLoading) return;  //  Bail early.

            this.isLoading = true;
            this.$emit('beginSubmit');

            saveSettings().then(() => {

            }).catch((reason) => {
                alert(`Could not save options. Reason: ${reason}`);
            }).finally(() => {
                this.isLoading = false;
                this.$emit('endSubmit');
            });

        }
    }
})
</script>

<style lang="scss" scoped>
    .component-save-settings-button {
        display: inline-block;
    }

    button {

        &.is-loading {
            opacity: 0.3;
            pointer-events: none;
        }

    }
</style>