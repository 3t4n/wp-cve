if (jQuery('body').hasClass('single-product') && jQuery('a.button-buynow').length > 0) {
    jQuery('input.qty').change(function () {
        var url = jQuery('a.button-buynow').attr('href');
        var newUrl = url.slice(0, -url.split('=').slice(-1)[0].length) + jQuery(this).val();
        jQuery('a.button-buynow').attr('href', newUrl);
    });
}

function og_bind_form_events() {
    jQuery(document).on("checkout_error", og_bind_form_events_internal);
    og_bind_form_events_internal();
}
function og_bind_form_events_internal() {
    jQuery("input[name=wc-officeguy-payment-token]")
        .off("change.OfficeGuy")
        .on("change.OfficeGuy", og_bind_refreshforms);
    og_bind_refreshforms();

    jQuery('input[name="og-ccnum"], input[name="og-citizenid"], input[name="og-cvv"]').on("change", function () {
        jQuery(".og-errors").hide();
    });
    var Form = jQuery("#og-ccnum").closest("form");
    Form.data("og-ignorewoocommerceevents", "0");
    if (Form.is(".woocommerce-checkout"))
        Form.off('checkout_place_order_officeguy').on('checkout_place_order_officeguy', og_submit_event);
    else if (Form.is("#order_review, #add_payment_method"))
        Form.on('submit.OfficeGuy').on('submit.OfficeGuy', og_submit_event);
}
function og_bind_refreshforms() {
    var Value = jQuery("input[name=wc-officeguy-payment-token]:checked").length == 0 ? "new" : jQuery("input[name=wc-officeguy-payment-token]:checked").val();
    if (Value == "new") {
        jQuery(".og-token-form").hide().find("select").attr("disabled", "disabled");
        jQuery(".og-payment-form").show().find("select").removeAttr("disabled");
    }
    else {
        jQuery(".og-token-form").show().find("select").removeAttr("disabled");
        jQuery(".og-payment-form").hide().find("select").attr("disabled", "disabled");
    }

}

function og_submit_event() {
    if (jQuery("input[name=payment_method]:checked").val() != "officeguy")
        return true;

    var Form = jQuery("#og-ccnum").closest("form");
    if (Form.data("og-ignorewoocommerceevents") == "1")
        return false;
    if (Form.data("og-ignorewoocommerceevents") == "2")
        return true;
    if (Form.find("input[name=wc-officeguy-payment-token]:checked").length != 0 && Form.find("input[name=wc-officeguy-payment-token]:checked").val() != "new")
        return true;

    jQuery(".og-errors").empty().hide();

    var Invalid = false;
    var Input_CardNumber = Form.find("input[name=og-ccnum]");
    if (Input_CardNumber.length > 0) {
        Input_CardNumber.val(Input_CardNumber.val().trim());
        Input_CardNumber.closest('p')
            .toggleClass("validate-required woocommerce-invalid woocommerce-invalid-required-field", Input_CardNumber.val().length == 0)
            .toggleClass("woocommerce-validated", Input_CardNumber.val().length != 0);
        if (Input_CardNumber.val().length == 0) {
            jQuery(".og-errors").show().append(jQuery('<div>').text(Input_CardNumber.attr("data-og-message")));
            Invalid = true;
        }
    }
    var Input_ExpirationMonth = Form.find("select[name=og-expmonth]");
    var Input_ExpirationYear = Form.find("select[name=og-expyear]");
    if (Input_ExpirationMonth.length > 0 && Input_ExpirationYear.length > 0) {
        Input_ExpirationMonth.val(Input_ExpirationMonth.val().trim());
        Input_ExpirationYear.val(Input_ExpirationYear.val().trim());
        Input_ExpirationMonth.closest('p')
            .toggleClass("validate-required woocommerce-invalid woocommerce-invalid-required-field", Input_ExpirationMonth.val().length == 0 || Input_ExpirationYear.val().length == 0)
            .toggleClass("woocommerce-validated", Input_ExpirationMonth.val().length != 0 && Input_ExpirationYear.val().length != 0);
        if (Input_ExpirationMonth.val().length == 0 || Input_ExpirationYear.val().length == 0) {
            jQuery(".og-errors").show().append(jQuery('<div>').text(Input_ExpirationYear.attr("data-og-message")));
            Invalid = true;
        }
    }

    var Input_CitizenID = Form.find("input[name=og-citizenid]");
    if (Input_CitizenID.length > 0) {
        Input_CitizenID.val(Input_CitizenID.val().trim());
        if (Input_CitizenID.is("[required]")) {
            Input_CitizenID.closest('p')
                .toggleClass("validate-required woocommerce-invalid woocommerce-invalid-required-field", Input_CitizenID.val().length == 0)
                .toggleClass("woocommerce-validated", Input_CitizenID.val().length != 0);
            if (Input_CitizenID.val().length == 0) {
                jQuery(".og-errors").show().append(jQuery('<div>').text(Input_CitizenID.attr("data-og-message")));
                Invalid = true;
            }
        }
    }
    var Input_CVV = Form.find("input[name=og-cvv]");
    if (Input_CVV.length > 0) {
        Input_CVV.val(Input_CVV.val().trim());
        if (Input_CVV.is("[required]")) {
            Input_CVV.closest('p')
                .toggleClass("validate-required woocommerce-invalid woocommerce-invalid-required-field", Input_CVV.val().length == 0)
                .toggleClass("woocommerce-validated", Input_CVV.val().length != 0);
            if (Input_CVV.val().length == 0) {
                jQuery(".og-errors").show().append(jQuery('<div>').text(Input_CVV.attr("data-og-message")));
                Invalid = true;
            }
        }
    }
    if (Invalid)
        return false;

    Form.data("og-ignorewoocommerceevents", "1");
    var Settings = {
        FormSelector: Form,
        CompanyID: OG_Settings.CompanyID,
        APIPublicKey: OG_Settings.APIPublicKey,
        Environment: OG_Settings.Environment,
        ResponseLanguage: OG_Settings.ResponseLanguage,
        Callback: function (TokenValue) {
            Form.removeClass("processing");
            if (TokenValue != null) {
                Form
                    .data("og-ignorewoocommerceevents", "2")
                    .trigger("submit");
            }
            else {
                Form.find("input#og-token").val("");
                Form.data("og-ignorewoocommerceevents", "0");
            }
            jQuery(".og-errors").toggle(jQuery(".og-errors").text() != "");
        }
    };

    Form.find("input#og-token").val("");
    if (OfficeGuy.Payments.CreateToken(Settings)) {
        Form.data("og-ignorewoocommerceevents", "0");
        return true;
    }
    Form.addClass("processing");

    return false;
}

function og_buy_now_url(IsVariable, ProductID, CheckoutURL) {
    if (IsVariable)
        ProductID = document.querySelector('[name=variation_id]').value;
    if (ProductID == 0)
        return;
    var Quantity = document.querySelector('[name=quantity]').value;
    window.location.href = CheckoutURL + (CheckoutURL.indexOf('?') == -1 ? '?' : '&') + 'add-to-cart=' + ProductID + '&quantity=' + Quantity;
}

function OfficeGuyCheckSubscriptionProduct() {
    var ProductType = jQuery('#product-type').find(":selected").val();
    if (ProductType.toLowerCase().indexOf("subscription") != -1) {
        jQuery('#OfficeGuySubscription').prop("checked", false).hide();
        jQuery('[for=OfficeGuySubscription]').hide();
        jQuery('li.officeguy_options').hide();
    }
    else {
        jQuery('#OfficeGuySubscription').show();
        jQuery('[for=OfficeGuySubscription]').show();
    }
}

jQuery(document).on("change", "#product-type", OfficeGuyCheckSubscriptionProduct);
