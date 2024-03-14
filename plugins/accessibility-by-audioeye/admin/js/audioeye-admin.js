(function( $ ) {
	'use strict';

	$( document ).ready( function( ) {
		const stepOne = $('.ae-step-one');
		const stepTwo = $('.ae-step-two');

		const cancelButton = $('.ae-site-id-cancel-button');
		const editButton = $('.ae-site-id-form-edit-button');
		
		cancelButton.click(function() {
			$('.ae-site-id-form-container').addClass('hidden');
			$('.ae-site-id-form-edit-button-container').removeClass('hidden');
		});

		editButton.click(function() {
			$('.ae-site-id-form-edit-button-container').addClass('hidden');
			$('.ae-site-id-form-container').removeClass('hidden');
		});

		$( '.ae-site-id-form .site-id-field input' ).focus(function ( ) {
			$('.site-id-field-container').removeClass('has-error');
		});

		$( '.ae-site-id-form' ).submit( function( event ) {
			event.preventDefault();

			const self = this;

		  const siteId = $(self).find('.site-id-field input').val();

			if (siteId.length === 0) {
				$('.site-id-field-container').addClass('has-error');
				return false;
			}

			let ajax_form_data = $(self).serialize();

			ajax_form_data = ajax_form_data+'&ajaxrequest=true&submit=Submit+Form&nonce='+params.nonce;

			$.ajax({
				url: params.ajaxurl,
				type: 'post',
				data: ajax_form_data
			})
			.done( function( response ) {
				$('.ae-site-id-form .site-id-field input').val(siteId);

				if (!stepOne.hasClass('hidden')) {
					stepOne.addClass('hidden');
					stepTwo.removeClass('hidden');
				}

				$('.ae-site-id-form-edit-button-container').removeClass('hidden');
				$('.ae-site-id-form-container').addClass('hidden');
			})
			.fail( function() {
				$(".ae-result").html( "<h2>Something went wrong.</h2><br>" );         
			});
		});
	});
})( jQuery );
