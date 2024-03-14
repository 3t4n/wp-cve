import { paymentResult, raiseError, useFetch } from "./Helpers";



export const updateContact = async (props,shippingContact) => {
    return useFetch("hyperpay_update_checkout" , props.setting.site_url)
        .post("", shippingContact)
        .then((response)=>paymentResult(props,response))
        .catch(err => raiseError(err.response.data))
}

export const updateShippingMethod = async (props,shippingMethod,shipping_method) => {

    const data = {
        shipping_method: shipping_method.identifier,
        is_product_page: false
    }
    shippingMethod.current = shipping_method
    return useFetch("hyperpay_update_method" , props.setting.site_url)
        .post("", data)
        .then((response)=>paymentResult(props,response))
        .catch(err => raiseError(err, err.response))
}

export const  paymentAuthorized = async (props,shippingMethod,payment, checkoutId) => {
    const data = {
        _wpnonce: props.setting.nonce,
        checkoutId: checkoutId,
        payment_method: props.setting.name,
        create_account: false,
        billing_first_name: payment.billingContact.givenName,
        billing_last_name: payment.billingContact.familyName,
        billing_email: payment.shippingContact.emailAddress,
        billing_phone: payment.shippingContact.phoneNumber,
        billing_country: payment.billingContact.countryCode,
        billing_address_1: payment.billingContact.addressLines[0],
        billing_address_2: payment.billingContact.addressLines[1] ?? null,
        billing_city: payment.billingContact.locality,
        billing_state: payment.billingContact.administrativeArea ? payment.billingContact.administrativeArea : payment.billingContact.locality,
        billing_postcode: payment.billingContact.postalCode,
        shipping_first_name: payment.shippingContact.givenName,
        shipping_last_name: payment.shippingContact.familyName,
        shipping_company: '',
        shipping_country: payment.shippingContact.countryCode,
        shipping_address_1: payment.shippingContact.addressLines[0],
        shipping_address_2: payment.shippingContact.addressLines[1] ?? null,
        shipping_city: payment.shippingContact.locality,
        shipping_state: payment.shippingContact.administrativeArea ? payment.shippingContact.administrativeArea : payment.shippingContact.locality,
        shipping_postcode: payment.shippingContact.postalCode,
        shipping_method: shippingMethod.current.identifier,
        order_comments: '',
        ship_to_different_address: 1,
        terms: 1,
    }
    return useFetch("hyperpay_process_checkout" , props.setting.site_url)
        .post('', data)
        .then(res => {
            if (res.data.result != "SUCCESS") {
                return raiseError(props,res.data.messages, false)
            }
            return { status: "SUCCESS" }
        })
        .catch(err => raiseError(props,err.response.data))
        .finally(props.onClose)
}


