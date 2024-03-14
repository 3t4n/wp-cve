( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.rock-convert-exclude-pages-add', function () {
		$( '.rock-convert-exclude-pages-link' )
			.first()
			.clone()
			.appendTo( '.rock-convert-exclude-pages' )
			.children( 'input[type=text]' )
			.val( '' )
			.focus();
	} );

	// Exclude link
	$( document ).on(
		'click',
		'.rock-convert-exclude-pages-remove',
		function () {
			$( this ).parent().remove();
		}
	);

	$( document ).on(
		'change',
		'input[name="rock_convert_visibility"]',
		function () {
			var selected = $(
				'input[name="rock_convert_visibility"]:checked'
			).val();

			if ( selected == 'exclude' ) {
				$( '.rock-convert-exclude-control' ).show();
			} else {
				$( '.rock-convert-exclude-control' ).hide();
			}
		}
	);

	function initColorPicker( widget ) {
		widget.find( '.color-picker' ).wpColorPicker( {
			change: function ( e, ui ) {
				$( e.target ).val( ui.color.toString() );
				$( e.target ).trigger( 'change' );
			},
			clear: function ( e, ui ) {
				$( e.target ).trigger( 'change' );
			},
		} );
	}

	function onFormUpdate( event, widget ) {
		initColorPicker( widget );
	}

	$( document ).on( 'widget-added widget-updated', onFormUpdate );

	$( document ).ready( function () {
		$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
			initColorPicker( $( this ) );
		} );

		( function ( $ ) {
			// Add Color Picker to all inputs that have 'color-picker' class
			$( function () {
				$( '.color-picker-popup' ).wpColorPicker();
			} );
		} )( jQuery );

		jQuery( '.rconvert_announcement_bar_page .color-picker' ).each(
			function () {
				jQuery( this ).wpColorPicker( {
					change: function ( event, ui ) {
						var target = event.target.id;
						var c = ui.color.toString();
						var property =
							target === 'rconvert_announcement_text_color' ||
							target === 'rconvert_announcement_btn_text_color'
								? { color: c }
								: { backgroundColor: c };

						jQuery( '.' + target ).css( property );
					},
				} );
			}
		);
	} );

	$( document ).ready( function () {
		let ajax_url = $( '.widefat' ).attr( 'data-ajaxurl' );
		let isRunning = false;
		$( '.rock-lead-delete-action' ).each( function () {
			$( this ).on( 'click', 'a', function ( e ) {
				e.preventDefault();
				let entry_id = $( this ).attr( 'data-entry' );
				let nonce = $( this ).attr( 'data-nonce' );
				let email = $( this ).attr( 'data-email' );

				if ( ! isRunning ) {
					isRunning = true;
					$.ajax( {
						url: ajax_url,
						type: 'POST',
						data: {
							action: 'confirm_delete_lead',
							security: nonce,
							email: email,
							entry: entry_id,
						},
						beforeSend: function () {
							$( 'body.wp-admin' ).addClass(
								'rock-ajax-request-load'
							);
						},
					} )
						.success( function ( data ) {
							$( 'body' ).append( data );
						} )
						.complete( function () {
							$( 'body.wp-admin' ).removeClass(
								'rock-ajax-request-load'
							);
							isRunning = false;
							let delete_actions = $( '.delete-actions' );
							delete_actions.on(
								'click',
								'.cancel-action',
								function ( e ) {
									e.preventDefault();
									$(
										'.rock-confirm-delete-lead ~ span'
									).remove();
									$( '.rock-confirm-delete-lead' ).remove();
								}
							);

							delete_actions.on(
								'click',
								'.confirm-action',
								function ( e ) {
									e.preventDefault();
									let entry_id =
										$( this ).attr( 'data-entry' );
									if ( ! isRunning ) {
										isRunning = true;
										$.ajax( {
											url: ajax_url,
											type: 'POST',
											data: {
												action: 'delete_lead',
												security: nonce,
												entry: entry_id,
											},
											beforeSend: function () {
												$( 'body.wp-admin' ).addClass(
													'rock-ajax-request-load'
												);
											},
										} ).complete( function () {
											$( 'body.wp-admin' ).removeClass(
												'rock-ajax-request-load'
											);
											$( '#entry-' + entry_id ).remove();
											$(
												'.rock-confirm-delete-lead ~ span'
											).remove();
											$(
												'.rock-confirm-delete-lead'
											).remove();
											isRunning = false;
										} );
									}
								}
							);
						} );
				}
			} );
		} );
	} );

	$( document ).ready( function () {
		let custom_field_enabled = $( '#rock_convert_enable_custom_field' );
		let custom_field_label = $( '#rock-convert-label-container' );
		let custom_field_label_val = $( '#rock_convert_custom_field_label' );
		if ( custom_field_enabled.is( ':checked' ) ) {
			custom_field_label.removeClass( 'd-none' );
		} else {
			custom_field_label.addClass( 'd-none' ).removeAttr( 'style' );
		}
		$( 'body' ).on( 'click', custom_field_enabled, function () {
			if ( $( '#rock_convert_enable_custom_field' ).is( ':checked' ) ) {
				custom_field_label
					.removeClass( 'd-none' )
					.css( 'display', 'block' );
				custom_field_label_val.attr( 'required', 'required' );
			} else {
				custom_field_label.addClass( 'd-none' ).removeAttr( 'style' );
				custom_field_label_val.removeAttr( 'required' ).val( '' );
			}
		} );
	} );
} )( jQuery );
