jQuery(document).ready(
function($) {	
	
$(".wfw_widget_container").each(
function() {
	var id = $(this).attr("data-id");
	//alert(id)
	var div_loading = '#wfw_widget_loading_' + id;
	var div_show = '#wfw_widget_show_' + id;
	
	var sc_atts = $(this).data( "sc_atts" )

	//var post = <?php echo get_post( post_id ); ?>;
	//alert( sc_atts.lat );
	
	// Set a timeout before doing ajax call
	var timeDelay = 2000;           	// MILLISECONDS (2 SECONDS).
    setTimeout(ajax_call, timeDelay);  	// MAKE THE AJAX CALL AFTER A FEW SECONDS DELAY.
	
	// This does the ajax request (The Call).
    function ajax_call() {
		$.ajax({
			url: wp_transfer_to_ajax.ajaxurl,
			data: {
			  action: wp_transfer_to_ajax.ajaxaction, 	// This is our PHP function ('wfw_process_ajax') below
			  security: wp_transfer_to_ajax.ajaxnonce,
			  post_id: wp_transfer_to_ajax.post_id,
			  sc_atts: sc_atts
			},
			beforeSend: function() {
				// Showing a loading spinner
				//$("#wfw_widget_loading").addClass("hidden");
				$( div_loading ).addClass("hidden");
			},
			success: function( response )
			{
				//alert(response)
				
				// ERROR HANDLING
				if( !response.success )
				{
					//alert('error')
					
					// No data came back, maybe a security error
					if( !response.data )
						//$( '#wfw_widget_show' ).html( 'AJAX ERROR: no response' );
						$( div_show ).html( 'AJAX ERROR: no response' );
					else
						//$( '#wfw_widget_show' ).html( response.data.error );
						$( div_show ).html( response.data.error );
				}
				else
					//alert('success ' + div_show + ' ' + response.data)
					//$( '#wfw_widget' ).html( response.data );
					//$( '#wfw_widget_show' ).html( response.data );
					$( div_show ).html( response.data );
			},
			complete: function() {
				//$('#wfw_widget_loading').addClass("hidden");
				$( div_loading ).addClass("hidden");
			},
		});
	};
});
}
);
