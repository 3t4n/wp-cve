import axios from "axios";
import { paymentAuthorized, updateContact, updateShippingMethod } from "./dispatcher";
import { getSetting } from '@woocommerce/settings';

const settings = getSetting('hyperpay_blocks_data', {});



export const useFetch = (url , site_url) => {

    const content_type = url == "hyperpay_process_checkout" ? "application/x-www-form-urlencoded" : "multipart/form-data"

    const instance = axios.create({
        baseURL: `${site_url}/?wc-ajax=${url}`,
        withCredentials: true,
        headers: {
            "Content-Type": content_type
        }
    });

    return instance
}

export const raiseError = (props,message = null, Json = true) => {

    if (Json) {
        props.onError(message ?? "could not create order")
    } else {
        const container = document.querySelector('.wc-block-components-notices')
        container.innerHTML = message;
    }

    return { status: "ABORT" }
}

/**
 * 
 * @param {dispatcher} dispatcher 
 * @param {string} checkoutId 
 * @param {object} shippingMethod 
 * @returns 
 */
export const generateOptions = (props,shippingMethod,checkoutId) => {

    return {

        applePay: {
            version: "3",
            buttonType:props.setting.extraScriptData.action_type,
            buttonStyle:props.setting.extraScriptData.action_style,
            requiredBillingContactFields: ['postalAddress', 'email', "phone"],
            requiredShippingContactFields: ['postalAddress', 'email', "phone"],
            currencyCode: props.billing.currency.code,
            onCancel: props.onClose,
            total: {
                label: "By HyperPay",
                amount: (props.billing.cartTotal.value ?? 0) / (10 ** props.billing.currency.minorUnit ?? 0)
            },
            onShippingMethodSelected:async function(shipping_method){
                return updateShippingMethod(props,shippingMethod,shipping_method)
            },
            onPaymentAuthorized: async function (payment) {
                return paymentAuthorized(props,shippingMethod,payment, checkoutId)
            },
            onShippingContactSelected:async function (shippingContact) {
                return updateContact(props, shippingContact)
            },
        },
    }
}

export const paymentResult = (props,response) => {

    if (response.data.result != "success")
        return raiseError(props,response.data.result)
    return {
        newTotal: response.data.total,
        newLineItems: response.data.displayItems,
        newShippingMethods: response.data.shipping_options
    }
}

export const clearUpCopyAndPay = () => {
    if (window.wpwl !== undefined && window.wpwl.unload !== undefined) {
        window.wpwl.unload();
        document.querySelectorAll("script").forEach(i => {
            if (i.src.includes('static.min.js') || i.src.includes('paymentWidgets.js')) {
                i.remove()
            }
        })
    }
}

