jQuery(document).ready(function() {

    var toggle_fields = function(show) {
        if (show) {
            jQuery('#billing_company_field').fadeIn();
            jQuery('#billing_company_wi_id_field').fadeIn();
            jQuery('#billing_company_wi_vat_field').fadeIn();
            jQuery('#billing_company_wi_tax_field').fadeIn();
        }
        else {
            jQuery('#billing_company_field').fadeOut();
            jQuery('#billing_company_wi_id_field').fadeOut();
            jQuery('#billing_company_wi_vat_field').fadeOut();
            jQuery('#billing_company_wi_tax_field').fadeOut();
        }
    }

    jQuery('#wi_as_company').change(function() {
       toggle_fields(jQuery(this).is(':checked'));
    });

    toggle_fields(jQuery('#wi_as_company').is(':checked'))
});
