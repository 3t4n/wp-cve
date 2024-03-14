var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Payment_Confirmation = class Church_Tithe_WP_Payment_Confirmation extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			note_with_tithe_value: null,
			note_with_tithe_validated: false,

			form_validation_attempted: false,
			time_since_last_keypress: 0,
			after_payment_actions_completed: false,
			print_html: null,
			sending_email_receipt: false,
			email_receipt_success: null
		};

		this.note_with_tithe_keypress_delay;
		this.render_refunded_output = this.render_refunded_output.bind( this );
		this.email_transaction_receipt = this.email_transaction_receipt.bind( this );
	}

	componentDidMount() {
		// Set up our print HTML upon mount
		if ( this.props.main_component.state.dom_node ) {
			this.setState( {
				print_html: this.props.main_component.state.dom_node.outerHTML
			} );
		}

		// If we should fire the actions that take place after a payment upon component mounting...
		if ( this.props.do_after_payment_actions ) {
			this.do_after_payment_actions();
		}
	}

	componentDidUpdate() {

		// Update our print HTML upon mount
		if ( this.props.main_component.state.dom_node ) {
			if ( this.state.print_html != this.props.main_component.state.dom_node.outerHTML ) {
				this.setState( {
					print_html: this.props.main_component.state.dom_node.outerHTML
				} );
			}
		}

	}

	do_after_payment_actions() {
		this.email_transaction_receipt( true, false );
	}

	get_transaction_visual_amount() {

		var cents = this.props.main_component.state.current_transaction_info.transaction_charged_amount;
		var currency = this.props.main_component.state.current_transaction_info.transaction_charged_currency;
		var is_zero_decimal_currency = this.props.main_component.state.current_transaction_info.transaction_currency_is_zero_decimal;
		var locale = this.props.main_component.state.unique_settings.locale;
		var string_after = ' (' + currency.toUpperCase() + ')';

		return church_tithe_wp_format_money( cents, currency, is_zero_decimal_currency, locale, string_after );

	}

	get_arrangement_visual_amount() {

		var cents = this.props.main_component.state.current_transaction_info.arrangement_info.amount;
		var currency = this.props.main_component.state.current_transaction_info.arrangement_info.currency;
		var is_zero_decimal_currency = this.props.main_component.state.current_transaction_info.arrangement_info.is_zero_decimal_currency;
		var locale = this.props.main_component.state.unique_settings.locale;
		var string_after = this.props.main_component.state.current_transaction_info.arrangement_info.string_after + ' (' + currency.toUpperCase() + ')';

		return church_tithe_wp_format_money( cents, currency, is_zero_decimal_currency, locale, string_after );

	}

	maybe_render_the_period_this_transaction_covers() {

		var start_date = this.props.main_component.state.current_transaction_info.transaction_period_start_date;
		var end_date = this.props.main_component.state.current_transaction_info.transaction_period_end_date;
		var period_string;

		if ( ! start_date || ! end_date ) {
			return '';
		}

		if ( '0000-00-00 00:00:00' == start_date || '0000-00-00 00:00:00' == end_date ) {
			return '';
		}

		period_string = church_tithe_wp_format_date( start_date ) + ' - ' + church_tithe_wp_format_date( end_date );

		return(
			<div>
				<span className="church-tithe-wp-receipt-line-item-title">{ this.props.main_component.state.unique_settings.strings.transaction_period + ': ' }</span>
				<span className="church-tithe-wp-receipt-line-item-value">{ period_string }</span>
			</div>
		);

	}

	validate_form( modify_state ) {

		var all_fields_validate = true;

		// Note with tithe field
		if ( ! this.state.note_with_tithe_validated ) {
			all_fields_validate = false;
		}

		return all_fields_validate;

	}

	email_transaction_receipt( notify_admin_too = false, send_regardless_of_initial_emails_sent = false ) {

		this.setState( {
			sending_email_receipt: true,
			email_receipt_success: null
		} );

		// Do any after-payment actions that need to take place via ajax
		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_email_transaction_receipt' );
		postData.append('church_tithe_wp_transaction_id', this.props.main_component.state.current_transaction_info.transaction_id);
		postData.append('church_tithe_wp_session_id', this.props.main_component.state.session_id);
		postData.append('church_tithe_wp_user_id', this.props.main_component.state.user_id);
		postData.append('church_tithe_wp_notify_admin_too', notify_admin_too);
		postData.append('church_tithe_wp_send_regardless_of_initial_emails_sent', send_regardless_of_initial_emails_sent);
		postData.append('church_tithe_wp_email_transaction_receipt_nonce', this.props.main_component.state.frontend_nonces.church_tithe_wp_email_transaction_receipt_nonce);

		var this_component = this;

		// Here we will handle anything that needs to be done after the payment was completed.
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_email_transaction_receipt', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						sending_email_receipt: false,
						email_receipt_success: false
					} );

					console.log('Looks like there was a problem. Status Code: ' + response.status);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							this_component.setState( {
								sending_email_receipt: false,
								email_receipt_success: true
							} );

						} else {

							console.log( data );

							this_component.setState( {
								sending_email_receipt: false,
								email_receipt_success: false
							} );

						}
					}
				).catch( () => {
					this_component.setState( {
						sending_email_receipt: false,
						email_receipt_success: false
					} );

					console.log( response );
				} );
			}
		).catch(
			function( err ) {

				this_component.setState( {
					sending_email_receipt: false,
					email_receipt_success: false
				} );

				console.log('Fetch Error :-S', err);
			}
		);
	}

	set_validation_and_value_of_field( state_validation_variable, is_validated, state_value_variable = null, state_value = null ) {

		if ( 'note_with_tithe_value' != state_value_variable ) {

			if ( null == state_value_variable ) {
				this.setState( {
					[state_validation_variable]: is_validated,
				} );
			} else {
				this.setState( {
					[state_validation_variable]: is_validated,
					[state_value_variable]: state_value,
				} );
			}

		} else {

			// If we are saving the note with tithe
			var old_note_with_tithe = this.state.note_with_tithe;
			var this_component = this;

			// We won't set the validation to true until the ajax response comes back
			this.setState( {
				note_with_tithe_validated: 'typing',
				note_with_tithe_value: state_value,
			} );

			// If nothing has changed since the state was last set
			if ( state_value == old_note_with_tithe ) {

				// Do nothing
				return false;

			} else {

				// Set up a delay which waits to save the tithe until .5 seconds after they stop typing.
				if( this.note_with_tithe_keypress_delay ) {
					// Clear the keypress delay if the user just typed
					clearTimeout( this.note_with_tithe_keypress_delay );
					this.note_with_tithe_keypress_delay = null;
				}

				// (Re)-Set up the save_note_with_tithe to fire in 500ms
				this.note_with_tithe_keypress_delay = setTimeout( function() {
					clearTimeout( this.note_with_tithe_keypress_delay );
					this_component.save_note_with_tithe( state_value );
				}, 500);

			}
		}
	}

	save_note_with_tithe( note_with_tithe ) {

		this.setState( {
			note_with_tithe_validated: 'saving',
		} );

		// We'll auto save the entered tithe note into the database's transaction field via ajax every time the person stops typing for 1 second.
		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_save_note_with_tithe');
		postData.append('church_tithe_wp_transaction_id', this.props.main_component.state.current_transaction_info.transaction_id);
		postData.append('church_tithe_wp_note_with_tithe', this.state.note_with_tithe_value);
		postData.append('church_tithe_wp_session_id', this.props.main_component.state.session_id);
		postData.append('church_tithe_wp_user_id', this.props.main_component.state.user_id);
		postData.append('church_tithe_wp_note_with_tithe_nonce', this.props.main_component.state.frontend_nonces.note_with_tithe_nonce);

		var this_component = this;

		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_save_note_with_tithe', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log('Looks like there was a problem. Status Code: ' +
					response.status);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// The note was successfully saved. Adjust the validation to true
							this_component.setState( {
								note_with_tithe_validated: true,
							} );

						} else {
							console.log( data );

							// The note was not successfully saved. Adjust the validation to false
							this_component.setState( {
								note_with_tithe_validated: false,
							} );
						}
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
			}
		);
	}

	render_email_button() {

		var email_message = '';

		// If the receipt was just successfully sent, or just failed to send
		if ( this.state.email_receipt_success ) {
			email_message = <div className="church-tithe-wp-email-receipt-message">{ this.props.main_component.state.unique_settings.strings.email_receipt_success }</div>
		}

		if ( null !== this.state.email_receipt_success && ! this.state.email_receipt_success ) {
			email_message = <div className="church-tithe-wp-email-receipt-message">{ this.props.main_component.state.unique_settings.strings.email_receipt_failed }</div>
		}

		if ( this.state.sending_email_receipt ) {
			return (
				<div className="church-tithe-wp-email-receipt">
					{ this.props.main_component.state.unique_settings.strings.email_receipt_sending }
					<button className={ 'church-tithe-wp-pay-button' }>{ this.props.main_component.state.unique_settings.strings.email_receipt_sending }</button>
				</div>
			);
		}

		if ( ! this.state.sending_email_receipt ) {
			return (
				<div className="church-tithe-wp-email-receipt">
					{ email_message }
					<button className={ 'church-tithe-wp-pay-button' } onClick={ this.email_transaction_receipt.bind( this, true, true ) }>{ this.props.main_component.state.unique_settings.strings.email_receipt }</button>
				</div>
			);
		}

	}

	render_print_button() {

		if ( this.state.print_html ) {

			if( typeof window.print == 'function' ) {
				return (
					<div className="church-tithe-wp-print-receipt">
					<button className={ 'church-tithe-wp-pay-button' } onClick={ church_tithe_wp_print_div.bind( null, this.state.print_html, this.props.main_component.state.unique_settings.strings.receipt_title, 'church_tithe_wp_default_skin-css' ) }>{ this.props.main_component.state.unique_settings.strings.print_receipt }</button>
					</div>
				)
			}

		}
	}

	render_manage_payments_button() {

		if ( ! this.props.show_manage_payments ) {
			return( '' );
		}

		return(
			<button type="button" className={ 'church-tithe-wp-manage-payments-button church-tithe-wp-input-instruction church-tithe-wp-text-button' } onClick={ this.props.main_component.set_all_current_visual_states.bind( null, {
				manage_payments: {}
			}, false ) }>{ this.props.main_component.state.unique_settings.strings.manage_payments_button_text }</button>
		);

	}

	render_refunded_output() {

		// If this is a refund transaction
		if ( 'refund' == this.props.main_component.state.current_transaction_info.transaction_type ) {
			return( 'This is a refund for transaction' + ' ' + this.props.main_component.state.current_transaction_info.refund_id );
		}

		// If this is an initial transaction that has been refunded
		if ( this.props.main_component.state.current_transaction_info.refund_id ) {
			if (
				'initial' == this.props.main_component.state.current_transaction_info.transaction_type ||
				'renewal' == this.props.main_component.state.current_transaction_info.transaction_type
			) {
				return( 'This transaction has been refunded. See transaction ' + this.props.main_component.state.current_transaction_info.refund_id );
			}
		}

		return( '' );

	}

	render_things_before_receipt() {

		// Don't show extra things on refund receipts, like note with tithe
		if ( 'refund' == this.props.main_component.state.current_transaction_info.transaction_type ) {
			return '';
		}

		return (
			<React.Fragment>
				<div className="church-tithe-wp-confirmation-message">
				{ this.props.main_component.state.unique_settings.strings.thank_you_message }
				</div>
				<div className="church-tithe-wp-confirmation-note">
				{
					<Church_Tithe_WP_TextArea_Field
						state_validation_variable_name={ 'note_with_tithe_validated' }
						state_value_variable_name={ 'note_with_tithe_value' }
						set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
						form_validation_attempted={ this.state.form_validation_attempted }
						is_validated={ this.state.note_with_tithe_validated }
						validate_form={ this.validate_form.bind( this ) }
						instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.note_with_tithe }
						value={ this.props.main_component.state.current_transaction_info ? this.props.main_component.state.current_transaction_info.transaction_note_with_tithe : '' }

						type="text"
						class_name={ 'church-tithe-wp-note-with-tithe' }
						placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.note_with_tithe.placeholder_text }
						name="tithe-amount"
					/>
				}
				</div>
			</React.Fragment>
		);


	}

	maybe_render_plan_details() {

		if ( 'off' !== this.props.main_component.state.current_transaction_info.arrangement_info.recurring_status ) {
			return(
				<React.Fragment>
					<div>
						<span className="church-tithe-wp-receipt-line-item-title">{ this.props.main_component.state.unique_settings.strings.arrangement_id_title + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value">{ this.props.main_component.state.current_transaction_info.arrangement_info.id }</span>
					</div>
					<div>
						<span className="church-tithe-wp-receipt-line-item-title">{ this.props.main_component.state.unique_settings.strings.arrangement_amount_title + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value">{ this.get_arrangement_visual_amount() }</span>
					</div>
					{ this.maybe_render_the_period_this_transaction_covers() }
				</React.Fragment>
			)
		}

	}

	render() {

		if ( ! this.props.main_component.state.current_transaction_info ) {
			return ( <Church_Tithe_WP_Spinner /> );
		}

		return (
			<div className="church-tithe-wp-payment-confirmation">
				{ this.render_things_before_receipt() }
				<div className="church-tithe-wp-receipt">
					<div className="church-tithe-wp-receipt-title">
					{ this.props.main_component.state.unique_settings.strings.receipt_title }
					</div>
					<div className="church-tithe-wp-receipt-field-space-below">
						{ this.props.main_component.state.current_transaction_info.email }
					</div>

					<div className="church-tithe-wp-receipt-field-space-below">
						{ this.render_refunded_output() }
					</div>

					<div className="church-tithe-wp-receipt-payee">
						<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-payee-title">{ ( 'refund' == this.props.main_component.state.current_transaction_info.transaction_type ? this.props.main_component.state.unique_settings.strings.refund_payer : this.props.main_component.state.unique_settings.strings.receipt_payee ) + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-payee-value">{ this.props.main_component.state.current_transaction_info.payee_name }</span>
					</div>
					<div className="church-tithe-wp-receipt-transaction-id">
						<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-id-title">{ this.props.main_component.state.unique_settings.strings.receipt_transaction_id + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-id-value">{ this.props.main_component.state.current_transaction_info.transaction_id }</span>
					</div>
					<div className="church-tithe-wp-receipt-transaction-date">
						<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-date-title">{ this.props.main_component.state.unique_settings.strings.receipt_date + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-date-value">{ church_tithe_wp_format_date_and_time( this.props.main_component.state.current_transaction_info.transaction_date_created ) }</span>
					</div>
					<div className="church-tithe-wp-receipt-amount">
						<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-amount-title">{ this.props.main_component.state.unique_settings.strings.receipt_transaction_amount + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-amount-value">{ this.get_transaction_visual_amount() }</span>
					</div>
					<div className="church-tithe-wp-receipt-statement-descriptor">
						<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-receipt-transaction-amount-title">{ this.props.main_component.state.unique_settings.strings.receipt_statement_descriptor + ': ' }</span>
						<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-receipt-transaction-amount-value">{ this.props.main_component.state.current_transaction_info.statement_descriptor }</span>
					</div>
					{ this.maybe_render_plan_details() }
				</div>
				{ this.render_email_button() }
				{ this.render_print_button() }
				{ this.render_manage_payments_button() }
			</div>
		)
	}

}
export default Church_Tithe_WP_Payment_Confirmation;

// This function takes html, puts it on a single page, and then sets that page to print.
function church_tithe_wp_print_div( html_to_print, page_title_to_use, css_stylesheet_id ) {

	// Copy the <head> tag
	var head_tag = document.querySelector( 'head' );

	var mywindow = window.open( '', page_title_to_use, 'height=6000,width=8000' );
	mywindow.document.write( head_tag.outerHTML );
	mywindow.document.write( '<body class="church-tithe-wp-print-page">' );
	mywindow.document.write( html_to_print );
	mywindow.document.write( '</body></html>' );

	// Wait for 1 second before attempting to print it so it can write everything and load the CSS
	setTimeout( function() {

		mywindow.focus()
		mywindow.print();

	}, 2000 );

	return true;
}
