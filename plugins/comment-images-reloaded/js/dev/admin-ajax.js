jQuery(function($){

	$('#convert_images, #convert_CA_images').click(function(e){
		e.preventDefault();
        var answer,
            action;

        if( $(this).attr('data-action') == 'CI'){
            answer = my_CI_alert();
            action = "convert_CI";
        }else {
            answer = my_CA_alert();
            action = "convert_CA"
        }

		if(answer){
			$.post(
                cmr_reloaded_ajax_object.ajax_url, 

                {
                    action: action,
                },

                function(response) {
                		$('.updated.settings-error.notice.is-dismissible').show();
                		//var count = num2word(response);
                		$('.responce_convert').text(response);
                    });
        }
	});

    // delete images
    $('.delete-cid').click( function(e){
        e.preventDefault();
        var wrap = $(this).parents('.ci-wrapper');
        var td = $(this).parents('td');

        if ( confirm(cmr_reloaded_ajax_object.before_delete_text) ) {

            $.post(
                cmr_reloaded_ajax_object.ajax_url, 

                {
                    action: "cir_delete_image",
                    cid: $(this).attr('data-cid'),
                    aid: $(this).attr('data-aid'),
                },

                function(response) {
                    console.log(response);
                    if ( 'true' == response ) {
                        $(wrap).html( cmr_reloaded_ajax_object.after_delete_text );
                        $(td).find('.ci-deleted').remove();
                        $(wrap).addClass('ci-deleted');
                    }
                }
            );
            
        } // end confirm check

    });

	
});