<template>
    <div>
        <textarea
            ref="textarea"
            :id="inputId"
            v-model="value"
        ></textarea>
    </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

declare var tinymce: any;

export default defineComponent({
    name: "TinyMce",
    props: {
        id: {
            type: String as PropType<String>
        },
        modelValue: String
    },
    data(){
        return {
            inputId: '',
            tinyMceInstance: null
        }
    },
    mounted(){

        this.inputId = this.id || 'rmtmc_tinymce_' + new Date().getTime();

        this.$nextTick(() => {

            tinymce.init({
                selector: '#' + this.inputId,
                statusbar: false,
                height: 400,
                menubar: '',
                toolbar: 'undo redo |  | bold italic strikethrough | forecolor backcolor | link | alignleft aligncenter alignright alignjustify',
                plugins: "textcolor link",
                setup: (editor) => {

                    this.tinyMceInstance = editor;

                    editor.on('change input keyup', (e) => {
                        this.value = editor.getContent();
                    });

                }
            });

        });

    },
    unmounted() {

        this.tinyMceInstance.remove();
        this.tinyMceInstance.destroy();
        this.tinyMceInstance = null;

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
    },
    watch: {
        modelValue(newVal){
            if(newVal !== this.tinyMceInstance.getContent()){
                this.tinyMceInstance.setContent(newVal);
            }
        }
    }
})
</script>

<style scoped>

</style>