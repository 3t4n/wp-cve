/* global jQuery */
(function($) {
	$(function() {
		let modal = $( '#ephd-deactivate-modal' );
		let deactivateLink = $( '#the-list' ).find( '[data-slug="help-dialog"] span.deactivate a' );

		// Open modal
		deactivateLink.on( 'click', function( e ) {
			e.preventDefault();

			modal.addClass( 'modal-active' );
			deactivateLink = $( this ).attr( 'href' );
			modal.find( 'a.ephd-deactivate-skip-modal' ).attr( 'href', deactivateLink );
		});

		// Close modal; Cancel
		modal.on( 'click', 'button.ephd-deactivate-cancel-modal', function( e ) {
			e.preventDefault();
			modal.removeClass( 'modal-active' );
		});

		// Reason change
		modal.on( 'click', 'input[type="radio"]', function () {
			let parent = $( this ).parents( 'li' );
			let inputValue = $( this ).val();

			$( 'ul.ephd-deactivate-reasons li' ).removeClass( 'ephd-deactivate-reason-selected' );

			parent.addClass( 'ephd-deactivate-reason-selected' );

			$( '.ephd-deactivate-modal-reason-inputs' ).removeClass( 'inputs-active' );
			$( '.ephd-deactivate-modal-reason-inputs--' + inputValue ).addClass( 'inputs-active' ).find( 'textarea' ).focus();
		});

		// Click submit button
		modal.on( 'click', '.ephd-deactivate-submit-modal', function( e ) {
			e.preventDefault();
			
			// set required attr for visible required fields only
			modal.find( 'input[data-required="true"]' ).removeAttr( 'required' );
			modal.find('.inputs-active input[data-required="true"]').prop( 'required', true );

			// submit form
			modal.find( 'form#ephd-deactivate-feedback-dialog-form' ).trigger( 'submit' );
		});

		// Submit form
		modal.on( 'submit', 'form#ephd-deactivate-feedback-dialog-form', function( e ) {
			e.preventDefault();

			if ( ! this.reportValidity() ) {
				return;
			}

			let button = $( this ).find( '.ephd-deactivate-submit-modal' );

			if ( button.hasClass( 'disabled' ) ) {
				return;
			}

			let formData = $( '#ephd-deactivate-feedback-dialog-form', modal ).serialize();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData,
				beforeSend: function() {
					button.addClass( 'disabled' );
					button.text( 'Processing...' );
				},
				complete: function() {
					window.location.href = deactivateLink;
				}
			});
		});

	});
}(jQuery));