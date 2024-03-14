import {computed, ComputedRef, Ref, unref} from "vue";
import {isCodeActive} from "../settings";
import _ from "lodash";

export function fieldValComputedArrayToListWithOnes(varRef: Ref){

    return computed({
        get(){
            const chosen = varRef.value || {};
            return Object.keys(chosen).map(key => chosen[key] === "1" ? key : false).filter(val => val);
        },
        set(val: Array<string>){

            const result = {};

            for(const key of val){
                result[key] = "1";
            }

            varRef.value = result;

        }
    });

}

export function computedListOfAvailablePostTypes(varRef: Ref): ComputedRef<{key: string, label: string, disabled: boolean }[]>{
    return computed<any>(() => {

        const result = {};

        _.forEach(varRef.value, (value, key) => {
            result[key] = {
                key: key,
                label: value,
                disabled: unref(isCodeActive()) ? false : !['post'].includes(key)
            }
        });

        return result;

    });
}