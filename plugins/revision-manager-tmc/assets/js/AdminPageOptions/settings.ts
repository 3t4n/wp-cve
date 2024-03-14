import {reactive, ref, Ref, toRef, triggerRef} from "vue";
import axios from "axios";
import {fieldsData} from "./fieldsData";
import _, {isObject} from "lodash";

declare const rm_tmc_settings: any;

export const settings = reactive(rm_tmc_settings);

export async function loadSettings(): Promise<any> {

    const result = await axios.post(fieldsData.restApiLoadOptionsUrl, {}, {
        headers: {
            'X-WP-Nonce': fieldsData.wpnonce
        }
    });

    const newSettings = result.data?.settings;

    if(newSettings){

        //  Iterate over every key in new settings and merge
        //  with every key in current settings reactive.
        Object.keys(newSettings).forEach(key => {
            if(Object.keys(settings).includes(key) && isObject(settings[key])){
                Object.assign(settings[key], newSettings[key]);
            } else {
                settings[key] = newSettings[key];
            }
        });

    } else {
        throw "Got wrong response containing empty settings.";
    }

}

export async function saveSettings(): Promise<any> {

    const payload = {
        settings
    };

    return axios.post(fieldsData.restApiSaveOptionsUrl, payload, {
        headers: {
            'X-WP-Nonce': fieldsData.wpnonce
        }
    });

}

export function hasCode(): Ref<boolean> {
    return toRef(settings.license, 'key');
}

export function isCodeActive(): Ref<boolean> {
    return toRef(settings.license, 'isKeyCorrect');
}

export function getProUrl(): string {
    return 'https://jetplugs.com/shop/revision-manager-v2/?utm_source=client&utm_medium=plugin&utm_campaign=revision-manager-tmc';
}