<template>
    <div
        :class="[
            'component-email-template-chooser',
            {'is-locked' : isLocked}
        ]"
        @click.prevent="onClick"
    >
        <img class="background" :src="imgUrl" alt="">
        <img v-if="isLocked" class="lock-icon" :src="lockImgUrl" alt="lick-icon">

    </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {fieldsData} from "../../fieldsData";
import {getProUrl} from "../../settings";

export default defineComponent({
    name: "EmailTemplateChooser",
    props: {
        isLocked: {
            type: Boolean as PropType<Boolean>,
            default: false
        },
        imgUrl: {
            type: String as PropType<String>
        },
        html: {
            type: String as PropType<String>,
            required: true
        },
        modelValue: String
    },
    computed: {
        lockImgUrl(){
            return fieldsData.pluginUrl + '/assets/img/padlock.png';
        }
    },
    methods: {
        onClick(){

            if(this.isLocked){
                window.open(getProUrl(), '_blank').focus();
            } else {
                this.$emit('update:modelValue', this.html);
            }

        }
    }
})
</script>

<style lang="scss" scoped>

    .component-email-template-chooser {
        display: block;
        width: 100px;
        height: 72px;
        position: relative;
        cursor: pointer;

        &:hover {
            opacity: 0.7;
        }

        &.is-locked {

            &:hover {
                .lock-icon {

                    opacity: 1;

                }
            }

        }

        //  Space between.
        & + & {
            margin-left: 25px;
        }

    }

    .background {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .lock-icon {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
    }

</style>