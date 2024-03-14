<template>
    <div class="component-section-row">
        <div class="col-title" v-if="title">
            <h3>{{title}}</h3>
        </div>
        <div class="col-content">
            <slot></slot>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: "SectionRow",
    props: {
        title: String,

    }
})
</script>

<style lang="scss" scoped>
    .component-section-row {
        display: flex;
        flex-wrap: nowrap;
        border-top: 1px solid #d9d9d9;
    }

    .col-title {
        flex-basis: 200px;
        border-right: 1px solid #d9d9d9;
        padding: 1rem;

        h3 {
            font-size: 1.1em;
            font-weight: 600;
            margin: 0;
            padding:0;
        }
    }

    .col-content {
        flex-basis: 50%;
        padding: 1rem;
    }
</style>