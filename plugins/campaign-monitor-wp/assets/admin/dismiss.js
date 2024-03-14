/* jshint asi: true */

jQuery( document ).ready( function( $ ) {
	
	$('#fca-eoi-dismiss-review-btn').click( dismiss )

	function dismiss() {
		$(this).closest('.fca_eoi_review_div').hide()
		
		$.ajax({
			url: fcaEoiDismiss.ajax_url,
			type: 'POST',
			data: {
				"nonce": fcaEoiDismiss.nonce,
				"action": 'fca_eoi_dismiss',
				"option" : $(this).data("option")
			}
		}).done( function( returnedData ) {
			console.log ( returnedData )			
		})

	}

})