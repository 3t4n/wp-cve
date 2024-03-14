<template>
    <div class="">

        <section-body title="API Key">
            <section-row>

                <code-manager v-model="key" :is-key-correct="isKeyCorrect"/>

            </section-row>
        </section-body>

    </div>
</template>

<script>
import { computed, defineComponent, reactive, ref, toRef, toRefs } from "vue";
import SectionBody from "../Sections/SectionBody";
import SectionRow from "../Sections/sectionRow";
import { settings } from "../../settings";
import CodeManager from "../CodeManager/CodeManager";

export default defineComponent({
    name: "TabPro",
    components: { CodeManager, SectionRow, SectionBody },
    setup(){

        const key               = toRef(settings.license, 'key');
        const isKeyCorrect      = toRef(settings.license, 'isKeyCorrect');

        return {
            key,
            isKeyCorrect
        }

    }
})
</script>

<style scoped>

</style>