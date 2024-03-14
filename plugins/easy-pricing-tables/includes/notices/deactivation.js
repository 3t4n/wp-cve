jQuery(document).ready(function($){
	
	var $deactivateButton = $('#the-list tr.active').filter( function() { return $(this).data('plugin') === 'easy-pricing-tables/pricing-table-plugin.php' } ).find('.deactivate a')

	$deactivateButton.on( 'click', function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_ptp.html)
		fca_ptp_uninstall_button_handlers( $deactivateButton.attr('href') )
		
	})
})

function fca_ptp_uninstall_button_handlers( url ) {
	var $ = jQuery
	$('#fca-ept-deactivate-skip').on( 'click', function(){
		$(this).prop( 'disabled', true )
		window.location.href = url
	})
	$('#fca-ept-deactivate-send').on( 'click', function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-ept-deactivate-skip').hide()
		$.ajax({
			url: fca_ptp.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_ptp_uninstall",
				"nonce": fca_ptp.nonce,
				"msg": $('#fca-ept-deactivate-textarea').val()
			}
		}).done( function( response ) {
			window.location.href = url			
		})	
	})
	
}