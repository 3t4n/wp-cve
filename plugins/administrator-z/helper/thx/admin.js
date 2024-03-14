jQuery(document).ready(function($) {

    $( '#acf-field_'+pa_vars.id_field_huyen).attr("disabled","disabled");
    $( '#acf-field_'+pa_vars.id_field_xa).attr("disabled","disabled");

    // update huyen
    $('body').on('change', '#acf-field_'+pa_vars.id_field_tinh, function() {
        $( '#acf-field_'+pa_vars.id_field_huyen).empty();
        $( '#acf-field_'+pa_vars.id_field_xa).empty();
        data = {
            action: 'lay_gia_tri_huyen',
            pa_nonce: pa_vars.pa_nonce,
            tinh: $(this).val(),
        };
        $.post( ajaxurl, data, function(response) {
            if( response ){

                $( '#acf-field_'+pa_vars.id_field_huyen).replaceWith('<select id="acf-field_'+pa_vars.id_field_huyen+'" name="acf[field_'+pa_vars.id_field_huyen+']"></select>');
                $( '#acf-field_'+pa_vars.id_field_huyen ).prepend( $('<option></option>').val("").html(pa_vars.label_field_huyen));
                for (var i = response.length - 1; i >= 0; i--) {

                    var text = response[i];
                    $( '#acf-field_'+pa_vars.id_field_huyen ).prepend( $('<option></option>').val(text).html(text));
                }
                $( '#acf-field_'+pa_vars.id_field_huyen).removeAttr("disabled");
            };
        });
    });

    // update xa
    $('body').on('change', '#acf-field_'+pa_vars.id_field_huyen, function() {
        $( '#acf-field_'+pa_vars.id_field_xa).empty();
        data = {
            action: 'lay_gia_tri_xa',
            pa_nonce: pa_vars.pa_nonce,
            huyen: $(this).val(),
            tinh: $( '#acf-field_'+pa_vars.id_field_tinh ).val()
        };
        $.post( ajaxurl, data, function(response) {
            if( response ){

                $( '#acf-field_'+pa_vars.id_field_xa).replaceWith('<select id="acf-field_'+pa_vars.id_field_xa+'" name="acf[field_'+pa_vars.id_field_xa+']"></select>');
                $( '#acf-field_'+pa_vars.id_field_xa ).prepend( $('<option></option>').val("").html(pa_vars.label_field_xa));
                for (var i = response.length - 1; i >= 0; i--) {
                    var text = response[i];  
                    $( '#acf-field_'+pa_vars.id_field_xa ).prepend( $('<option></option>').val(text).html(text));
                }
                $( '#acf-field_'+pa_vars.id_field_xa).removeAttr("disabled");
            };
        });
    });
});