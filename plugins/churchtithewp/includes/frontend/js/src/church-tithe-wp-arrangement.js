import {StripeProvider} from 'react-stripe-elements';
import {Elements} from 'react-stripe-elements';
import {PaymentRequestButtonElement} from 'react-stripe-elements';
import {injectStripe} from 'react-stripe-elements';
var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Arrangement = class Church_Tithe_WP_Arrangement extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			arrangement_cancel_intention_count: 0,
			card_form_visible: false,
			sca_authentication_status: 'initial',
		};

	}

	componentDidUpdate() {

		if ( ! this.props.main_component.state.current_arrangement_payment_method && this.state.card_form_visible ) {
			this.setState( {
				card_form_visible: false,
			} );
		}
	}

	handle_back_to_plans_click( event ) {
		this.props.main_component.set_all_current_visual_states( {
			manage_payments: {
				arrangements: {}
			}
		}, false );
	}

	toggle_card_form_visibility( event ) {
		if ( this.state.card_form_visible ) {
			this.setState( {
				card_form_visible: false,
			} );
		} else {
			this.setState( {
				card_form_visible: true,
			} );
		}
	}

	get_arrangement_payment_method( arrangement_id ) {

		return new Promise( (resolve, reject) => {

			var this_component = this;

			var postData = new FormData();
			postData.append('action', 'church_tithe_wp_get_arrangement_payment_method_endpoint' );
			postData.append('church_tithe_wp_arrangement_id', arrangement_id);
			postData.append('church_tithe_wp_get_arrangement_payment_method_nonce', this.props.main_component.state.frontend_nonces.get_arrangement_payment_method_nonce);

			// Get the arrangements defined by the paramaters in the state
			fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_arrangement_payment_method_endpoint', {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				body: postData
			} ).then(
				( response ) => {
					if ( response.status !== 200 ) {

						this_component.props.main_component.setState( {
							current_arrangement_payment_method: null
						} );

						console.log('Looks like there was a problem. Status Code: ' + response.status);

						reject();
						return;
					}

					// Examine the text in the response
					response.json().then(
						function( data ) {
							if ( data.success ) {

								this_component.props.main_component.setState( {
									current_arrangement_payment_method: data.payment_method_data
								}, () => {
									resolve();
								} );

							} else {

								// Remove the user ID from the main state and set the state to be login
								this_component.props.main_component.setState( {
									current_arrangement_payment_method: null
								}, () => {
									reject();
								} );

							}
						}
					).catch(
						function( err ) {
							console.log('Fetch Error: ', err);
							reject();
							return;
						}
					);
				}
			).catch(
				function( err ) {
					console.log('Fetch Error :-S', err);
					reject();
					return;
				}
			);

		});

	}

	get_arrangement( arrangement_id ) {

		// If we are already fetching arrangements, don't fetch more again.
		if ( this.state.fetching_arrangement ) {
			return false;
		}

		return new Promise( (resolve, reject) => {

			this.setState( {
				fetching_arrangement: true,
			} );

			var postData = new FormData();
			postData.append('action', 'church_tithe_wp_get_arrangement' );
			postData.append('church_tithe_wp_arrangement_id', arrangement_id);
			postData.append('church_tithe_wp_get_arrangement_nonce', this.props.main_component.state.frontend_nonces.get_arrangement_nonce);

			// Get the arrangements defined by the paramaters in the state
			fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_arrangement', {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				body: postData
			} ).then(
				( response ) => {
					if ( response.status !== 200 ) {

						this.props.main_component.setState( {
							current_arrangement_info: null,
							current_arrangement_payment_method: null,
						}, () => {
							this.setState( {
								fetching_arrangement: false,
							} );
						} );

						console.log('Looks like there was a problem. Status Code: ' + response.status);

						reject();
						return;
					}

					// Examine the text in the response
					response.json().then(
						( data ) => {
							if ( data.success ) {

								this.props.main_component.setState( {
									user_logged_in: data.user_logged_in,
									current_arrangement_info: data.arrangement_info,
									current_arrangement_payment_method: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this.props.main_component.state.frontend_nonces
								}, () => {
									this.setState( {
										fetching_arrangement: false,
									}, () => {
										this.get_arrangement_payment_method( arrangement_id )
										resolve();
									} );
								} );

							} else {

								// Remove the user ID from the main state and set the state to be login
								this.props.main_component.setState( {
									user_logged_in: null,
									current_arrangement_info: null,
									current_arrangement_payment_method: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this.props.main_component.state.frontend_nonces
								}, () => {
									this.setState( {
										fetching_arrangement: false,
									} );
									reject();
								} );

							}
						}
					).catch(
						( err ) => {
							this.setState( {
								fetching_arrangement: false,
							} );
							console.log('Fetch Error: ', err);
							reject();
							return;
						}
					);
				}
			).catch(
				( err ) => {
					this.setState( {
						fetching_arrangement: false,
					} );
					console.log('Fetch Error :-S', err);
					reject();
					return;
				}
			);

		});

	}

	cancel_arrangement() {

		// Double check with the user to make sure they meant to cancel
		if ( 0 == this.state.arrangement_cancel_intention_count ) {

			this.setState( {
				arrangement_cancel_intention_count: 1
			} );

			return;
		}

		// Get the current arrangement
		var current_arrangement_info = this.props.main_component.state.current_arrangement_info;

		// Update the recurring status of that arrangement to "cancelling"
		current_arrangement_info.recurring_status = 'cancelling';

		// Set the state to be cancelling
		this.props.main_component.setState( {
			current_arrangement_info: current_arrangement_info
		} );

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_cancel_arrangement' );
		postData.append('church_tithe_wp_arrangement_id', this.props.main_component.state.current_arrangement_info.id );
		postData.append('church_tithe_wp_cancel_arrangement_nonce', this.props.main_component.state.frontend_nonces.cancel_arrangement_nonce );

		// Cancel the arrangement in question
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_cancel_arrangement', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			( response ) => {
				if ( response.status !== 200 ) {

					// We were unable to cancel the arrangement

					// Get the current arrangement
					current_arrangement_info = this.props.main_component.state.current_arrangement_info;

					// Update the recurring status of that arrangement to "cancelling"
					current_arrangement_info.recurring_status = 'failed_to_cancel';

					// Update the arrangement from the fetched data
					this.props.main_component.setState( {
						current_arrangement_info: data.current_arrangement_info
					} );

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					( data ) => {
						if ( data.success ) {

							// Reset the check user nonce
							this.props.main_component.setState( {
								user_logged_in: data.user_logged_in,
								frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this.props.main_component.state.frontend_nonces
							}, () => {
								// Set the current arrangement info based on the updated data
								this.props.main_component.setState( {
									current_arrangement_info: data.arrangement_info,
									arrangement_cancel_intention_count: 0
								} );

								// Reload the arrangements so that "cancelled" shows up in the column for this arrangement
								this.props.main_component.setState( {
									reload_arrangements: true
								} );

							} );

						} else {

							// We were unable to cancel the arrangement

							// Get the current arrangement
							current_arrangement_info = this.props.main_component.state.current_arrangement_info;

							// Update the recurring status of that arrangement to "cancelling"
							current_arrangement_info.recurring_status = 'failed_to_cancel';

							// Set the state to be cancelling
							this.props.main_component.setState( {
								current_arrangement_info: current_arrangement_info
							} );

							if ( 'not_logged_in' == data.error_code ) {

								// Remove the user ID from the main state and set the state to be login
								this.props.main_component.setState( {
									user_logged_in: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this.props.main_component.state.frontend_nonces
								} );
							}
						}
					}
				).catch(
					( err ) => {

						// We were unable to cancel the arrangement

						// Get the current arrangement
						current_arrangement_info = this.props.main_component.state.current_arrangement_info;

						// Update the recurring status of that arrangement to "cancelling"
						current_arrangement_info.recurring_status = 'failed_to_cancel';

						// Set the state to be cancelling
						this.props.main_component.setState( {
							current_arrangement_info: current_arrangement_info
						} );

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			( err ) => {

				// We were unable to cancel the arrangement

				// Get the current arrangement
				current_arrangement_info = this.props.main_component.state.current_arrangement_info;

				// Update the recurring status of that arrangement to "cancelling"
				current_arrangement_info.recurring_status = 'failed_to_cancel';

				// Set the state to be cancelling
				this.props.main_component.setState( {
					current_arrangement_info: current_arrangement_info
				} );

				console.log('Fetch Error :-S', err);
			}
		);
	}

	render_authenticate_sca_button() {

		if ( this.state.card_form_visible ) {
			return '';
		}

		return(
			<StripeProvider apiKey={this.props.main_component.state.dynamic_settings.stripe_api_key}>
				<Elements>
					<ChurchTitheWPAuthenticatePaymentButton
						main_component={ this.props.main_component }
						Church_Tithe_WP_Arrangement={ this }
						get_arrangement={ this.get_arrangement.bind( this ) }
					/>
				</Elements>
			</StripeProvider>
		);
	}

	render_cancel_button() {

		// Don't show this if we are updating the card attached to the plan.
		if ( this.state.card_form_visible ) {
			return '';
		}

		var button_text;

		// If the webhook has not arrived, show "Unable to cancel, webhook failed" on the button.
		if ( ! this.props.main_component.state.current_arrangement_info.webhook_succeeded ) {
			return ( <button className="church-tithe-wp-arrangement-action-cancel">{ this.props.main_component.state.unique_settings.strings.arrangement_action_cant_cancel }</button> );
		}

		if ( 0 == this.state.arrangement_cancel_intention_count ) {
			button_text = this.props.main_component.state.unique_settings.strings.arrangement_action_cancel;
		}

		if ( 1 == this.state.arrangement_cancel_intention_count ) {
			button_text = this.props.main_component.state.unique_settings.strings.arrangement_action_cancel_double;
		}

		if ( 'failed_to_cancel' == this.props.main_component.state.current_arrangement_info.recurring_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<a className="church-tithe-wp-arrangement-action-cancel">{ this.props.main_component.state.unique_settings.strings.arrangement_failed_to_cancel }</a>
				</div>
			);
		}

		if ( 'cancelling' == this.props.main_component.state.current_arrangement_info.recurring_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<a className="church-tithe-wp-arrangement-action-cancel">{ this.props.main_component.state.unique_settings.strings.arrangement_cancelling }</a>
				</div>
			);
		}

		if ( 'cancelled' == this.props.main_component.state.current_arrangement_info.recurring_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<a className="church-tithe-wp-arrangement-action-cancel">{ this.props.main_component.state.unique_settings.strings.arrangement_cancelled }</a>
				</div>
			);
		} else {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<a className="church-tithe-wp-arrangement-action-cancel" onClick={ this.cancel_arrangement.bind( this ) }>{ button_text }</a>
				</div>
			);
		}
	}

	render_renewal_date() {

		if ( 'cancelled' == this.props.main_component.state.current_arrangement_info.recurring_status ) {
			return '';
		}

		return (
			<div className="church-tithe-wp-arrangement-renewal-date">
				<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-arrangement-renewal-date-title">{ this.props.main_component.state.unique_settings.strings.arrangement_renewal_title + ': ' }</span>
				<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-arrangement-renewal-date-value">{ church_tithe_wp_format_date( this.props.main_component.state.current_arrangement_info.renewal_date ) }</span>
			</div>
		);

	}

	format_amount() {

		var cents = this.props.main_component.state.current_arrangement_info.amount;
		var currency = this.props.main_component.state.current_arrangement_info.currency;
		var is_zero_decimal_currency = this.props.main_component.state.current_arrangement_info.is_zero_decimal_currency;
		var locale = this.props.main_component.state.unique_settings.locale;
		var string_after = this.props.main_component.state.current_arrangement_info.string_after + ' (' + currency.toUpperCase() + ')';

		return church_tithe_wp_format_money( cents, currency, is_zero_decimal_currency, locale, string_after );

	}

	render_payment_method() {

		if ( ! this.props.main_component.state.current_arrangement_payment_method ) {
			return <Church_Tithe_WP_Spinner color_mode="church-tithe-wp-spinner-dark" />;
		}

		if ( 'none' == this.props.main_component.state.current_arrangement_payment_method ) {
			return 'None found';
		}

		return(
			<React.Fragment>
				<div className="church-tithe-wp-payment-method">
					<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-arrangement-id-title">
						{ this.props.main_component.state.unique_settings.strings.arrangement_payment_method_title + ': ' }
					</span>
					<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-arrangement-id-value">
						<span className="church-tithe-wp-inline-card">
							{ <Church_Tithe_WP_Card_Icon brand={ this.props.main_component.state.current_arrangement_payment_method.card.brand } /> }
							<div className="church-tithe-wp-inline-card-number">{ '•••• ' + this.props.main_component.state.current_arrangement_payment_method.card.last4 }</div>
							<div className="church-tithe-wp-inline-update-button">
								<button className="church-tithe-wp-text-button" onClick={ this.toggle_card_form_visibility.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.update_payment_method_verb }</button>
							</div>
						</span>
					</span>
				</div>
			</React.Fragment>
		);
	}

	render_payment_method_update_form() {

		if ( this.state.card_form_visible ) {
			return(
				<StripeProvider apiKey={this.props.main_component.state.dynamic_settings.stripe_api_key}>
					<Elements>
						<ChurchTitheWPUpdateCardForm
							main_component={ this.props.main_component }
							Church_Tithe_WP_Arrangement={ this }
							get_arrangement={ this.get_arrangement.bind( this ) }
						/>
					</Elements>
				</StripeProvider>
			);
		}

	}

	render() {

		if ( ! this.props.main_component.state.current_arrangement_info ) {
			return ( <Church_Tithe_WP_Spinner /> );
		}

		return (
			<div className="church-tithe-wp-arrangement">
				<div className={ 'church-tithe-wp-back-button-container' }>
					<a onClick={ this.handle_back_to_plans_click.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.back_to_plans }</a>
				</div>
				<div className={ 'church-tithe-wp-receipt-title' }>{ this.props.main_component.state.unique_settings.strings.arrangement_details }</div>
				<div className="church-tithe-wp-arrangement-id">
					<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-arrangement-id-title">{ this.props.main_component.state.unique_settings.strings.arrangement_id_title + ': ' }</span>
					<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-arrangement-id-value">{ this.props.main_component.state.current_arrangement_info.id }</span>
				</div>
				<div className="church-tithe-wp-arrangement-status">
					<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-arrangement-plan-title">{ this.props.main_component.state.unique_settings.strings.arrangement_amount_title + ': ' }</span>
					<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-arrangement-plan-value">{ this.props.main_component.state.current_arrangement_info.recurring_status_visible }</span>
				</div>
				<div className="church-tithe-wp-arrangement-interval">
					<span className="church-tithe-wp-receipt-line-item-title church-tithe-wp-arrangement-plan-title">{ this.props.main_component.state.unique_settings.strings.arrangement_amount_title + ': ' }</span>
					<span className="church-tithe-wp-receipt-line-item-value church-tithe-wp-arrangement-plan-value">{ this.format_amount() }</span>
				</div>
				{ this.render_renewal_date() }
				{ this.render_payment_method() }
				<div className="church-tithe-wp-arrangement-actions">
					{ this.render_payment_method_update_form() }
					{ this.render_authenticate_sca_button() }
					{ this.render_cancel_button() }
				</div>
			</div>
		)
	}

}
export default Church_Tithe_WP_Arrangement;

class Church_Tithe_WP_Authenticate_Payment_Button extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			sca_authentication_status: 'initial',
		};

	}


	attempt_payment_intent_confirmation() {

		this.setState( {
			sca_authentication_status: 'authorizing',
		} );

		// Pass the PaymentIntent's client secret off to Stripe
		this.props.stripe.confirmPaymentIntent(
			this.props.main_component.state.current_arrangement_info.pending_invoice.payment_intent.client_secret,
			{
				payment_method: this.props.main_component.state.current_arrangement_info.pending_invoice.payment_intent.payment_method,
			}
		).then( ( payment_intent_result ) => {

			// If the Payment Intent was unable to be confirmed
			if ( payment_intent_result.error ) {

				console.log( payment_intent_result.error );

				this.setState( {
					sca_authentication_status: 'failed_to_authorize',
				} );

			} else {

				// Success with no 3DS
				if ('succeeded' === payment_intent_result.paymentIntent.status) {
					this.setState( {
						sca_authentication_status: 'success',
					}, () => {
						this.props.get_arrangement( this.props.main_component.state.current_arrangement_info.id );
					} );
				} else {

					// It didn't go through without 3DS so try it as a card payment so we can use Stripe simpified 3DS
					// We got a payment intent from Stripe, so process it now
					this.props.stripe.handleCardPayment(
						this.props.main_component.state.current_arrangement_info.pending_invoice.payment_intent.client_secret,
						{}
					).then( (payment_intent_result) => {
						// If the response from handleCardPayment was no good
						if (payment_intent_result.error) {

							// Display error.message in the UI.
							this.setState( {
								sca_authentication_status: 'failed_to_authorize',
							});

						} else {

							// The PaymentIntent was successful
							this.setState( {
									sca_authentication_status: 'success',
							}, () => {
								this.props.get_arrangement( this.props.main_component.state.current_arrangement_info.id );
							} );
						}
					});
				}
			}
		});
	}

	render() {

		// Don't show this if there is no payment intent to authorize
		if ( ! this.props.main_component.state.current_arrangement_info.pending_invoice ) {
			return '';
		}

		var button_text;

		if ( 'failed_to_authorize' == this.state.sca_authentication_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<div>{  this.props.main_component.state.unique_settings.strings.sca_auth_description }</div>
					<button className="church-tithe-wp-receipt-line-item-action church-tithe-wp-arrangement-action-authenticate" onClick={ this.attempt_payment_intent_confirmation.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.sca_auth_failed }</button>
				</div>
			);
		}

		if ( 'authorizing' == this.state.sca_authentication_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<div>{  this.props.main_component.state.unique_settings.strings.sca_auth_description }</div>
					<button className="church-tithe-wp-receipt-line-item-action church-tithe-wp-arrangement-action-authenticate">{ this.props.main_component.state.unique_settings.strings.sca_authing_verb }</button>
				</div>
			);
		}

		if ( 'initial' == this.state.sca_authentication_status ) {

			var invoice = this.props.main_component.state.current_arrangement_info.pending_invoice.invoice;
			var formatted_amount = church_tithe_wp_format_money( invoice.total, invoice.currency, this.props.main_component.state.current_arrangement_info.is_zero_decimal_currency, this.props.main_component.state.unique_settings.locale, '' );

			return(
				<React.Fragment>
					<div className="church-tithe-wp-receipt-action-button">
						<div>{  this.props.main_component.state.unique_settings.strings.sca_auth_description }</div>
						<button className="church-tithe-wp-receipt-line-item-action church-tithe-wp-arrangement-action-authenticate" onClick={ this.attempt_payment_intent_confirmation.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.sca_auth_verb + ' - ' + formatted_amount }</button>
					</div>
				</React.Fragment>
			);
		}

		if ( 'success' == this.state.sca_authentication_status ) {
			return(
				<div className="church-tithe-wp-receipt-action-button">
					<div>{ this.props.main_component.state.unique_settings.strings.sca_authed_verb }</div>
				</div>
			);
		}
	}
}

class Church_Tithe_WP_Update_Card_Form extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			status_of_payment_method_updating: 'initial',
			form_validation_attempted: false,
			stripe_elements_fields_complete: false,
			stripe_card_error_code: '',
		};

	}

	update_payment_method_for_arrangement() {

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_update_arrangement' );
		postData.append('church_tithe_wp_arrangement_id', this.props.main_component.state.current_arrangement_info.id );
		postData.append('church_tithe_wp_stripe_payment_method_id', this.state.stripe_payment_method.id);
		postData.append('church_tithe_wp_update_arrangement_nonce', this.props.main_component.state.frontend_nonces.update_arrangement_nonce );

		// Update the payment method to use for this arrangement's renewals.
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_update_arrangement', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			( response ) => {
				if ( response.status !== 200 ) {

					this.setState( {
						status_of_payment_method_updating: 'unable_to_update'
					} );

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					( data ) => {
						if ( data.success ) {

							this.setState( {
								status_of_payment_method_updating: 'success'
							}, () => {
								// Reload the card attached to the arrangement and update it visually.
								this.props.main_component.setState( {
									current_arrangement_payment_method: data.payment_method
								}, () => {
									setTimeout(() => {
										this.props.Church_Tithe_WP_Arrangement.setState( {
											card_form_visible: false,
											status_of_payment_method_updating: 'initial',
										} );
									}, 1000);
								} );
							} );

						} else {

							this.setState( {
								status_of_payment_method_updating: 'unable_to_update'
							} );

						}
					}
				).catch(
					( err ) => {

						this.setState( {
							status_of_payment_method_updating: 'unable_to_update'
						} );

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			( err ) => {

				this.setState( {
					status_of_payment_method_updating: 'unable_to_update'
				} );

				console.log('Fetch Error :-S', err);
			}
		);

	}

	handleCreditCardSubmit( event ){

		event.preventDefault();

		this.setState( {
			status_of_payment_method_updating: 'updating'
		});

		// Create a stripe payment_method using the submitted info
		this.create_stripe_payment_method().then( () => {

			// Validate the fields
			var allow_form_to_be_submitted = this.validate_form( true );

			// Prevent the form submission if a field didn't validate
			if ( ! allow_form_to_be_submitted ) {

				this.setState( {
					status_of_payment_method_updating: 'unable_to_update',
				}, () => {
					return false;
				} );

			} else {
				this.update_payment_method_for_arrangement();
			}

		} ).catch((err) => {

			this.setState( {
				stripe_card_error_code: err,
				status_of_payment_method_updating: 'unable_to_update',
			}, () => {
				console.log( err );
			});

			// Validate the fields
			var allow_form_to_be_submitted = this.validate_form( true );

			// Prevent the form submission if a field didn't validate
			return false;

		} );

	}

	validate_form( modify_state = true ) {

		var all_fields_validate = true;

		// Credit Card form specific fields
		if ( 'credit_card' == this.state.payment_mode ) {

			// Name field
			if ( ! this.state.name_validated ) {
				all_fields_validate = false;
			}

			// Email field
			if ( ! this.state.email_validated ) {
				all_fields_validate = false;
			}

			// Stripe all in one field
			if ( ! this.state.stripe_payment_method ) {
				all_fields_validate = false;
			}

		}

		if ( modify_state ) {

			this.setState( {
				form_validation_attempted: true
			} );

			if ( ! all_fields_validate ) {

				this.setState( {
					form_has_any_error: true,
				});
			} else {
				this.setState( {
					form_has_any_error: false,
				});
			}
		}

		return all_fields_validate;

	}

	create_stripe_payment_method() {

		return new Promise( (resolve, reject) => {

			var billing_details = {
				billing_details: {
					address: {
						postal_code: this.state.postal_code
					},
					email: this.state.email_value,
					name: this.state.name_value,
				},
			}

			// If no zip code was found
			if ( this.state.stripe_elements_fields_complete && ! billing_details.billing_details.address.postal_code ) {

				this.setState( {
					stripe_card_error_code: 'incomplete_zip'
				}, () => {
					reject('incomplete_zip');
					return;
				} );

			} else {

				// Set the validation of this field
				this.props.stripe.createPaymentMethod(
					'card',
					billing_details
				).then( (result) => {
					if (result.error) {

						// Show error in payment form
						this.setState( {
							stripe_payment_method: null,
							stripe_card_error_code: result.error.code
						}, () => {
							reject(result.error.code);
							return result.error;
						} );

					} else {

						this.setState( {
							stripe_payment_method: result.paymentMethod,
							stripe_card_error_code: 'success'
						}, () => {
							resolve( result.paymentMethod );
							return result.paymentMethod;
						} );

					}
				});

			}

		});

	};

	render_complete_payment_button_field() {

		var button_class;
		var button_text;

		if ( 'initial' == this.state.status_of_payment_method_updating ) {
			button_class = '';
			button_text = this.props.main_component.state.unique_settings.strings.update_payment_method_verb;
		}
		if ( 'updating' == this.state.status_of_payment_method_updating ) {
			button_class = ' church-tithe-wp-btn-attempting-payment';
			button_text = <Church_Tithe_WP_Spinner />;
		}
		if ( 'success' == this.state.status_of_payment_method_updating ) {
			button_class = ' church-tithe-wp-btn-success';
			button_text = <span><Church_Tithe_WP_Checkmark /></span>;
		}
		if ( 'unable_to_update' == this.state.status_of_payment_method_updating ) {
			button_class = ' church-tithe-wp-btn-error';
			button_text = this.props.main_component.state.unique_settings.strings.complete_payment_button_error_text;
		}

		return (
			<div>
				<button type="button" onClick={this.handleCreditCardSubmit.bind( this )} className={ 'church-tithe-wp-pay-button' + button_class }>{ button_text }</button>
			</div>
		)
	}

	set_validation_and_value_of_field( state_validation_variable, is_validated, state_value_variable = null, state_value = null ) {

		return new Promise( (resolve, reject) => {

			if ( null == state_value_variable ) {
				this.setState( {
					[state_validation_variable]: is_validated,
				},  () => {
					resolve( this.state );
				} );
			} else {
				this.setState( {
					[state_validation_variable]: is_validated,
					[state_value_variable]: state_value,
				}, () => {
					resolve( this.state );
				} );
			}

		} );
	}

	render() {
		return(
			<React.Fragment>
				<div className="church-tithe-wp-payment-field-container">
					<div className="church-tithe-wp-payment-field">
						<Church_Tithe_WP_Input_Field
							main_component={ this.props.main_component }
							state_validation_variable_name={ 'name_validated' }
							state_value_variable_name={ 'name_value' }
							set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
							form_validation_attempted={ this.state.form_validation_attempted }
							is_validated={ this.state.name_validated }
							validate_form={ this.validate_form.bind( this ) }
							instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.name }
							initial_value={ this.props.main_component.state.unique_settings.strings.current_user_name }

							type="text"
							class_name={ 'church-tithe-wp-name' }
							placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.name.placeholder_text }
							name="tithe-amount"
							disabled={ false }
							editing_key={ 'strings/input_field_instructions/name/[current_key_here]/instruction_message' }
						/>
					</div>
				</div>
				<div className="church-tithe-wp-payment-field-container">
					<div className="church-tithe-wp-payment-field">
						<Church_Tithe_WP_Stripe_All_In_One_Field
							main_component={ this.props.main_component }
							card_form={ this }
							form_validation_attempted={ this.state.form_validation_attempted }
							validate_form={ null }
							stripe_card_error_code={ this.state.stripe_card_error_code }
							instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.stripe_all_in_one }
							stripe={ this.props.stripe }
							disabled={ false }
							mobile_mode={ window.innerWidth > 600 ? false : true}
							zip_code_placeholder={ this.props.main_component.state.unique_settings.strings.zip_code_placeholder }
							create_stripe_source={ this.create_stripe_payment_method }
							editing_key={ 'strings/input_field_instructions/stripe_all_in_one/[current_key_here]/instruction_message' }
						/>
					</div>
				</div>
				<div className="church-tithe-wp-payment-field-container">
					<div className="church-tithe-wp-payment-field">
						{ this.render_complete_payment_button_field() }
					</div>
				</div>
			</React.Fragment>
		);
	}
}
const ChurchTitheWPUpdateCardForm = injectStripe(Church_Tithe_WP_Update_Card_Form);
const ChurchTitheWPAuthenticatePaymentButton = injectStripe(Church_Tithe_WP_Authenticate_Payment_Button);
