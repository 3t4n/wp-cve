<template>
    <div class="">

        <section-body title="Post types">
            <section-row title=" ">

                <p>Plugin will only work on chosen post types.</p>

                <table class="postTypesListTable">
                    <tr :class="[{'field-disabled' : postType.disabled}]" v-for="postType of postTypesList" :key="postType.key">
                        <td class="column-label">
                            <label>
                                <input type="checkbox" v-model="fieldValPostTypes" :value="postType.key" :disabled="postType.disabled">
                                <span>{{postType.label}}</span>
                            </label>
                        </td>
                        <td>
                            <i>( {{postType.key}} )</i>
                        </td>
                    </tr>
                </table>

                <pro-link v-if="!isCodeActive">
                    You can choose every custom post type in PRO version.
                </pro-link>

            </section-row>
        </section-body>

        <section-body title="Capabilities">
            <section-row title="Copy creation capability">
                <select v-model="fieldValCopyCreationCapability" class="regular-text">
                    <option v-for="capability in capabilitiesList" :value="capability">
                        {{capability}}
                    </option>
                </select>
            </section-row>
            <section-row title="Acceptation capability">
                <select v-model="fieldValAcceptationCapability" class="regular-text">
                    <option v-for="capability in capabilitiesList" :value="capability">
                        {{capability}}
                    </option>
                </select>
            </section-row>
            <section-row title="Role for notification">
                <select v-model="fieldValRoleNotification" class="regular-text">
                    <option v-for="(name, role) in rolesList" :value="role">
                        {{name}}
                    </option>
                </select>
            </section-row>
            <section-row title="Excluded e-mails">
                <p>These addresses will never receive any notification.</p>
                <input-label :disabled="!isCodeActive">
                    <textarea v-model="fieldValNotificationsExcludedEmails" class="regular-text" :disabled="!isCodeActive"></textarea>
                </input-label>
                <p style="color: silver;">Comma separated emails. Example: mail@host1.com, mail@host2.com (...)</p>
                <pro-link v-if="!isCodeActive">
                    PRO version gives you ability to exclude certain e-mail addresses from notifications.
                </pro-link>
            </section-row>
        </section-body>

        <section-body title="Merging">
            <section-row title="Merge date with revision">
                <input-label>
                    <input type="radio" v-model="fieldValMergeDate" value="0">
                    <span>No, keep original post's date</span>
                </input-label>
                <input-label>
                    <input type="radio" v-model="fieldValMergeDate" value="1">
                    <span>Yes, after revision acceptance, replace date</span>
                </input-label>
            </section-row>
        </section-body>

        <section-body title="Differences for WordPress (classic editor)">
            <section-row title="Post title">
                <input-label>
                    <input type="radio" v-model="fieldValWpDifferencesDisplayPostTitle" value="0">
                    <span>Display changes in post title</span>
                </input-label>
                <input-label>
                    <input type="radio" v-model="fieldValWpDifferencesDisplayPostTitle" value="1">
                    <span>Do not show</span>
                </input-label>
            </section-row>
            <section-row title="Post content">
                <input-label>
                    <input type="radio" v-model="fieldValWpDifferencesDisplayPostContent" value="0">
                    <span>Display changes in post content</span>
                </input-label>
                <input-label>
                    <input type="radio" v-model="fieldValWpDifferencesDisplayPostContent" value="1">
                    <span>Do not show</span>
                </input-label>
            </section-row>
        </section-body>

        <section-body title="Differences for Advanced Custom Fields">
            <section-row title="Differences">
                <p>
                    If there is a difference between original post field and clone, there will be a mark on the side of field.
                </p>
                <input-label>
                    <input type="radio" v-model="fieldValAcfDifferencesMarkChanges" value="1">
                    <span>Mark changed fields</span>
                </input-label>
                <input-label>
                    <input type="radio" v-model="fieldValAcfDifferencesMarkChanges" value="0">
                    <span>Do not show</span>
                </input-label>
            </section-row>
            <section-row title="Color of change mark">
                <color-picker
                    v-model:pure-color="fieldValAcfDifferencesChangeMarkColor"
                    :disable-alpha="true"
                    :disable-history="true"
                ></color-picker>
            </section-row>
            <section-row title="Color of new mark">
                <color-picker
                    v-model:pure-color="fieldValAcfDifferencesNewMarkColor"
                    :disable-alpha="true"
                    :disable-history="true"
                ></color-picker>
            </section-row>
        </section-body>

        <section-body title="Notifications">
            <section-row title="Type of notifications">
                <p>
                    I want to receive notification about:
                </p>
                <input-label :disabled="!isCodeActive">
                    <input type="radio" v-model="fieldValNotificationsType" value="everySingle" :disabled="!isCodeActive">
                    <span>Every single revision</span>
                </input-label>
                <input-label :disabled="!isCodeActive">
                    <input type="radio" v-model="fieldValNotificationsType" value="collective" :disabled="!isCodeActive">
                    <span>All revisions in one e-mail (one per day)</span>
                </input-label>
                <pro-link v-if="!isCodeActive">
                    You can choose alternative notifications type in PRO version.
                </pro-link>

            </section-row>
            <section-row title="Who receives notifications?">
                <input-label :disabled="!isCodeActive">
                    <input type="radio" v-model="fieldValNotificationsWhoReceives" value="all" :disabled="!isCodeActive">
                    <span>All authorized users</span>
                </input-label>
                <input-label :disabled="!isCodeActive">
                    <input type="radio" v-model="fieldValNotificationsWhoReceives" value="authors" :disabled="!isCodeActive">
                    <span>Only original authors</span>
                </input-label>
                <pro-link v-if="!isCodeActive">
                    You can limit notifications to original authors in PRO version.
                </pro-link>
            </section-row>
            <section-row title="Title">
                <input type="text" v-model="fieldValNotificationsTitle" class="regular-text">
            </section-row>
            <section-row title="Content">

                <div class="choose-email-template-grid">
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/default_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeDefault"
                    />
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/mono_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeMono"
                    />
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/aqua_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeAqua"
                    />
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/blue_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeBlue"
                    />
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/light_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeLight"
                    />
                    <email-template-chooser
                        :is-locked="!isCodeActive"
                        :img-url="pluginUrl + '/assets/emailTemplates/moonlight_mail.jpg'"
                        v-model="fieldValNotificationsContent"
                        :html="emailThemeMoonlight"
                    />
                </div>

                <tiny-mce
                    v-if="isCodeActive"
                    v-model="fieldValNotificationsContent"
                    :disabled="!isCodeActive"
                    toolbar="bold italic forecolor | fontsizeselect | link image | aligncenter alignjustify alignleft alignnone alignright"
                    :init="{
                        height: 500,
                        menubar: false
                    }"
                />
                <div v-else v-html="fieldValNotificationsContent">

                </div>

                <pro-link v-if="!isCodeActive">
                    PRO version gives you ability to edit this content.
                </pro-link>

            </section-row>
            <section-row title="Quick test">
                <quick-email-send-test
                    :default-email-target="currentUserEmail"
                    :email-subject="fieldValNotificationsTitle"
                    :email-content="fieldValNotificationsContent"
                />
            </section-row>
        </section-body>

        <section-body>
            <section-row title=" ">
                <save-settings-button></save-settings-button>
            </section-row>
        </section-body>

    </div>
</template>

<script lang="ts">
import {defineComponent, ref, toRef} from "vue";
import SectionBody from "../Sections/SectionBody.vue";
import SectionRow from "../Sections/sectionRow.vue";
import SaveSettingsButton from "../Inputs/SaveSettingsButton.vue";
import { fieldsData } from "../../fieldsData";
import {isCodeActive, settings} from "../../settings";
import ProLink from "../ProLink/ProLink.vue";
import {
    fieldValComputedArrayToListWithOnes,
    computedListOfAvailablePostTypes
} from "../../fieldsDataConverters/fieldsDataConverters";
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import InputLabel from "../Inputs/InputLabel.vue";
import QuickEmailSendTest from "../QuickEmailSendTest/QuickEmailSendTest.vue";
import EmailTemplateChooser from "../EmailTemplateChooser/EmailTemplateChooser.vue";
import TinyMce from "../Inputs/TinyMce.vue";

export default defineComponent({
    name: "TabGeneral",
    components: {
        TinyMce,
        EmailTemplateChooser,
        QuickEmailSendTest,
        InputLabel, ProLink, SaveSettingsButton, SectionRow, SectionBody, ColorPicker },
    setup(){

        const postTypesList                             = computedListOfAvailablePostTypes(toRef(fieldsData, 'postTypes'));
        const capabilitiesList                          = fieldsData.capabilities as string[];
        const rolesList                                 = fieldsData.roles as string[];
        const currentUserEmail                          = fieldsData.currentUserEmail as string;
        const pluginUrl                                 = fieldsData.pluginUrl as string;

        const fieldValPostTypes                         = fieldValComputedArrayToListWithOnes(toRef(settings.postTypes, 'chosen'));
        const fieldValCopyCreationCapability            = toRef(settings.capabilities, 'capCopy');
        const fieldValAcceptationCapability             = toRef(settings.capabilities, 'capAccept');
        const fieldValRoleNotification                  = toRef(settings.capabilities, 'roleNotification');
        const fieldValNotificationsExcludedEmails       = toRef(settings.capabilities, 'excludedEmails');
        const fieldValMergeDate                         = toRef(settings.merging, 'mergeDate');
        const fieldValWpDifferencesDisplayPostTitle     = toRef(settings.wpDifferences, 'displayPostTitle');
        const fieldValWpDifferencesDisplayPostContent   = toRef(settings.wpDifferences, 'displayPostContent');
        const fieldValAcfDifferencesMarkChanges         = toRef(settings.acfDifferences, 'markChanges');
        const fieldValAcfDifferencesChangeMarkColor     = toRef(settings.acfDifferences, 'changeMarkColor');
        const fieldValAcfDifferencesNewMarkColor        = toRef(settings.acfDifferences, 'newMarkColor');
        const fieldValNotificationsType                 = toRef(settings.notifications, 'type');
        const fieldValNotificationsWhoReceives          = toRef(settings.notifications, 'whoReceives');
        const fieldValNotificationsTitle                = toRef(settings.notifications, 'title');
        const fieldValNotificationsContent              = toRef(settings.notifications, 'content');

        const emailThemeDefault                         = require('/assets/emailTemplates/default_mail.html').default;
        const emailThemeAqua                            = require('/assets/emailTemplates/aqua_mail.html').default;
        const emailThemeBlue                            = require('/assets/emailTemplates/blue_mail.html').default;
        const emailThemeLight                           = require('/assets/emailTemplates/light_mail.html').default;
        const emailThemeMono                            = require('/assets/emailTemplates/mono_mail.html').default;
        const emailThemeMoonlight                       = require('/assets/emailTemplates/moonlight_mail.html').default;

        return {
            postTypesList,
            capabilitiesList,
            rolesList,
            currentUserEmail,
            pluginUrl,
            isCodeActive: isCodeActive(),
            fieldValPostTypes,
            fieldValCopyCreationCapability,
            fieldValAcceptationCapability,
            fieldValRoleNotification,
            fieldValNotificationsExcludedEmails,
            fieldValMergeDate,
            fieldValWpDifferencesDisplayPostTitle,
            fieldValWpDifferencesDisplayPostContent,
            fieldValAcfDifferencesMarkChanges,
            fieldValAcfDifferencesChangeMarkColor,
            fieldValAcfDifferencesNewMarkColor,
            fieldValNotificationsType,
            fieldValNotificationsWhoReceives,
            fieldValNotificationsTitle,
            fieldValNotificationsContent,
            emailThemeDefault,
            emailThemeAqua,
            emailThemeBlue,
            emailThemeLight,
            emailThemeMono,
            emailThemeMoonlight
        };

    }
})
</script>

<style lang="scss" scoped>

    .postTypesListTable {

        .column-label {

            padding: 0 40px 0 0;

        }

    }

    .choose-email-template-grid {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

</style>