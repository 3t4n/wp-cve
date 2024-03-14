/* jshint asi: true */
jQuery(document).ready(function($){
	var pluginList = [
		'aweber-wp/aweber-wp.php',
		'campaign-monitor-wp/campaign-monitor-wp.php',
		'mailchimp-wp/mailchimp-wp.php',
		'mad-mimi-wp/mad-mimi-wp.php',
		'getresponse-wp/getresponse-wp.php',
	]
	
	var $deactivateButton = $('#the-list tr.active').filter( function() { return pluginList.indexOf( $(this).data('plugin') ) !== -1 } ).find('.deactivate a')
		
	$deactivateButton.click(function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_eoi.html)
		fca_eoi_uninstall_button_handlers( $deactivateButton.attr('href') )
		
	})
}) 

function fca_eoi_uninstall_button_handlers( url ) {
	var $ = jQuery
	$('#fca-eoi-deactivate-skip').click(function(){
		$(this).prop( 'disabled', true )
		window.location.href = url
	})
	$('#fca-eoi-deactivate-send').click(function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-eoi-deactivate-skip').hide()
		$.ajax({
			url: fca_eoi.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_eoi_uninstall",
				"nonce": fca_eoi.nonce,
				"msg": $('#fca-eoi-deactivate-textarea').val()
			}
		}).done( function( response ) {
			console.log ( response )
			window.location.href = url			
		})	
	})
	
}