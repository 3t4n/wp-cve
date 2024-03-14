/* jshint asi: true */
jQuery(document).ready( function($) {
	$( document ).on( 'click', '[data-optin-cat]', function( e ) {
		e.preventDefault()
		var id = $( this ).data( 'optin-cat' )
		add_impression( id )
		var $lightbox =  $( '#fca_eoi_lightbox_' + id )
		$.featherlight( $lightbox, { variant: 'fca_eoi_featherlight', closeOnClick: false, afterOpen: function(){
			var $instance = this.$instance
			if ( $lightbox.hasClass('animated') ) {
				setTimeout( function(){
					$instance.find('span.featherlight-close-icon.featherlight-close').show()
					$instance.find('.fca_eoi_form_input_element').last().focus()
				}, 600)
			} else {
				$instance.find('span.featherlight-close-icon.featherlight-close').show()
				$instance.find('.fca_eoi_form_input_element:visible').first().focus()
			}
		}})
	})
	
	function add_impression( id ) {
		$.ajax({
			url: fcaEoiScriptData.ajax_url,
			type: 'POST',
			data: {
				nonce: fcaEoiScriptData.nonce,
				form_id: id,
				action: 'fca_eoi_activity'
			}
		})
	}
	
})

