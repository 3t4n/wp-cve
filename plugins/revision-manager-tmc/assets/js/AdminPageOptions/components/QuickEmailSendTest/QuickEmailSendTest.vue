<template>
    <div class="component-quick-email-send-test">

        <p>
            Insert your e-mail address and send preview of the notification.
        </p>

        <div class="notice notice-info is-dismissible" v-if="notice">
            <div class="notice-text" v-html="notice"></div>
            <button type="button" class="notice-dismiss" @click.prevent="onDismissNotice"></button>
        </div>

        <div>
            <input type="email" v-model="valEmail" class="regular-text">
            <button @click.prevent="onSubmit" class="button button-secondary">
                Send test
                <span
                    v-if="isLoading"
                    class="spinner is-active"
                ></span>
            </button>
        </div>

    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {fieldsData} from "../../fieldsData";
import axios from "axios";

export default defineComponent({
    name: "QuickEmailSendTest",
    data(){
        return {
            valEmail: "",
            isLoading: false,
            notice: ""
        }
    },
    mounted(){

        this.valEmail = this.defaultEmailTarget;

    },
    props: {
        defaultEmailTarget: String,
        emailContent: String,
        emailSubject: String
    },
    methods: {
        onSubmit(){

            this.isLoading = true;

            const data = new URLSearchParams({
                'action': 	        fieldsData.quickEmailTestActionName,
                'emailTarget': 	    this.valEmail,
                'emailSubject':     this.emailSubject,
                'emailContent':     this.emailContent
            });

            axios.post( fieldsData.ajaxUrl, data, {}).then(result => {
                this.notice = "Email has been sent! 😍";
            }).catch(reason => {
                this.notice = `Could not send email. <pre>${reason}</pre>`;
            }).finally(() => {
                this.isLoading = false;
            });

        },
        onDismissNotice(){
            this.notice = "";
        }
    }
})
</script>

<style lang="scss" scoped>
    .component-quick-email-send-test {
        display: block;
    }

    .spinner {
        float: unset;
        margin: 0;
        vertical-align: text-top;
    }

    .notice-text {
        padding: 9px 0;
    }
</style>