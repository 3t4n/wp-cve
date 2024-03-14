var fi_session = {
	'search_user_data': [],
};

jQuery.noConflict();
( function ( $ ) {

	FiwG = {
		onChangeState: function () {
			$( 'body.post-type-inspire_invoice' ).on( 'keyup keypress', 'select, input', function ( e ) {
				$( 'body' ).addClass( 'invoice-changed' );
			} );
		},
		editMetaBoxData: function () {
			jQuery( 'button.edit-ocs-data' ).click( function ( e ) {
				let display = jQuery( this ).closest( '.ocs-meta-col' ).find( '.display' )
				let edit = jQuery( this ).closest( '.ocs-meta-col' ).find( '.edit_data' )

				if ( display.is( ':visible' ) ) {
					display.hide();
					edit.show();
				} else {
					display.show();
					edit.hide();
				}
			} );
		},
		userDataAndPayment: function () {
			$( 'body.post-type-inspire_invoice .get_user_data' ).click( function ( e ) {
				if ( fi_session.search_user_data.length !== 0 ) {
					console.log( fi_session.search_user_data );
					for ( i in fi_session.search_user_data ) {
						let field = $( '[name=client\\[' + i + '\\]]' );
						let name = field.attr( 'name' );
						if ( i === 'country' ) {
							console.log( fi_session.search_user_data[ i ] );
							field.val( fi_session.search_user_data[ i ] ).change();
						} else {
							field.val( fi_session.search_user_data[ i ] );
						}

					}
				}
				return false;
			} );


			$( '#payment' ).on( 'change', '#inspire_invoices_payment_status', function ( e ) {
				let field_to_change = $( '#inspire_invoices_total_paid' );
				if ( $( this ).val() === 'paid' ) {
					field_to_change.val( $( '#inspire_invoices_total_price' ).val() );
				} else {
					field_to_change.val( 0.0 );
				}
			} );

			var show_signatures = $( '#inspire_invoices_show_signatures' );
			var signature_row = $( 'tr.signature-user' );

			if ( show_signatures.length ) {
				if ( show_signatures.prop( 'checked' ) ) {
					signature_row.show();
				}

				show_signatures.click( function () {
					if ( $( this ).prop( 'checked' ) ) {
						signature_row.show();
					} else {
						signature_row.hide();
					}
				} );
			}
		},
		updateOCSFields: function () {
			let wrap_handler = jQuery( '.ocs-meta-box .edit_data' );
			let display_handler = jQuery( '.ocs-meta-box .display' );
			jQuery( 'input,select', wrap_handler ).on( 'keyup change', function () {
				let id = jQuery( this ).attr( 'id' );
				let value = jQuery( this ).val();
				if ( jQuery( this ).is( 'select' ) ) {
					value = jQuery( this ).find( 'option:selected' ).text();
				}
				jQuery( '.' + id + ' span', display_handler ).html( value );
			} )
		},

		imagePicker: function () {
			var frame,
				metaBox = $( '#image_picker' ),
				addImgLink = metaBox.find( '.upload-custom-img' ),
				delImgLink = metaBox.find( '.delete-custom-img' ),
				imgContainer = metaBox.find( '.custom-img-container' ),
				imgIdInput = metaBox.find( '.image-field-value' ),
				displayHandler = jQuery( '.ocs-meta-box .display' );

			addImgLink.on( 'click', function ( event ) {
				event.preventDefault();
				if ( frame ) {
					frame.open();
					return;
				}

				frame = wp.media( {
					library: {
						type: [ 'image' ]
					},
					multiple: false
				} );

				frame.on( 'select', function () {
					var attachment = frame.state().get( 'selection' ).first().toJSON();
					let image = '<img src="' + attachment.url + '" alt="" width="100" />';
					imgContainer.append( image );
					jQuery( '.inspire_invoices_owner_logo', displayHandler ).html( image );
					imgIdInput.val( attachment.url );
					addImgLink.addClass( 'hidden' );
					delImgLink.removeClass( 'hidden' );
				} );
				frame.open();
			} );

			delImgLink.on( 'click', function () {
				imgContainer.html( '' );
				addImgLink.removeClass( 'hidden' );
				delImgLink.addClass( 'hidden' );
				imgIdInput.val( '' );
				return false;
			} );
		},

		selectI18n: function () {
			return {
				placeholder: inspire_invoice_params.select2_placeholder,
				language: {
					inputTooShort: function ( args ) {
						var remainingChars = args.minimum - args.input.length;
						return inspire_invoice_params.select2_min_chars.replace( '%', remainingChars );
					},
					loadingMore: function () {
						return inspire_invoice_params.select2_loading_more;
					},
					noResults: function () {
						return inspire_invoice_params.select2_no_results;
					},
					searching: function () {
						return inspire_invoice_params.select2_searching;
					},
					errorLoading: function () {
						return inspire_invoice_params.select2_error_loading;
					},
				},
			};
		},

		userSelect2: function () {
			let _this = this;
			var roles_input = jQuery( '#inspire_invoices_roles' );
			if ( roles_input.length ) {
				roles_input.select2( {
					width: '400px',
					..._this.selectI18n(),
				} );
			}


			var _invoice_users_select = $( '#inspire_invoice_client_select' );
			if ( _invoice_users_select.length ) {
				options = {
					width: '200px',
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 300,
						type: 'POST',
						data: function ( params ) {
							return {
								action: 'woocommerce-invoice-user-select',
								name: params.term,
								security: inspire_invoice_params.ajax_nonce
							};
						},
						processResults: function ( data ) {
							return {
								results: data.items
							};
						},
						cache: true,
					},
					minimumInputLength: 3,
					..._this.selectI18n(),
					placeholder: inspire_invoice_params.search_customer
				};

				if ( $.fn.selectWoo ) {
					_invoice_users_select.selectWoo( options ).on( 'select2:select', function ( event ) {
						// This is how I got ahold of the data
						fi_session.search_user_data = event.params.data.details;
					} );
				} else if ( $.fn.select2 ) {
					_invoice_users_select.select2( options ).on( 'select2:select', function ( event ) {
						fi_session.search_user_data = event.params.data.details;
					} );
				}

			}
		},

		countrySelect2: function () {
			var _this = this;
			var $state_select = $( '#inspire_invoices_client_state' );
			if ( $state_select.length ) {
				$state_select.select2( {
					..._this.selectI18n(),
					width: '100%'
				} );
			}

			var country_select = $( '.country-select2' );
			if ( country_select.length ) {
				let $country_value = country_select.val();
				if ( $country_value ) {
					_this.setCountryState( $state_select, $country_value );
				}

				country_select.select2( {
					..._this.selectI18n(),
					width: '100%'
				} ).on( 'select2:select', function ( event ) {
					let country = event.params.data.id;
					_this.setCountryState( $state_select, country );
				} );
			}
		},

		setCountryState: function ( $state_select, country ) {

			if ( $state_select.length && country ) {
				let state_value = $state_select.val();
				let has_values = false;
				let countries_states = inspire_invoice_params.states
				let output = '<option value="">----</option>';
				let selected = '';
				for ( c in countries_states ) {
					let country_states = countries_states[ c ];
					if ( country === c ) {
						if ( country_states ) {
							has_values = true;
							for ( s in country_states ) {
								if ( state_value === s ) {
									selected = 'selected="selected"';
								} else {
									selected = '';
								}
								output += '<option ' + selected + ' value="' + s + '">' + country_states[ s ] + '</option>';
							}
						}
					}
				}

				$state_select.html( output );
				$state_select.trigger( "change" );
				let selected_value = $state_select.attr( 'data-value' );
				if ( has_values ) {
					$state_select.closest( '.form-field' ).show();
					$state_select.val( selected_value );
					$( '.inspire_invoices_client_state' ).show();
					if ( countries_states && selected_value ) {
						$( '.inspire_invoices_client_state span' ).html( countries_states[ country ][ selected_value ] );
					} else {
						$( '.inspire_invoices_client_state span' ).html( '---' );
					}
				} else {
					$state_select.closest( '.form-field' ).hide();
					$( '.inspire_invoices_client_state' ).hide();
				}
			}
		},

		generateDocument: function () {
			$( '.column-fi_actions .generate-document' ).click( function () {
				let $this = $( this );
				let tr = $this.closest( 'tr' );
				let td = $this.closest( 'td' );
				if ( $this.hasClass( 'disabled' ) ) {
					return false;
				} else {
					let generate_buttons = $( 'a.generate-document' );
					generate_buttons.addClass( 'disabled' );
					$this.parent().html( '<span style="float: none; margin: 4px 4px; height: 2em !important; width: 2em;" class="spinner is-active"></span>' );
					$.post( $this.attr( 'href' ), '', function ( result ) {
						if ( result.success ) {
							tr.find( '.fi_documents' ).html( result.data.html );
							td.html( result.data.email_url );
							generate_buttons.removeClass( 'disabled' );
						} else {
							alert( result.data.result );
						}
					} );
				}

				return false;
			} );

			$( '.postbox .generate-document' ).click( function () {
				let $this = $( this );
				let _parent = $this.closest( 'p' );
				_parent.html( '<span style="float: none; margin: 4px 0;" class="spinner is-active"></span>' );
				$.post( $this.attr( 'href' ), '', function ( result ) {
					if ( result.success ) {
						_parent.before( result.data.html );
						_parent.before( result.data.email_url );
						_parent.hide();
					} else {
						alert( result.data.html );
					}
				} );
				return false;
			} );
		},

		sendEmail: function () {
			$( document ).on( 'click', '.send_document', function () {
				var $this = $( this );

				function doAction() {
					$.post( $this.attr( 'href' ), '', function ( result ) {
							if ( result.success ) {
								alert( inspire_invoice_params.message_invoice_sent + result.data.email );
								$this.addClass( 'email-send' );
							} else {
								alert( result.data.msg );
							}
						}
					);
				}

				let email_status = $this.data( 'status' );
				if ( email_status === 'yes' ) {
					if ( confirm( inspire_invoice_params.email_was_sent ) === true ) {
						doAction();
					}
				} else if ( $( 'body' ).hasClass( 'invoice-changed' ) ) {
					if ( confirm( inspire_invoice_params.message_not_saved_changes ) === true ) {
						doAction();
					}
				} else {
					doAction();
				}

				return false;
			} );
		},

		getDocument: function () {
			$( 'body.post-type-inspire_invoice .get-document' ).click( function ( e ) {
				e.preventDefault();
				var $this = $( this );

				function doAction() {
					var url = $this.attr( 'href' );
					window.open( url, '_blank' );
				}

				if ( $( 'body' ).hasClass( 'invoice-changed' ) ) {
					if ( confirm( inspire_invoice_params.message_confirm ) === true ) {
						doAction();
					}
				} else {
					doAction();
				}
			} );
		},
	};

	FiwG.onChangeState();
	FiwG.editMetaBoxData();
	FiwG.userDataAndPayment();
	FiwG.updateOCSFields();
	FiwG.imagePicker();
	FiwG.userSelect2();
	FiwG.countrySelect2();
	FiwG.generateDocument();
	FiwG.sendEmail();
	FiwG.getDocument();
} )( jQuery );
