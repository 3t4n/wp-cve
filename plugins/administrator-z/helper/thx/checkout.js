jQuery(document).ready(function($) {    

    // set first disable
    var array_fields_thx = [
        'thx_checkout_vars.name_field_tinh',
        'thx_checkout_vars.name_field_huyen',
        'thx_checkout_vars.name_field_xa',
        /*'thx_checkout_vars.name_field_duong'*/
    ];


    for (var i = array_fields_thx.length - 1; i >= 0; i--) {
        $( 'form.woocommerce-checkout  [name="billing_'+eval(array_fields_thx[i])+'"]').attr("disabled","disabled");
        if(!$( 'form.woocommerce-checkout  [name="billing_'+eval(array_fields_thx[(i-1)])+'"]').length){
            $( 'form.woocommerce-checkout  [name="billing_'+eval(array_fields_thx[i])+'"]').removeAttr("disabled");
            if(thx_checkout_vars.is_select2) {
                $( 'form.woocommerce-checkout  [name="billing_'+eval(array_fields_thx[i])+'"]').select2();
            }
        }

        $( 'form.woocommerce-checkout  [name="shipping_'+eval(array_fields_thx[i])+'"]').attr("disabled","disabled");
        if(!$( 'form.woocommerce-checkout  [name="shipping_'+eval(array_fields_thx[(i-1)])+'"]').length){
            $( 'form.woocommerce-checkout  [name="shipping_'+eval(array_fields_thx[i])+'"]').removeAttr("disabled");
            if(thx_checkout_vars.is_select2) {
                $( 'form.woocommerce-checkout  [name="shipping_'+eval(array_fields_thx[i])+'"]').select2();
            }
        }
    }
    function set_empty_after(field_name,$type){
        for (var i = 0; i < array_fields_thx.length; i++) {
            let field_index = Object.keys(array_fields_thx).find(key => array_fields_thx[key] === field_name);
            if(i>field_index){
                $( '[name="'+$type+eval(array_fields_thx[i])+'"]').empty();
                $( '[name="'+$type+eval(array_fields_thx[i])+'"]')
                    .prepend( $('<option></option>')
                    .val("")
                    .html(
                        eval(array_fields_thx[i].replace("name",'label'))
                        )
                    );
                $( '[name="'+eval(array_fields_thx[i])+'"]').attr("disabled","disabled");
            }
        }

    }

    /*------------- billing ----------------*/

    // update huyen
    $('body').on('change', 'form.woocommerce-checkout [name="billing_'+thx_checkout_vars.name_field_tinh+'"]', function() {
        set_empty_after('thx_checkout_vars.name_field_tinh','billing_');
        data = {
            action: 'lay_gia_tri_huyen_checkout',
            pa_nonce: thx_checkout_vars.pa_nonce,
            tinh: $(this).val(),
        };
        $.post( thx_checkout_vars.ajax_url, data, function(response) {
            if( response.length>0 ){                
                for (var i = response.length - 1; i >= 0; i--) {
                    var text = response[i];
                    $( '[name="billing_'+thx_checkout_vars.name_field_huyen+'"]').prepend( $('<option></option>').val(text).html(text));
                }
                if(thx_checkout_vars.is_select2) {
                    $( '[name="billing_'+thx_checkout_vars.name_field_huyen+'"]').select2();
                }
                $( '[name="billing_'+thx_checkout_vars.name_field_huyen+'"]').removeAttr("disabled");
            };
        });
    });

    // update huyen
    $('body').on('change', 'form.woocommerce-checkout [name="billing_'+thx_checkout_vars.name_field_huyen+'"]', function() {
        set_empty_after('thx_checkout_vars.name_field_huyen','billing_');
        data = {
            action: 'lay_gia_tri_xa_checkout',
            pa_nonce: thx_checkout_vars.pa_nonce,
            tinh: $( '[name="billing_'+thx_checkout_vars.name_field_tinh+ '"]' ).val(),
            huyen: $(this).val(),
        };
        $.post( thx_checkout_vars.ajax_url, data, function(response) {
            if( response.length>0 ){                
                for (var i = response.length - 1; i >= 0; i--) {
                    var text = response[i];
                    $( '[name="billing_'+thx_checkout_vars.name_field_xa+'"]').prepend( $('<option></option>').val(text).html(text));
                }
                if(thx_checkout_vars.is_select2) {
                    $( '[name="billing_'+thx_checkout_vars.name_field_xa+'"]').select2();
                }
                $( '[name="billing_'+thx_checkout_vars.name_field_xa+'"]').removeAttr("disabled");
            };
        });
    });
    

    /*============== shipping===================*/

    // update huyen
    $('body').on('change', 'form.woocommerce-checkout [name="shipping_'+thx_checkout_vars.name_field_tinh+'"]', function() {
        set_empty_after('thx_checkout_vars.name_field_tinh','shipping_');
        data = {
            action: 'lay_gia_tri_huyen_checkout',
            pa_nonce: thx_checkout_vars.pa_nonce,
            tinh: $(this).val(),
        };
        $.post( thx_checkout_vars.ajax_url, data, function(response) {
            if( response.length>0 ){                
                for (var i = response.length - 1; i >= 0; i--) {
                    var text = response[i];
                    $( '[name="shipping_'+thx_checkout_vars.name_field_huyen+'"]').prepend( $('<option></option>').val(text).html(text));
                }
                if(thx_checkout_vars.is_select2) {
                    $( '[name="shipping_'+thx_checkout_vars.name_field_huyen+'"]').select2();
                }
                $( '[name="shipping_'+thx_checkout_vars.name_field_huyen+'"]').removeAttr("disabled");
            };
        });
    });

    // update huyen
    $('body').on('change', 'form.woocommerce-checkout [name="shipping_'+thx_checkout_vars.name_field_huyen+'"]', function() {
        set_empty_after('thx_checkout_vars.name_field_huyen','shipping_');
        data = {
            action: 'lay_gia_tri_xa_checkout',
            pa_nonce: thx_checkout_vars.pa_nonce,
            tinh: $( '[name="shipping_'+thx_checkout_vars.name_field_tinh+ '"]' ).val(),
            huyen: $(this).val(),
        };
        $.post( thx_checkout_vars.ajax_url, data, function(response) {
            if( response.length>0 ){                
                for (var i = response.length - 1; i >= 0; i--) {
                    var text = response[i];
                    $( '[name="shipping_'+thx_checkout_vars.name_field_xa+'"]').prepend( $('<option></option>').val(text).html(text));
                }
                if(thx_checkout_vars.is_select2) {
                    $( '[name="shipping_'+thx_checkout_vars.name_field_xa+'"]').select2();
                }
                $( '[name="shipping_'+thx_checkout_vars.name_field_xa+'"]').removeAttr("disabled");
            };
        });
    });

});