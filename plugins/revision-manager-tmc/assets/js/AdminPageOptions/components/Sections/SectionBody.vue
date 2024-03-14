<template>
    <div class="component-section-body">

        <div v-if="title" class="section-title">{{title}}</div>

        <slot></slot>

    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    name: "SectionBody",
    props: {
        title: String
    }
})
</script>

<style lang="scss" scoped>

    .component-section-body {
        margin-top: 10px;

        & + & {
            margin-top: 0;
        }

    }

    .section-title {
        border-top: 1px solid #d9d9d9;
        padding: 1.5em 1em;
        font-weight: bold;
        font-size: 1.3em;
    }

</style>