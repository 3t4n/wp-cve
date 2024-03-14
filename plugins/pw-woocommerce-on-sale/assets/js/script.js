jQuery(function() {

    jQuery('#pwos-product-categories-included-select-all').on('click', function(e) {
        jQuery('#pwos-product-categories-included option').prop('selected', true);
        jQuery('#pwos-product-categories-included').focus();
        e.preventDefault();
        return false;
    });

    jQuery('#pwos-product-categories-included-select-none').on('click', function(e) {
        jQuery('#pwos-product-categories-included').val([]);
        e.preventDefault();
        return false;
    });
});

function pwosWizardLoadStep(step, validate) {
    // This is located in all_steps.php since it's dynamic.
    if (validate == true && pwosWizardValidateStep(step - 1) == false) {
        return;
    }

    jQuery('#pwos-main-content').css('display', 'none');
    jQuery('.pwos-wizard-step').css('display', 'none');
    jQuery('#pwos-wizard-step-saving').css('display', 'none');
    jQuery('#pwos-wizard-step-' + step).css('display', 'inline-block');
    if (step > 1) {
        jQuery('#pwos-wizard-step-' + step).find('input[type=text],textarea,select').filter(':visible:first').focus();
    }
}

function pwosWizardFinish() {
    // This is located in all_steps.php since it's dynamic.
    if (pwosWizardValidateStep(pwosLastStep) == false) {
        return;
    }

    jQuery('.pwos-wizard-step').css('display', 'none');
    jQuery('#pwos-wizard-step-saving').css('display', 'inline-block');

    jQuery.post(
        ajaxurl,
        {
            'action': 'pw-on-sale-save',
            'sale_id': pwosGetParameterByName('sale_id'),
            'begin_date': jQuery('#pwos-begin-date').val(),
            'begin_time': jQuery('#pwos-begin-time').val(),
            'end_date': jQuery('#pwos-end-date').val(),
            'end_time': jQuery('#pwos-end-time').val(),
            'discount_percentage': jQuery('#pwos-discount-percentage').val(),
            'title': jQuery('#pwos-title').val()
        },
        function( result ) {
            if (result.complete == true) {
                window.location.replace(window.location.href.split('?')[0] + '?page=pw-on-sale');

            } else {
                alert(result.message);

                if (result.step) {
                    pwosWizardLoadStep(result.step);
                } else {
                    pwosWizardLoadStep(pwosLastStep);
                }
            }
        }
    );
}

function pwosWizardClose() {
    jQuery('.pwos-wizard-step').css('display', 'none');
    jQuery('#pwos-main-content').show();
}

function pwosDeleteSale(saleId) {
    if (confirm('Are you sure you want to delete this sale? This cannot be undone.')) {
        jQuery.post(ajaxurl, {'action': 'pw-on-sale-delete', 'sale_id': saleId}, function() {
            location.reload();
        });
    }
}

// Source: https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function pwosGetParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
