jQuery(function(){
    jQuery('.wrld-welcome-popup-modal').fadeIn(500);

    jQuery('button.modal-button-reports').on('click', function(){
        event.preventDefault();
        
        jQuery.ajax({
			url: wrld_welcome_modal_script_data.wp_ajax_url,
			type: 'post',
			data: {
                'action': 'wrld_welcome_modal_action',
                'nonce':wrld_welcome_modal_script_data.nonce,
                'close_modal':true
            },
			timeout: 60000, // sets timeout to 1 minute
			success: function(response){
                //do nothing
		  	},
		  	error: function(eventData){
			},
		});
        jQuery('.wrld-welcome-popup-modal').hide();
    });
});