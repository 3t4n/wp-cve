(function( $ ) {
	SJEaAdmin = {

		init: function() {
			$('.handlediv.button-link, .postbox .hndle').on('click', SJEaAdmin._OpenaClosePostbox);
			
			/* Submit Contact Form */
			$('#sjea-support-form').on('submit', SJEaAdmin._submitSupportForm );
		},

		_OpenaClosePostbox: function(){
			if ($(this).parent().hasClass('closed')) {
				$(this).parent().removeClass('closed');
			} else{
				$(this).parent().addClass('closed');	
			}
		},

		/**
		 * Fires when the campaign save clicked.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_submitSupportForm: function( e )
		{
			var $this 	 = $(this);
			
			$this.find('.sjea-small-loader').removeClass('sjea-hidden');
			
			var $submit  = $this.find('#submit_request');
			var formData = $this.serialize();

			$submit.attr( 'disabled', 'disabled' );
						
			
			$.ajax( {
				url: sjea.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: formData,
						
				success: function( response, status ) {
					$this.find('.sjea-small-loader').addClass('sjea-hidden');
					
					$submit.removeAttr( 'disabled' );

					if ( ! response.success ) {
						$this.find('.sjea-form-msg').text( response.data.msg ).removeClass('sjea-hidden');
					} else {
						$this.find('.sjea-form-msg').text( response.data.msg ).removeClass('sjea-hidden');
					}
				},

				error: function( xhr, desc ) {
					$this.find('.sjea-small-loader').addClass('sjea-hidden');
					
					$this.find('.sjea-form-msg').text( desc ).removeClass('sjea-hidden');
				}
			} );
		},
	}

	$(document).ready(function() {

		SJEaAdmin.init();
	});

})( jQuery );



