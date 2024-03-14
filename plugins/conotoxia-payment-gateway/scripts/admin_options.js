jQuery(document).ready(function ($) {
    addClassesToPaymentMethods($);
    setGenerateNewKeyAction($);
});

function addClassesToPaymentMethods($) {
    const firstPaymentMethodRow = $('.js-cx-payment-method:first').parents('tr');
    firstPaymentMethodRow.children('th, td').addClass('cx-pay-method-first');

    const betweenPaymentMethodRows = $('.js-cx-payment-method:not(:first):not(:last)').parents('tr');
    betweenPaymentMethodRows.children('th, td').addClass('cx-pay-method-between');

    const lastPaymentMethodRow = $('.js-cx-payment-method:last').parents('tr');
    lastPaymentMethodRow.children('th, td').addClass('cx-pay-method-last');
}

function setGenerateNewKeyAction($) {
    const privateKey = $('#woocommerce_conotoxia_pay_private_key');
    const publicKeyId = $('#woocommerce_conotoxia_pay_public_key_id');
    const publicKey = $('#woocommerce_conotoxia_pay_public_key');
    const generateKeyButton = $('#js-cx-generate-key-button');
    const generateKeyToast = $('#js-cx-generate-key-toast')

    generateKeyButton.click(() => {
        $.ajax({
            method: 'post',
            url: adminAjax.url,
            data: {
                action: 'cx_generate_key_pair'
            },
            dataType: 'json',
            beforeSend: () => {
                $(generateKeyButton).text(translations.generating).attr('disabled', true);
            },
            success: (keyPair) => {
                $(privateKey).val(keyPair.private);
                $(publicKeyId).val('');
                $(publicKey).val(keyPair.public);
                $(generateKeyToast).text(translations.rememberToSaveChangesAfterGeneratingKeys);
            },
            error: () => {
                $(generateKeyToast).text(translations.anErrorOccurredWhileGeneratingNewKey);
            }
        }).always(() => {
            $(generateKeyButton).text(translations.generateNewKey).attr('disabled', false);
        });
    });
}
