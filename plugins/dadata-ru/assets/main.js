
let token = sh_data.dadata_api_key;
let count = sh_data.dadata_count_r;
let hint = sh_data.dadata_hint;
let minchars = sh_data.dadata_minchars;
let locations = sh_data.dadata_locations.split(",");
let ids_input = [
    ['dadata_custom_name', 'NAME', 'NAME'],  // id, type, parts
    ['dadata_custom_surname', 'NAME', 'SURNAME'],
    ['dadata_custom_fio', 'NAME'],
    ['dadata_custom_address', 'ADDRESS'],
    ['dadata_custom_party', 'PARTY'],
    ['dadata_custom_bank', 'BANK'],
    ['dadata_custom_email', 'EMAIL'],
    ['dadata_custom_domain', 'domain'],
    ['dadata_custom_oktmo', 'oktmo'],
    ['dadata_custom_fms_unit', 'fms_unit'],
    ['dadata_custom_postal_unit', 'postal_unit'],
    ['dadata_custom_fns_unit', 'fns_unit'],
    ['dadata_custom_fts_unit', 'fts_unit'],
    ['dadata_custom_region_court', 'region_court'],
    ['dadata_custom_metro', 'metro'],
    ['dadata_custom_car_brand', 'car_brand'],
    ['dadata_custom_mktu', 'mktu'],
    ['dadata_custom_country', 'country'],
    ['dadata_custom_currency', 'currency'],
    ['dadata_custom_okved2', 'okved2'],
    ['dadata_custom_okpd2', 'okpd2'],
];

// Support woo
if(sh_data.dadata_woo_off != "1") {
    ids_input.push(['billing_first_name', 'NAME', 'NAME']);
    ids_input.push(['shipping_first_name', 'NAME', 'NAME']);
    ids_input.push(['billing_last_name', 'NAME', 'SURNAME']);
    ids_input.push(['shipping_last_name', 'NAME', 'SURNAME']);
    ids_input.push(['billing_address_1', 'ADDRESS']);
    ids_input.push(['shipping_address_1', 'ADDRESS']);
    ids_input.push(['billing_email', 'EMAIL']);
    ids_input.push(['billing_company', 'PARTY']);
    ids_input.push(['billing_bank', 'BANK']);
}

let loca = [];
for (let loc of locations) {
    loca.push({country_iso_code: loc.trim()});
}

function dadata_query(id, count= 5, hint = ' ', minChars = 1, mobileWidth = 980, token,type="NAME", parts=''){

    jQuery('#'+id).suggestions({
        token: token,
        type: type,
        partner: "WORDPRESS.143338",
        count: count,
        hint: hint.toString(),
        minChars: minChars,
        mobileWidth: mobileWidth,
        params: {
            parts: [parts]
        },
        constraints: {
            locations: loca,
        },
        onSelect: function(suggestion) {
            if(id === 'billing_address_1') {
                jQuery("#billing_city").val(suggestion.data.city);
                jQuery("#billing_state").val(suggestion.data.region);
                jQuery("#billing_postcode").val(suggestion.data.postal_code);
            }
            if(id === 'shipping_address_1') {
                jQuery("#shipping_city").val(suggestion.data.city);
                jQuery("#shipping_state").val(suggestion.data.region);
                jQuery("#shipping_postcode").val(suggestion.data.postal_code);
            }
            if(id === 'billing_company') {
                jQuery('#billing_company').val(suggestion.unrestricted_value);
                jQuery('#billing_address').val(suggestion.data.address.value);
                jQuery('#billing_inn').val(suggestion.data.inn);
                jQuery('#billing_kpp').val(suggestion.data.kpp);
                jQuery('#billing_ogrn').val(suggestion.data.ogrn);
            }
            if(id === 'billing_bank') {
                jQuery('#billing_bank').val(suggestion.value);
                jQuery('#billing_bank_address').val(suggestion.data.address.unrestricted_value);
                jQuery('#billing_bank_bic').val(suggestion.data.bic);
                jQuery('#billing_bank_swift').val(suggestion.data.swift);
                jQuery('#billing_bank_correspondent_account').val(suggestion.data.correspondent_account);
            }
            if(id === 'dadata_custom_fio') {
                jQuery('#dadata_custom_fio_surname').val(suggestion.data.surname);
                jQuery('#dadata_custom_fio_name').val(suggestion.data.name);
                jQuery('#dadata_custom_fio_patronymic').val(suggestion.data.patronymic);
                jQuery('#dadata_custom_fio_gender').val(suggestion.data.gender);
            }
            if(id === 'dadata_custom_address') {
                jQuery('#dadata_custom_address_postal_code').val(suggestion.data.postal_code);
                jQuery('#dadata_custom_address_country').val(suggestion.data.country);
                jQuery('#dadata_custom_address_region').val(suggestion.data.region);
                jQuery('#dadata_custom_address_city').val(suggestion.data.city);
                jQuery('#dadata_custom_address_street').val(suggestion.data.street);
                jQuery('#dadata_custom_address_house').val(suggestion.data.house);
                jQuery('#dadata_custom_address_flat').val(suggestion.data.flat);
            }
            if(id === 'dadata_custom_party') {
                jQuery('#dadata_custom_party_inn').val(suggestion.data.inn);
                jQuery('#dadata_custom_party_kpp').val(suggestion.data.kpp);
                jQuery('#dadata_custom_party_ogrn').val(suggestion.data.ogrn);
                jQuery('#dadata_custom_party_type').val(suggestion.data.type);
                jQuery('#dadata_custom_party_name_full_with_opf').val(suggestion.data.name.full_with_opf);
                jQuery('#dadata_custom_party_address_value').val(suggestion.data.address.value);
            }
            if(id === 'dadata_custom_bank') {
                jQuery('#dadata_custom_bank_bic').val(suggestion.data.bic);
                jQuery('#dadata_custom_bank_swift').val(suggestion.data.swift);
                jQuery('#dadata_custom_bank_inn').val(suggestion.data.inn);
                jQuery('#dadata_custom_bank_kpp').val(suggestion.data.kpp);
                jQuery('#dadata_custom_bank_registration_number').val(suggestion.data.registration_number);
                jQuery('#dadata_custom_bank_correspondent_account').val(suggestion.data.correspondent_account);
                jQuery('#dadata_custom_bank_opf_type').val(suggestion.data.opf.type);
                jQuery('#dadata_custom_bank_address').val(suggestion.data.address.unrestricted_value);
            }
            if(id === 'dadata_custom_domain') {
                jQuery('#dadata_custom_domain_type').val(suggestion.data.type);
                jQuery('#dadata_custom_domain_name').val(suggestion.data.name);
                jQuery('#dadata_custom_domain_inn').val(suggestion.data.inn);
                jQuery('#dadata_custom_domain_ogrn').val(suggestion.data.ogrn);
                jQuery('#dadata_custom_domain_okved').val(suggestion.data.okved);
                jQuery('#dadata_custom_domain_okved_name').val(suggestion.data.okved_name);
                jQuery('#dadata_custom_domain_employee_count').val(suggestion.data.employee_count);
                jQuery('#dadata_custom_domain_income').val(suggestion.data.income);
                jQuery('#dadata_custom_domain_city').val(suggestion.data.city);
            }
            if(id === 'dadata_custom_fms_unit') {
                jQuery('#dadata_custom_fms_unit_code').val(suggestion.data.code);
                jQuery('#dadata_custom_fms_unit_name').val(suggestion.data.name);
                jQuery('#dadata_custom_fms_unit_region_code').val(suggestion.data.region_code);
                jQuery('#dadata_custom_fms_unit_type').val(suggestion.data.type);
            }

        }
    });
}


jQuery(document).ready(function ($) {

    // hint - Поясняющий текст, который показывается в выпадающем списке над подсказками.
    // minChars - Минимальная длина текста, после которой включаются подсказки.
    // mobileWidth - Максимальная ширина экрана, при которой будет применен вид, адаптированный для мобильных устройств.
    for(let i = 1; i <= ids_input.length; i++ ) {
        dadata_query(ids_input[i-1][0], count, hint,minchars,980,token, ids_input[i-1][1],ids_input[i-1][2]);
    }

});

if(!sh_data.dadata_use_mask) {
    document.addEventListener("DOMContentLoaded", function () {
        var phoneInputs = document.querySelectorAll('input#billing_phone, input#dadata_custom_phone');

        var getInputNumbersValue = function (input) {
            // Return stripped input value — just numbers
            return input.value.replace(/\D/g, '');
        }

        var onPhonePaste = function (e) {
            var input = e.target,
                inputNumbersValue = getInputNumbersValue(input);
            var pasted = e.clipboardData || window.clipboardData;
            if (pasted) {
                var pastedText = pasted.getData('Text');
                if (/\D/g.test(pastedText)) {
                    // Attempt to paste non-numeric symbol — remove all non-numeric symbols,
                    // formatting will be in onPhoneInput handler
                    input.value = inputNumbersValue;
                    return;
                }
            }
        }

        var onPhoneInput = function (e) {
            var input = e.target,
                inputNumbersValue = getInputNumbersValue(input),
                selectionStart = input.selectionStart,
                formattedInputValue = "";

            if (!inputNumbersValue) {
                return input.value = "";
            }

            if (input.value.length != selectionStart) {
                // Editing in the middle of input, not last symbol
                if (e.data && /\D/g.test(e.data)) {
                    // Attempt to input non-numeric symbol
                    input.value = inputNumbersValue;
                }
                return;
            }

            if (["7", "8", "9"].indexOf(inputNumbersValue[0]) > -1) {
                if (inputNumbersValue[0] == "9") inputNumbersValue = "7" + inputNumbersValue;
                var firstSymbols = (inputNumbersValue[0] == "8") ? "8" : "+7";
                formattedInputValue = input.value = firstSymbols + " ";
                if (inputNumbersValue.length > 1) {
                    formattedInputValue += '(' + inputNumbersValue.substring(1, 4);
                }
                if (inputNumbersValue.length >= 5) {
                    formattedInputValue += ') ' + inputNumbersValue.substring(4, 7);
                }
                if (inputNumbersValue.length >= 8) {
                    formattedInputValue += '-' + inputNumbersValue.substring(7, 9);
                }
                if (inputNumbersValue.length >= 10) {
                    formattedInputValue += '-' + inputNumbersValue.substring(9, 11);
                }
            } else {
                formattedInputValue = '+' + inputNumbersValue.substring(0, 16);
            }
            input.value = formattedInputValue;
        }
        var onPhoneKeyDown = function (e) {
            // Clear input after remove last symbol
            var inputValue = e.target.value.replace(/\D/g, '');
            if (e.keyCode == 8 && inputValue.length == 1) {
                e.target.value = "";
            }
        }
        for (var phoneInput of phoneInputs) {
            phoneInput.addEventListener('keydown', onPhoneKeyDown);
            phoneInput.addEventListener('input', onPhoneInput, false);
            phoneInput.addEventListener('paste', onPhonePaste, false);
        }
    })
}

jQuery(document.body).on('updated_checkout', function () {
    for(let i = 1; i <= ids_input.length; i++ ) {
        dadata_query(ids_input[i-1][0], count, hint,minchars,980,token, ids_input[i-1][1],ids_input[i-1][2]);
    }
});
