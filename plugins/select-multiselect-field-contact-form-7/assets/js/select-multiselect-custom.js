/*Select and Multi-Select Custom jQuery Events*/
jQuery('.wpcf7').ready(function(){
    jQuery('select.wpcf7-selct-multiselct').select2();
    jQuery('select.wpcf7-selct-multiselct').val(null).trigger('change');
});

jQuery(".wpcf7").on( 'wpcf7:mailsent', function( event ){
    jQuery('select.wpcf7-selct-multiselct').val(null).trigger('change');
});