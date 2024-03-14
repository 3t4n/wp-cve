jQuery(document).ready(function($) {    

    // set first disable
    var array_fields_thx = [
        'pa_vars.name_field_tinh',
        'pa_vars.name_field_huyen',
        'pa_vars.name_field_xa',
        'pa_vars.name_field_duong'
    ];
    for (var i = array_fields_thx.length - 1; i >= 0; i--) {
        $( '.adminz_woo_form  [name="'+eval(array_fields_thx[i])+'\[\]"]').attr("disabled","disabled");
        if(!$( '.adminz_woo_form  [name="'+eval(array_fields_thx[(i-1)])+'\[\]"]').length){
            $( '.adminz_woo_form  [name="'+eval(array_fields_thx[i])+'\[\]"]').removeAttr("disabled");
        }
    }


    function set_empty_after(field_name){
        
        for (var i = 0; i < array_fields_thx.length; i++) {
            let field_index = Object.keys(array_fields_thx).find(key => array_fields_thx[key] === field_name);
            if(i>field_index){
                $( '[name="'+eval(array_fields_thx[i])+'\[\]"]').empty();
                $( '[name="'+eval(array_fields_thx[i])+'\[\]"]').prepend( $('<option></option>').val("").html(eval(array_fields_thx[i].replace("name",'label'))));
                $( '[name="'+eval(array_fields_thx[i])+'\[\]"]').attr("disabled","disabled");
            }
        }

    }

    // update huyen
    $('body').on('change', '[name="'+pa_vars.name_field_tinh+'\[\]"]', function() {
        set_empty_after('pa_vars.name_field_tinh');
        data = {
            action: 'lay_gia_tri_field_frontend',
            pa_nonce: pa_vars.pa_nonce,
            key1 : pa_vars.name_field_tinh,
            value1: $(this).val(),
            key2: pa_vars.name_field_huyen,
        };
        
        $.post( 
            pa_vars.ajax_url, 
            data, 
            function(response) {
                if( response.length>0 ){                   
                    for (var i = response.length - 1; i >= 0; i--) {
                        var text = response[i];
                        //console.log(text);
                        $( '[name="'+pa_vars.name_field_huyen+'\[\]"]').prepend( $('<option></option>').val(text).html(text));
                    }
                    $( '[name="'+pa_vars.name_field_huyen+'\[\]"]').removeAttr("disabled");
                };
            }
        );
    });

    // update xa
    $('body').on('change', '[name="'+pa_vars.name_field_huyen+'\[\]"]', function() {
        set_empty_after('pa_vars.name_field_huyen');
        data = {
            action: 'lay_gia_tri_field_frontend',
            pa_nonce: pa_vars.pa_nonce,
            key1 : pa_vars.name_field_huyen,
            value1: $(this).val(),
            key2: pa_vars.name_field_xa,
        };
        $.post( 
            pa_vars.ajax_url, 
            data, 
            function(response) {
                if( response.length>0 ){                
                    for (var i = response.length - 1; i >= 0; i--) {
                        var text = response[i];
                        //console.log(text);
                        $( '[name="'+pa_vars.name_field_xa+'\[\]"]').prepend( $('<option></option>').val(text).html(text));
                    }
                    $( '[name="'+pa_vars.name_field_xa+'\[\]"]').removeAttr("disabled");
                };
            }
        );
    });

    // update duong
    $('body').on('change', '[name="'+pa_vars.name_field_xa+'\[\]"]', function() {
        set_empty_after('pa_vars.name_field_xa');
        data = {
            action: 'lay_gia_tri_field_frontend',
            pa_nonce: pa_vars.pa_nonce,
            key1 : pa_vars.name_field_xa,
            value1: $(this).val(),
            key2: pa_vars.name_field_duong,
        };
        $.post( 
            pa_vars.ajax_url, 
            data, 
            function(response) {
                if( response.length>0 ){                
                    for (var i = response.length - 1; i >= 0; i--) {
                        var text = response[i];
                        //console.log(text);
                        $( '[name="'+pa_vars.name_field_duong+'\[\]"]').prepend( $('<option></option>').val(text).html(text));
                    }
                    $( '[name="'+pa_vars.name_field_duong+'\[\]"]').removeAttr("disabled");
                };
            }
        );
    });
});