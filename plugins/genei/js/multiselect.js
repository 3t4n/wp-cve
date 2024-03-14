    jQuery(document).ready(function ($) {
        jQuery('#grupoimpultec_agencias_personalizadas option').mousedown(function(e) {
    e.preventDefault();

    jQuery(this).parent('select').focus();

    jQuery(this).toggleClass('selected');  
    jQuery(this).prop('selected', !jQuery(this).prop('selected'));

    if (jQuery('option').hasClass('selected')) {
        jQuery(this).parent('select').addClass('is-valid');
    }
    else {
        jQuery(this).parent('select').removeClass('is-valid');
    }
});
    });