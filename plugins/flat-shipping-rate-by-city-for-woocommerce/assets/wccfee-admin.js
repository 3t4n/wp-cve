jQuery( function($) {
    
    $('.wccfee_addcity').click(function(){
        console.log('clicked');

        var $tr    = $('#wcc_fee_rows .wcc_fee_row:first-child');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find(':text:first-child').attr('name', 'cities[]');
        $clone.find('.wccfee_cities_fee').attr('name', 'cities_fee[]');
        $clone.find('.wccfee_delcity').attr('data-id', '');
        console.log($clone);
        $('#wcc_fee_rows').append($clone);
    });

    $(document).on('click', '.wccfee_delcity', function(){
        var id = $(this).data('id');
        if($('.wcc_fee_row').length > 1){            
            $(this).parent().remove();

            if(id){
                $('#del_citites').append('<input type="hidden" name="delcity[]" value="'+id+'"/>')
            }
        }
    });

});    