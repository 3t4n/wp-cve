<template>
    <a target="_blank" :href="proLink">
       <slot></slot>
    </a>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {getProUrl} from "../../settings";

export default defineComponent({
    name: "ProLink",
    computed: {
        proLink(){
            return getProUrl();
        }
    }
})
</script>

<style lang="scss" scoped>
    a {
        clear: both;
        display: inline-block;
        color: #3498DB;
        text-decoration: none;
        margin: 10px 0;
        padding: 5px 10px;
        border: 1px dashed #cad7df;
        border-radius: 3px;
        transition: 500ms all;

        &:hover {
            color: #db3442;
            text-decoration: none;
            background: #cad7df;
            border: 1px solid #cad7df;
        }
    }
</style>