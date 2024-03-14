jQuery( function( $ ) {

    if($('#discount_type').val() === 'easypack_inpost_discount') {
        $('#easypack_list_configured_inpost_methods').show();
    }

    $('#discount_type').on('change', function () {
        if($(this).val() === 'easypack_inpost_discount') {
            $('#easypack_list_configured_inpost_methods').show();

        } else {
            $('#easypack_list_configured_inpost_methods').hide();

        }
    })

} );