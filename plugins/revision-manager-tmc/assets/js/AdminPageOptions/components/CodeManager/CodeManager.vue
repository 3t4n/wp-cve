<template>
    <div class="components-code-manager">

        <div class="description">
            Software has to be activated for premium support and more advanced functionality, which relies on our servers.<br/>
            <br/>
            Activation works through REST API located at <b>jetplugs.com</b>, so<br/>
            be sure to whitelist this domain, if your hosting works under firewall.
        </div>

        <div>
            <input type="password" class="regular-text" placeholder="Your license goes here..." v-model="license" :disabled="isCodeFieldLocked">

            <button v-if="!isCodeFieldLocked" class="button" @click.prevent="onActivate">
                Activate
                <span v-if="isLoading" class="spinner is-active"></span>
            </button>
            <button v-if="isCodeFieldLocked" class="button" @click.prevent="onDeactivate">
                <span v-if="isLoading" class="spinner is-active"></span>
                Deactivate
            </button>
        </div>
        <div>
            <div v-if="isKeyCorrect" class="key-info is-success">Software is fully activated</div>
            <div v-else class="key-info is-error">Software is not activated</div>
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import axios from "axios";
import {fieldsData} from "../../fieldsData";
import {loadSettings} from "../../settings";

export default defineComponent({
    name: "CodeManager",
    data(){
        return {
            isCodeFieldLocked: false,
            isLoading: false
        }
    },
    props: {
        modelValue: String,
        isKeyCorrect: {
            type: Boolean as PropType<boolean>
        }
    },
    mounted(){

        if(this.modelValue && this.isKeyCorrect){
            this.isCodeFieldLocked = true;
        }

    },
    methods: {
        onDeactivate(){

            this.isCodeFieldLocked = true;
            this.isLoading = true;

            this.deactivateCode()
                .then(() => loadSettings())
                .then(() => {
                    this.isCodeFieldLocked = false;
                })
                .catch(reason => {
                    alert("Could not deactivate your license. Please try again.");
                })
                .finally(() => {
                    this.isLoading = false;
                });

        },
        onActivate(){

            this.isCodeFieldLocked = true;

            this.activateCode()
                .then(() => loadSettings())
                .then(() => {

                })
                .catch(reason => {
                    this.isCodeFieldLocked = false;
                    alert("Could not activate your license. Please try again.");
                })
                .finally(() => {
                    this.isLoading = false;
                });

        },
        async activateCode(){
            return axios.post( fieldsData.restApiActivateCode + '/' + this.license, {}, {
                headers: {
                    'X-WP-Nonce': fieldsData.wpnonce
                }
            } );
        },
        async deactivateCode(){
            return axios.post( fieldsData.restApiDeactivateCode + '/' + this.license, {}, {
                headers: {
                    'X-WP-Nonce': fieldsData.wpnonce
                }
            } );
        }
    },
    computed: {
        license : {
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

<style lang="scss" scoped>

    .key-info {

        padding: 0.8em;
        margin: 10px 0;
        max-width: 400px;
        width: 100%;
        border-radius: 4px;
        font-weight: bold;

        &.is-success {
            background: #2ECC71;
            border: 2px dashed darkgreen;
            color: white;
        }

        &.is-error {
            background: #da7979;
            border: 2px dashed #770b1e;
            color: white;
        }

    }

    .description {
        margin-bottom: 2em;
    }

</style>