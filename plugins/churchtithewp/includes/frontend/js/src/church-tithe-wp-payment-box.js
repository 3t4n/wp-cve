import {StripeProvider} from 'react-stripe-elements';
import {Elements} from 'react-stripe-elements';
import {PaymentRequestButtonElement} from 'react-stripe-elements';
import {injectStripe} from 'react-stripe-elements';

var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Payment_Box = class Church_Tithe_WP_Payment_Box extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			map_of_visual_states: {
				payment: {
					variable: {}
				}
			},
			current_visual_state: 'form', //loading, form, confirmation, success
			has_been_in_view: false,
			stripe: null,
			current_title_string: this.props.main_component.state.unique_settings.strings.form_title,
		};

	}

	componentDidMount() {

		church_tithe_wp_set_visual_state_of_component( {
				component: this,
				default_visual_states: {
					parent_in_view: 'form',
					parent_not_in_view: 'none'
				},
				name_of_visual_state_variable: 'current_visual_state'
		} );
	}

	componentDidUpdate() {

		church_tithe_wp_set_visual_state_of_component( {
				component: this,
				default_visual_states: {
					parent_in_view: 'form',
					parent_not_in_view: 'none'
				},
				name_of_visual_state_variable: 'current_visual_state'
		} );

	}

	get_current_title() {

		// If the current visual state is loading
		if( 'loading' == this.state.current_visual_state ) {

			return <h1 className="church-tithe-wp-header-title">{ this.props.main_component.state.unique_settings.strings.loading }</h1>

		}

		// If the current visual state is form
		if( 'form' == this.state.current_visual_state ) {

			return <h1 className="church-tithe-wp-header-title">{ this.props.main_component.state.unique_settings.strings.form_title }</h1>

		}

		// If the current visual state is confirmation
		if( 'confirmation' == this.state.current_visual_state ) {

			return <h1 className="church-tithe-wp-header-title">{ this.props.main_component.state.unique_settings.strings.payment_confirmation_title }</h1>

		}
	}

	get_current_subtitle() {

		// If the current visual state is loading
		if( 'loading' == this.state.current_visual_state ) {

			return '';

		}

		// If the current visual state is form
		if( 'form' == this.state.current_visual_state && this.props.main_component.state.unique_settings.strings.form_subtitle ) {

			return <h2 className={ 'church-tithe-wp-header-subtitle' }>{ this.props.main_component.state.unique_settings.strings.form_subtitle }</h2>

		}

		// If the current visual state is confirmation
		if( 'confirmation' == this.state.current_visual_state ) {

			return '';

		}
	}

	maybe_render_close_button() {

		if ( ! this.props.show_close_button ) {
			return '';
		}

		return (
			<div className="church-tithe-wp-close-btn" aria-label="Close" onClick={ this.props.main_component.handle_visual_state_change_click_event.bind( this, {}, {} ) }><img src={ this.props.main_component.state.dynamic_settings.close_button_url } /></div>
		);
	}

	render() {

		if ( 'none' == this.state.current_visual_state ) {
			return (
				''
			);
		} else {
			return (
				<StripeProvider apiKey={this.props.main_component.state.dynamic_settings.stripe_api_key}>
					<div className="church-tithe-wp-component-box">

						<header className="church-tithe-wp-header" role="banner">
							{ this.get_current_title() }
							{ this.get_current_subtitle() }
							{ this.maybe_render_close_button() }
						</header>

						{ (() => {
							if ( 'loading' == this.state.current_visual_state ) {
								return(
									<div className={ 'church-tithe-wp-payment-box-view church-tithe-wp-payment-loading-view' }>
										<Church_Tithe_WP_Spinner />
									</div>
								);
							}
						})() }

						{ ( () => {
							if ( 'form' == this.state.current_visual_state ) {
								return(
									<div className="church-tithe-wp-payment-form-container">
										<div className={ 'church-tithe-wp-payment-box-view' }>
											<Elements>
												<CardForm
													main_component={ this.props.main_component }
												/>
											</Elements>
										</div>
									</div>
								);
							}
						} )() }

						{ ( () => {
							if ( 'confirmation' == this.state.current_visual_state ) {

								if ( ! this.props.main_component.state.current_transaction_info ) {
									return(
										<div className={ 'church-tithe-wp-payment-box-view church-tithe-wp-payment-confirmation-view' }>
											<div>
												No transaction found.
											</div>
											<button
												onClick={ this.props.main_component.set_all_current_visual_states.bind( null, {
													manage_payments: {}
												}, false ) }
											>Manage your payments</button>
										</div>
									)
								}

								return(
									<div className={ 'church-tithe-wp-payment-box-view church-tithe-wp-payment-confirmation-view' }>
										<Church_Tithe_WP_Payment_Confirmation
											main_component={ this.props.main_component }
											do_after_payment_actions={ true }
											show_manage_payments={ true }
										/>
									</div>
								);
							}
						} )() }

					</div>
				</StripeProvider>
			);
		}
	}
}
export default Church_Tithe_WP_Payment_Box;

class Church_Tithe_WP_Card_Form extends React.Component {

	constructor( props ){
		super(props);

		this.state= {
			map_of_visual_states: {
				payment: {
					form: {
						variable: {}
					}
				}
			},
			stripe_is_set: false,
			canDoPaymentRequest: false,
			paymentRequest : null,
			default_payment_mode: null,
			payment_mode: null, //credit_card or payment_request
			payment_method_name: null, // basic-card, apple-pay, payment-request
			payment_request_button_name: null,
			form_has_any_error: false,
			form_validation_attempted: false,
			current_payment_state: 'initial',

			// Currency states
			currency_search_visible: false,
			user_typed_currency: this.props.main_component.state.unique_settings.currency_code,
			verified_currency: this.props.main_component.state.unique_settings.currency_code,
			verified_currency_symbol: this.props.main_component.state.unique_settings.currency_symbol,
			verified_currency_type: this.props.main_component.state.unique_settings.currency_type,

			stripe_error_message: null,
			stripe_payment_method: null,

			tithe_amount: this.props.main_component.state.unique_settings.default_amount,

			privacy_policy_validated: false,

			email_value: null,
			email_validated: false,

			name_value: null,
			name_validated: false,

			recurring_value: null,
			recurring_validated: false,

			input_fields_tithe_amount_current_instruction: 'initial',
			input_fields_stripe_all_in_one_current_instruction: 'initial',
		};

		this.currency_text_input = React.createRef();
		this.check_for_payment_request_availability = this.check_for_payment_request_availability.bind( this );
		this.validate_tithe_amount = this.validate_tithe_amount.bind( this );
		this.handleCreditCardSubmit = this.handleCreditCardSubmit.bind( this );
		this.set_payment_mode = this.set_payment_mode.bind( this );
		this.get_currency_flag_class = this.get_currency_flag_class.bind( this );
		this.create_stripe_payment_method = this.create_stripe_payment_method.bind( this );
	}

	componentDidMount() {

		// Upon mount, clear the current transaction and arrangement.
		// This handles back-clicks from the manage payments state, and prevents double "after-payment completed" actions.
		this.props.main_component.setState( {
			current_transaction_info: null,
			current_arrangement_info: null,
		} );

		//this.check_for_payment_request_availability();
	}

	componentDidUpdate() {

		church_tithe_wp_set_visual_state_of_component( {
				component: this,
				default_visual_states: {
					parent_in_view: this.state.default_payment_mode,
					parent_not_in_view: 'none'
				},
				name_of_visual_state_variable: 'payment_mode'
		} );

		this.check_for_payment_request_availability();

		// Pass the email value up to the main component.
		if ( this.props.main_component.state.form_email_value !== this.state.email_value ) {
			this.props.main_component.setState( {
				form_email_value: this.state.email_value
			} );
		}

	}

	country_supports_payment_request_button() {
		var country_code = this.props.main_component.state.dynamic_settings.stripe_account_country_code;

		// Has to be a supported country for Stripe: https://stripe.com/global
		var payment_request_countries = ['AT', 'AU', 'BE', 'BR', 'CA', 'CH', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HK', 'IE', 'IN', 'IT', 'JP', 'LT', 'LU', 'LV', 'MX', 'MY', 'NL', 'NO', 'NX', 'PH', 'PL', 'PT', 'RO', 'SE', 'SG', 'SK', 'US'];

		if ( payment_request_countries.indexOf( country_code ) ) {
			return true;
		} else {
			return false;
		}
	}

	check_for_payment_request_availability() {

		// If stripe has now loaded and we haven't checked for Payment Request capabilities yet (Apple Pay, Google Pay, etc)
		if ( this.props.stripe && ! this.state.stripe_is_set ) {

			// Set up a placeholder payment request just to see if we can do it
			var paymentRequest = this.props.stripe.paymentRequest({
				country: this.props.main_component.state.dynamic_settings.stripe_account_country_code,
				currency: this.state.verified_currency ? this.state.verified_currency.toLowerCase() : 'USD',
				total: {
					label: this.props.main_component.state.unique_settings.strings.payment_request_label,
					amount: this.state.tithe_amount,
				},
				requestPayerEmail: true,
			});

			this.setState({
				stripe_is_set: true,
			});

			// If we can make a Payment Request (Apple Pay, Google Pay, etc)
			paymentRequest.canMakePayment().then(result => {

				// If we can do a PaymentRequest in this browser
				if ( !!result && this.country_supports_payment_request_button() ) {

					this.setState({
						canDoPaymentRequest: !!result,
						paymentRequest: paymentRequest,
						default_payment_mode: 'payment_request'
					}, () => {

						// If no payment mode has been set in the URL, set the default one to Payment Request.
						// But if a payment mode was set through the URL, we want to keep it.
						if ( ! this.state.payment_mode	) {
							// We are not using this.set_payment_method here because we don't want to affect the URL, since this is a fresh page load.
							this.setState( {
								payment_mode: 'payment_request',
							} );
						}

						// If a payment mode was set in the URL, but it isn't one of the available ones, set the URL and payment mode to the default here.
						else if (
							'payment_request' !== this.state.payment_mode &&
							'credit_card' !== this.state.payment_mode
						) {
							this.set_payment_mode( 'payment_request' );
						}

						else if ( 'credit_card' === this.state.payment_mode ) {
							this.set_payment_mode( 'credit_card' );
						}

					});

					// This fires once a paymentRequest has been completed by the user.
					paymentRequest.on('paymentmethod', (result) => {

						const { source, error, complete } = result;

						if ( result.error ) {
							// Handle error...
							stripe_error_message: result.error.message
						}

						this.setState( {
							stripe_payment_method: result.paymentMethod,
							email_validated: true,
							email_value: result.payerEmail,
							name_value: result.paymentMethod.billing_details.name,
							name_validated: true,
							payment_method_name: 'apple-pay' != result.methodName ? 'payment-request' : result.methodName,
							payment_request_button_name: 'apple-pay' != result.methodName ? 'payment-request' : result.methodName,
						} );

						// Send the token to the server for processing
						this.do_ajax_stripe_transaction();

						// Close the browser modal
						complete('success');
					});

				} else {

					// Payment request is not available in this browser.
					this.setState({
						canDoPaymentRequest: !!result,
						default_payment_mode: 'credit_card',
						payment_method_name: 'basic-card'
					}, () => {

						// If no payment mode has been set in the URL, set the default one to Credit Card
						// But if a payment mode was set through the URL, we want to keep it.
						if ( ! this.state.payment_mode	) {
							// We are not using this.set_payment_method here because we don't want to affect the URL, since this is a fresh page load.
							this.setState( {
								payment_mode: 'credit_card',
							} );
						} else {
							this.set_payment_mode( 'credit_card' );
						}

					} );

				}

			});

		}

	}

	// This is for displaying the amount in an input field.
	get_visual_amount_for_input_field() {

		if ( 0 === this.state.tithe_amount ) {
			//Do nothing...
		} else if( ! this.state.tithe_amount ) {
			return '';
		}

		// If this is not a zero-decimal currency, divide the amount by 100
		if ( 'zero_decimal' !== this.state.verified_currency_type ) {
			var visual_tithe_amount = this.state.tithe_amount / 100;
		} else {
			var visual_tithe_amount = this.state.tithe_amount;
		}

		return visual_tithe_amount;
	}

	// This is for displaying the amount, but not for inside an input field.
	get_visual_amount() {

		if ( 0 === this.state.tithe_amount ) {
			//Do nothing...
		} else if( ! this.state.tithe_amount ) {
			return '';
		}

		// If this is not a zero-decimal currency, divide the amount by 100
		if ( 'zero_decimal' !== this.state.verified_currency_type ) {
			var visual_tithe_amount = this.state.tithe_amount / 100;
		} else {
			var visual_tithe_amount = this.state.tithe_amount;
		}

		var locale = this.props.main_component.state.dynamic_settings.locale.replace("_", "-");

		// Localize the amount (commas as decimal places, etc)
		visual_tithe_amount = visual_tithe_amount.toLocaleString(locale);//new Intl.NumberFormat(locale, { style: 'currency', currency: this.state.verified_currency }).format(visual_tithe_amount);

		// Replace the currency symbol from the Intl.NumberFormat with our own prettier one (US$ and CA$ are ugly, and adding "CAD" after is nicer to look at)
		//visual_tithe_amount = visual_tithe_amount.split(/(?<=^\S+)\s/)[1];

		return  this.state.verified_currency_symbol + visual_tithe_amount;
	}

	get_amount_field_step_format() {
		// If this is not a zero-decimal currency, handle all the decimal requirements
		if ( 'zero_decimal' !== this.state.verified_currency_type ) {
			var step_format = "0.01";
		} else {
			var step_format = "1";
		}

		// At this point HTML5 number forms don't seem to support translated decimals. But if they do, use church_tithe_wp_get_decimal_character_for_locale to get it
		return step_format;
	}

	// This function will return a class that either transitions an element in or out, based on the view state of this component
	get_view_class( payment_modes ) {

		var currently_in_view_class_name = 'church-tithe-wp-current-view';
		var hidden_class_name = 'church-tithe-wp-hidden-view';

		// If the item in question should be shown based on the current payment mode of this component
		if( payment_modes.indexOf( this.state.payment_mode ) != -1 ) {

			return ' ' + currently_in_view_class_name;

		} else {

			return ' ' + hidden_class_name;

		}

	}

	get_disabled_status( payment_modes ) {

		// If the item in question should be shown based on the current view of this component
		if( payment_modes.indexOf( this.state.payment_mode ) != -1 ) {

			return '';

		} else {

			return 'disabled';

		}
	}

	validate_tithe_amount( all_fields_validate, modify_state ) {

		// Validate the Tithe Amount field
		if ( ! this.state.tithe_amount ) {

			all_fields_validate = false;

			if ( modify_state ) {
				this.setState({
					input_fields_tithe_amount_current_instruction: 'empty'
				});
			}

		} else {

			if ( modify_state ) {
				this.setState({
					input_fields_tithe_amount_current_instruction: 'initial'
				});
			}
		}

		return all_fields_validate;

	}

	validate_currency( all_fields_validate, modify_state ) {

		// Validate the currency field
		if ( ! this.state.verified_currency ) {

			all_fields_validate = false;

			if ( modify_state ) {
				this.setState({
					input_fields_tithe_amount_current_instruction: 'invalid_currency'
				});
			}

		} else {

			if ( modify_state ) {
				this.setState({
					input_fields_tithe_amount_current_instruction: 'initial'
				});
			}
		}

		return all_fields_validate;

	}

	create_stripe_payment_method() {

		return new Promise( (resolve, reject) => {

			var this_component = this;

			var billing_details = {
				billing_details: {
					address: {
						postal_code: this.state.postal_code
					},
					email: this.state.email_value,
					name: this.props.name_value,
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
				).then(function(result) {
					if (result.error) {

						// Show error in payment form
						this_component.setState( {
							stripe_payment_method: null,
							stripe_card_error_code: result.error.code
						}, function() {
							reject(result.error.code);
							return result.error;
						} );

					} else {

						this_component.setState( {
							stripe_payment_method: result.paymentMethod,
							stripe_card_error_code: 'success'
						}, function() {
							resolve( result.paymentMethod );
							return result.paymentMethod;
						} );

					}
				});

			}

		});

	};

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

		// Fields present no matter the payment mode
		all_fields_validate = this.validate_tithe_amount( all_fields_validate, modify_state );
		all_fields_validate = this.validate_currency( all_fields_validate, modify_state );

		// Privacy Policy
		if ( this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_body ) {
			if ( ! this.state.privacy_policy_validated ) {
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
					current_payment_state: 'payment_attempt_failed',
				});
			} else {
				this.setState( {
					form_has_any_error: false,
					current_payment_state: 'initial',
				});
			}
		}

		return all_fields_validate;

	}

	handleCreditCardSubmit( event ){

		event.preventDefault();

		this.setState( {
			current_payment_state: 'attempting_payment'
		});

		// Create a stripe source using the submitted info
		this.create_stripe_payment_method().then( () => {

			// Validate the fields
			var allow_form_to_be_submitted = this.validate_form( true );

			// Prevent the form submission if a field didn't validate
			if ( ! allow_form_to_be_submitted ) {

				this.setState( {
					current_payment_state: 'payment_attempt_failed',
				}, () => {
					return false;
				} );

			} else {
				this.do_ajax_stripe_transaction();
			}

		} ).catch((err) => {

			this.setState( {
				stripe_card_error_code: err,
				current_payment_state: 'payment_attempt_failed',
			}, () => {
				console.log( err );
			});

			// Validate the fields
			var allow_form_to_be_submitted = this.validate_form( true );

			// Prevent the form submission if a field didn't validate
			return false;

		} );

	}

	do_ajax_stripe_transaction() {

		this.setState( {
			current_payment_state: 'attempting_payment'
		});

		var this_component = this;

		// Send the request to the server so that we can create a PaymentIntent

		// Use ajax to do the stripe transaction on the server using this data.
		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_get_payment_intent' );
		postData.append('church_tithe_wp_stripe_payment_method_id', this_component.state.stripe_payment_method.id);
		postData.append('church_tithe_wp_amount', this_component.state.tithe_amount);
		postData.append('church_tithe_wp_name', this_component.state.name_value);
		postData.append('church_tithe_wp_email', this_component.state.email_value);
		postData.append('church_tithe_wp_currency', this_component.state.verified_currency.toLowerCase());
		postData.append('church_tithe_wp_method', this_component.state.payment_method_name);
		postData.append('church_tithe_wp_page_url', this_component.props.main_component.state.single_page_app_base_url);
		postData.append('church_tithe_wp_recurring_value', this_component.state.recurring_value);

		// First off, generate and get a PaymentIntent on the server
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_payment_intent', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						current_payment_state: 'payment_attempt_failed',
						stripe_error_message: 'Looks like there was a problem. Status Code: ' + response.status
					});

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Pass the PaymentIntent's client secret off to Stripe
							this_component.props.stripe.confirmPaymentIntent(
								data.client_secret,
								{
									save_payment_method: true
								}
							).then( function( payment_intent_result ){

								// If the Payment Intent was unable to be confirmed
								if ( payment_intent_result.error ) {

									this_component.setState( {
										current_payment_state: 'payment_attempt_failed',
										stripe_error_message: payment_intent_result.error.message
									} );

								} else {

									// Success with no 3DS
									if ('succeeded' === payment_intent_result.paymentIntent.status) {

										// Send us to the purchase confirmation, where we'll send it to the server to be stored
										this_component.setState( {
											current_payment_state: 'success'
										});

										this_component.props.main_component.setState( {
											session_id: data.session_id,
											user_id: data.user_id,
											current_transaction_info: data.transaction_info
										});

										// Wait for a moment and then show the purchase confirmation view
										setTimeout( function() {
											this_component.props.main_component.set_all_current_visual_states( {
												payment: {
													confirmation: {}
												}
											} );
										}, 1000 );

									} else {

										// It didn't go through without 3DS so try it as a card payment so we can use Stripe simpified 3DS
										// We got a payment intent from Stripe, so process it now
										this_component.props.stripe.handleCardPayment(
											data.client_secret,
											{}
										).then(function(payment_intent_result) {
											// If the response from handleCardPayment was no good
											if (payment_intent_result.error) {

												console.log( payment_intent_result.error );

												// Display error.message in the UI.
												this_component.setState( {
													current_payment_state: 'payment_attempt_failed',
													stripe_error_message: payment_intent_result.error.message
												});

											} else {

												// The PaymentIntent was successful
												// Send us to the purchase confirmation, where we'll send it to the server to be stored
												this_component.setState( {
													current_payment_state: 'success'
												});

												this_component.props.main_component.setState( {
													session_id: data.session_id,
													user_id: data.user_id,
													current_transaction_info: data.transaction_info
												});

												// Wait for a moment and then show the purchase confirmation view
												setTimeout( function() {
													this_component.props.main_component.set_all_current_visual_states( {
														payment: {
															confirmation: {}
														}
													} );
												}, 1000 );

											}
										});

									}
								}

							} );

						} else {

							this_component.setState( {
								current_payment_state: 'payment_attempt_failed',
								stripe_error_message: data.details
							});
						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							current_payment_state: 'payment_attempt_failed',
							stripe_error_message: 'Unable to make payment at this time. Please try again.'
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					current_payment_state: 'payment_attempt_failed',
					stripe_error_message: this_component.strings.general_server_error
				});

				console.log('Fetch Error: ', err);
			}
		);

	}

	handleAmountChange( event ) {

		var tithe_amount = event.target.value;

		// If this is not a zero-decimal currency, multithely the amount by 100
		if ( 'zero_decimal' !== this.state.verified_currency_type ) {
			var stripe_tithe_amount = Number( tithe_amount ) * 100;
		} else {
			var stripe_tithe_amount = Number( tithe_amount );
		}

		// Make sure the math is all done correctly since javascript is strange at multithelication
		stripe_tithe_amount = Number( stripe_tithe_amount.toFixed(0) );

		if ( 1 <= stripe_tithe_amount ) {

			// Update the payment request button to match the amount
			if ( this.state.paymentRequest ) {
				this.state.paymentRequest.update({
					currency: this.state.verified_currency.toLowerCase(),
					total: {
						label: this.props.main_component.state.unique_settings.strings.payment_request_label,
						amount: stripe_tithe_amount,
					},
				} );
			}

			this.setState( {
				tithe_amount: stripe_tithe_amount,
				paymentRequest: this.state.paymentRequest
			} );

		} else {

			stripe_tithe_amount = '';

			this.setState( {
				tithe_amount: null,
			} );
		}

		if ( this.state.form_has_any_error ) {
			this.validate_form( true );
		}

	};

	handlePaymentRequestValidateButton( event ) {

		this.validate_form( true );

	};

	set_payment_mode( mode ) {

		if ( 'toggle' == mode ) {
			if ( 'payment_request' == this.state.payment_mode ) {
				this.setState( {
					payment_mode: 'credit_card',
					payment_method_name: 'basic-card',
					stripe_error_message: null,
					form_has_any_error: false,
					form_validation_attempted: false,
				}, () => {

					// Update the visual state of the entire Single Page Application so that the URL is updated as well
					this.props.main_component.set_all_current_visual_states( {
						payment: {
							form: {
								[this.state.payment_mode]: {}
							}
						}
					} );

					//this.validate_form( true );
				} );
			} else if ( 'credit_card' == this.state.payment_mode ) {
				this.setState( {
					payment_mode: 'payment_request',
					payment_method_name: this.state.payment_request_button_name,
					stripe_error_message: null,
					form_has_any_error: false,
					form_validation_attempted: false,
				}, () => {

					// Update the visual state of the entire Single Page Application so that the URL is updated as well
					this.props.main_component.set_all_current_visual_states( {
						payment: {
							form: {
								[this.state.payment_mode]: {}
							}
						}
					} );

					//this.validate_form( true );
				} );
			}
		} else {

			var payment_method_name = 'credit_card' === mode ? 'basic-card' : this.state.payment_request_button_name;

			this.setState( {
				payment_mode: mode,
				payment_method_name: payment_method_name,
				stripe_error_message: null,
				form_has_any_error: false,
				form_validation_attempted: false,
			}, () => {

				// Update the visual state of the entire Single Page Application so that the URL is updated as well
				this.props.main_component.set_all_current_visual_states( {
					payment: {
						form: {
							[this.state.payment_mode]: {}
						}
					}
				} );

				//this.validate_form( true );
			} );
		}

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

	render_payment_field( payment_field_to_render, array_of_visible_states ) {

		if ( typeof this['render_' + payment_field_to_render + '_field' ] === "function" ) {

			var field_content = this['render_' + payment_field_to_render + '_field' ]();

			// If there's something to show
			if ( field_content ) {
				return (
					<div className={ 'church-tithe-wp-payment-field-container church-tithe-wp-payment-field-' + payment_field_to_render }>
					<div className={ 'church-tithe-wp-payment-field ' + this.get_view_class( array_of_visible_states ) }>
					{ this['render_' + payment_field_to_render + '_field' ]() }
					</div>
					</div>
				)
			} else {
				return( '' )
			}
		} else {
			console.log( 'Not found: ' + payment_field_to_render );
		}

	}

	get_currency_flag_class() {

		if ( ! this.state.verified_currency ) {
			return ' flag';
		}

		return ' flag flag-' + this.state.verified_currency.substring(0, 2).toLowerCase();
	}

	toggle_currency_search() {

		if ( this.state.currency_search_visible ) {
			this.setState( {
				currency_search_visible: false
			} );
		} else {
			this.setState( {
				currency_search_visible: true,
				user_typed_currency: '',
				currency_typing_frozen: false
			}, () => {
				this.currency_text_input.focus();
			} );
		}
	}

	confirm_currency_exists( currency_to_confirm ) {

		var this_component = this;

		// Use ajax to do the stripe transaction on the server using this data.
		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_confirm_currency');
		postData.append('church_tithe_wp_currency_to_confirm', currency_to_confirm);

		// Confirm whether this currency is one supported or not, or if it even is a currency
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_confirm_currency', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						verified_currency: false,
						verified_currency_symbol: this_component.unique_settings.currency_symbol,
						verified_currency_type: this_component.unique_settings.currency_type
					});

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							if ( 'search_not_long_enough' == data.success_type ) {
								// Make no changes yet
							}

							if ( 'more_than_one_currency_matched' == data.success_type ) {
								// Make no changes yet
							}

							if ( 'one_currency_matched' == data.success_type ) {
								// Make changes now that only one currency matches
								this_component.setState( {
									user_typed_currency: data.validated_currency,
									verified_currency: data.validated_currency,
									verified_currency_symbol: data.validated_currency_symbol,
									verified_currency_type: data.validated_currency_type,
									currency_search_visible: false,
									// Freeze typing for 3 seconds since we just forced a 3 letter currency into the field
									currency_typing_frozen: true
								}, () => {

									// Update the payment request button to match this new currency
									if ( 1 <= this_component.state.tithe_amount ) {
										if ( this_component.state.paymentRequest ) {
											this_component.state.paymentRequest.update({
												currency: this_component.state.verified_currency.toLowerCase(),
												total: {
													label: 'Pay',
													amount: this_component.state.tithe_amount,
												},
											} );
										}

										this_component.setState( {
											paymentRequest: this_component.state.paymentRequest
										}, () => {
											this_component.validate_currency( true, true );
										} );
									}

								} );

								// Wait 3 seconds, then unfreeze the currency typing
								setTimeout( () => {
									this_component.setState( {
										currency_typing_frozen: false
									} );
								}, 3000 );

							}

						} else {
							// No valid currency was found, so reset it to the default currency.
							this_component.setState( {
								verified_currency: false,
								verified_currency_symbol: this_component.unique_settings.currency_symbol,
								verified_currency_type: this_component.unique_settings.currency_type
							}, () => {
								this_component.validate_currency( true, true );
							} );
						}
					}
				).catch(
					function( err ) {
						this_component.setState( {
							verified_currency: false,
							verified_currency_symbol: this_component.unique_settings.currency_symbol,
							verified_currency_type: this_component.unique_settings.currency_type
						} );

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {
				this_component.setState( {
					verified_currency: false,
					verified_currency_symbol: this_component.unique_settings.currency_symbol,
					verified_currency_type: this_component.unique_settings.currency_type
				} );

				console.log('Fetch Error: ', err);
			}
		);

	}

	handle_currency_typing( event ) {

		if ( this.state.currency_typing_frozen ) {
			// Allow the string to get shorter, but not longer for 3 seconds
			if ( event.target.value.length > this.state.user_typed_currency.length ) {
				return;
			}
			// If the string had gotten shorter, these are backspaces, so we can unfreeze the typing
			else {
				this.setState( {
					currency_typing_frozen: false
				} );
			}
		}

		if ( ! event.target.value ) {
			this.setState( {
				user_typed_currency: event.target.value.toUpperCase(),
				verified_currency: this.props.main_component.state.unique_settings.currency_code,
				verified_currency_symbol: this.props.main_component.state.unique_settings.currency_symbol
			} );
		}

		this.setState( {
			user_typed_currency: event.target.value.toUpperCase()
		}, () => {
			this.confirm_currency_exists( this.state.user_typed_currency );
		} );
	}

	handle_currency_enter( event ) {
		// Set the currency search field to be blank and focused
		this.setState( {
			user_typed_currency: '',
		} );
	}

	handle_currency_leave( event ) {
		// If the currency search field is blank and they leave the field, default it back to the original default currency
		if ( ! event.target.value ) {
			this.setState( {
				user_typed_currency: this.props.main_component.state.unique_settings.currency_code,
				verified_currency: this.props.main_component.state.unique_settings.currency_code,
				verified_currency_symbol: this.props.main_component.state.unique_settings.currency_symbol,
				currency_search_visible: false,
			}, () => {
				this.validate_form();
			} );
		}
	}

	render_currency_switcher() {

		if ( this.state.currency_search_visible ) {
			return(
				<div className={ 'church-tithe-wp-tithe-currency-code' }>
					<span className={ 'church-tithe-wp-tithe-currency-flag-container' }>
						{ ( () => {
							// If no flag is currenly found, show a spinner
							if( ! this.state.verified_currency ) {
								return( <Church_Tithe_WP_Spinner color_mode="church-tithe-wp-spinner-dark" /> );
							}
							// If a valid currency has been chosen, show the flag
							else {
								return (
									<img
										src={ this.props.main_component.state.unique_settings.blank_flag_url }
										style={ {
											backgroundImage: 'url(' + this.props.main_component.state.unique_settings.flag_sprite_url + ')',
										} }
										className={ 'church-tithe-wp-tithe-currency-flag' +  this.get_currency_flag_class() }
									/>
								);
							}
						})()}
					</span>
					<span className={ 'church-tithe-wp-tithe-currency-text-container' }>
						<input
							ref={(input) => this.currency_text_input = input}
							type="text"
							value={ this.state.user_typed_currency }
							onChange={ this.handle_currency_typing.bind( this ) }
							onFocus={ this.handle_currency_enter.bind( this ) }
							onBlur={ this.handle_currency_leave.bind( this ) }
							placeholder={ this.props.main_component.state.unique_settings.strings.currency_search_text }
							autoComplete={ 'off' }
						/>
					</span>
				</div>
			);
		} else {
			return(
				<button className={ 'church-tithe-wp-tithe-currency-code-toggle-btn' } onClick={ this.toggle_currency_search.bind( this ) }>
					<div className={ 'church-tithe-wp-tithe-currency-code' } >
						<span className={ 'church-tithe-wp-tithe-currency-flag-container' }>
							<img
								src={ this.props.main_component.state.unique_settings.blank_flag_url }
								style={ {
									backgroundImage: 'url(' + this.props.main_component.state.unique_settings.flag_sprite_url + ')',
								} }
								className={ 'church-tithe-wp-tithe-currency-flag' +  this.get_currency_flag_class() }
							/>
						</span>
						<span className={ 'church-tithe-wp-tithe-currency-text-container' }>
							{ this.state.verified_currency }
						</span>
					</div>
				</button>
			);
		}
	}

	render_amount_field() {

		return (
			<div>
				<Church_Tithe_WP_Input_Field_Instruction current_instruction={ this.state.input_fields_tithe_amount_current_instruction } instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.tithe_amount } />
				<div className={ 'church-tithe-wp-amount-container' +  ( () => {
					if ( this.state.currency_search_visible ) {
						return ' currency-search-visible';
					} else {
						return '';
					}
				})()}>
					<div className={ 'church-tithe-wp-tithe-currency-symbol' }>{ this.state.verified_currency_symbol }</div>
					<div className={ 'church-tithe-wp-tithe-amount-input-container' }>
						<label>
							<input
								disabled={ this.get_disabled_status( [ 'credit_card', 'payment_request' ] ) }
								type="number"
								min={ 1 }
								step={ this.get_amount_field_step_format() }
								className={ 'church-tithe-wp-tithe-amount-input' }
								placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.tithe_amount.placeholder_text }
								name="tithe-amount"
								onChange={ this.handleAmountChange.bind( this ) }
								value={ this.get_visual_amount_for_input_field() }
								lang={ this.props.main_component.state.dynamic_settings.locale.replace("_", "-") }
							/>
						</label>
					</div>
					<div className={ 'church-tithe-wp-currency-switcher' }>
						{ this.render_currency_switcher() }
					</div>
				</div>
			</div>
		);
	}

	render_recurring_field() {

		return(
			<Church_Tithe_WP_Radio_Field
				state_validation_variable_name={ 'recurring_validated' }
				state_value_variable_name={ 'recurring_value' }
				set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
				form_validation_attempted={ this.state.form_validation_attempted }
				is_validated={ this.state.recurring_validated }
				validate_form={ this.validate_form.bind( this ) }
				instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.recurring }

				type="radio"
				radio_buttons={ this.props.main_component.state.unique_settings.recurring_options }
				class_name={ 'church-tithe-wp-recurring' }
				placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.recurring.placeholder_text }
				name="recurring"
			/>
		);

	}

	render_privacy_policy_field() {

		if ( this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_body ) {
			return (
				<Church_Tithe_WP_Terms_Field
				state_validation_variable_name={ 'privacy_policy_validated' }
				set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
				form_validation_attempted={ this.state.form_validation_attempted }
				is_validated={ this.state.privacy_policy_validated }
				validate_form={ this.validate_form.bind( this ) }
				instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy }
				terms_title={ this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_title }
				terms_body={ this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_body }
				terms_show_text={ this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_show_text }
				terms_hide_text={ this.props.main_component.state.unique_settings.strings.input_field_instructions.privacy_policy.terms_hide_text }
				disabled={ this.get_disabled_status( [ 'credit_card', 'payment_request' ] ) }
				/>
			);
		}
	}

	render_email_field() {

		return(
			<Church_Tithe_WP_Email_Field
			state_validation_variable_name={ 'email_validated' }
			state_value_variable_name={ 'email_value' }
			set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
			form_validation_attempted={ this.state.form_validation_attempted }
			is_validated={ this.state.email_validated }
			validate_form={ this.validate_form.bind( this ) }
			instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.email }
			initial_value={ this.props.main_component.state.unique_settings.strings.current_user_email }

			type="email"
			class_name={ 'church-tithe-wp-email' }
			placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.email.placeholder_text }
			name="email"
			disabled={ this.get_disabled_status( [ 'credit_card' ] ) }
			/>
		)
	}

	render_name_field() {
		return(
			<Church_Tithe_WP_Input_Field
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
			disabled={ this.get_disabled_status( [ 'credit_card' ]) }
			/>
		)
	}

	render_all_in_one_credit_card_field() {
		return(
			<Church_Tithe_WP_Stripe_All_In_One_Field
			card_form={ this }
			form_validation_attempted={ this.state.form_validation_attempted }
			validate_form={ this.validate_form.bind( this ) }
			stripe_card_error_code={ this.state.stripe_card_error_code }
			instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.stripe_all_in_one }
			stripe={ this.props.stripe }
			disabled={ this.get_disabled_status( [ 'credit_card' ] ) }
			mobile_mode={ window.innerWidth > 600 ? false : true}
			zip_code_placeholder={ this.props.main_component.state.unique_settings.strings.zip_code_placeholder }
			create_stripe_source={ this.create_stripe_payment_method }
			/>
		)

	}

	render_payment_error_field() {

		// Handle form errors
		if ( this.state.form_has_any_error ) {
			return(
				<div className={ 'church-tithe-wp-payment-error-message'}>{ this.props.main_component.state.unique_settings.strings.form_has_an_error }</div>
			)
		}

		// Handle errors after form was submitted
		if ( 'payment_attempt_failed' == this.state.current_payment_state && this.state.stripe_error_message ) {
			return(
				<div className={ 'church-tithe-wp-payment-error-message'}>{ this.state.stripe_error_message }</div>
			)
		} else {
			return( '' )
		}
	}

	render_recurring_text_on_payment_button() {

		if ( ! this.state.recurring_value || 'never' == this.state.recurring_value ) {
			return '';
		}

		return ' ' + this.props.main_component.state.unique_settings.recurring_options[this.state.recurring_value].after_output.toLowerCase();

	}

	render_payment_request_button_field() {

		if ( 'initial' == this.state.current_payment_state || 'payment_attempt_failed' == this.state.current_payment_state ) {

			// We need to decide if we show the payment request button, or a "Next" button, which validates the form
			if ( this.validate_form( false ) ) {

				return(
					<div>
					<PaymentRequestButtonElement
						paymentRequest={ this.state.paymentRequest }
						className="PaymentRequestButton"
						style={{
							// For more details on how to style the Payment Request Button, see:
							// https://stripe.com/docs/elements/payment-request-button#styling-the-element
							paymentRequestButton: {
								theme: 'dark',
								height: '50px',
							},
						}}
					/>
					</div>
				)
			} else {

				var currency_for_button = this.state.verified_currency ? ' (' + this.state.verified_currency + ')' : '';
				var button_text = this.props.main_component.state.unique_settings.strings.payment_verb + ' ' + this.get_visual_amount() + this.render_recurring_text_on_payment_button() + currency_for_button;

				return (
					<button className={ 'church-tithe-wp-pay-button' } type="button" onClick={ this.handlePaymentRequestValidateButton.bind( this ) } disabled={ this.get_disabled_status( [ 'payment_request' ] ) }>{ button_text }</button>
				)
			}
		}
		if ( 'attempting_payment' == this.state.current_payment_state || 'success' == this.state.current_payment_state ) {
			return this.render_complete_payment_button_field();
		}

	}

	render_complete_payment_button_field() {

		var button_class;
		var button_text;
		var currency_for_button = this.state.verified_currency ? ' (' + this.state.verified_currency + ')' : '';

		if ( 'initial' == this.state.current_payment_state ) {
			button_class = '';
			button_text = this.props.main_component.state.unique_settings.strings.payment_verb + ' ' + this.get_visual_amount() + this.render_recurring_text_on_payment_button() + currency_for_button;
		}
		if ( 'attempting_payment' == this.state.current_payment_state ) {
			button_class = ' church-tithe-wp-btn-attempting-payment';
			button_text = <Church_Tithe_WP_Spinner />;
		}
		if ( 'success' == this.state.current_payment_state ) {
			button_class = ' church-tithe-wp-btn-success';
			button_text = <span><Church_Tithe_WP_Checkmark /></span>;
		}
		if ( 'payment_attempt_failed' == this.state.current_payment_state ) {
			button_class = ' church-tithe-wp-btn-error';
			button_text = this.props.main_component.state.unique_settings.strings.complete_payment_button_error_text;
		}

		return (
			<div>
				<button type="button" onClick={this.handleCreditCardSubmit.bind( this )} disabled={ this.get_disabled_status( [ 'credit_card' ] ) } className={ 'church-tithe-wp-pay-button' + button_class }>{ button_text }</button>
			</div>
		)
	}

	render_payment_mode_toggler_field() {


			return(
				<button type="button" className={ 'church-tithe-wp-other-payment-option church-tithe-wp-input-instruction church-tithe-wp-text-button' } onClick={ this.set_payment_mode.bind( null, 'toggle' ) }>{ this.props.main_component.state.unique_settings.strings.other_payment_option }</button>
			)

	}

	render_manage_payments_button() {


			return(
				<button type="button" className={ 'church-tithe-wp-manage-payments-button church-tithe-wp-input-instruction church-tithe-wp-text-button' } onClick={ this.props.main_component.set_all_current_visual_states.bind( null, {
					manage_payments: {}
				}, false ) }>{ this.props.main_component.state.unique_settings.strings.manage_payments_button_text }</button>
			)

	}

	render_card_form() {

		if( this.state.canDoPaymentRequest ) {
			return(
				<form onSubmit={this.handleCreditCardSubmit}>

				{ this.render_payment_field( 'amount', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'recurring', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'email', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'name', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'all_in_one_credit_card', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'privacy_policy', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'payment_error', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'payment_request_button', [ 'payment_request' ] ) }

				{ this.render_payment_field( 'complete_payment_button', [ 'credit_card' ] ) }

				<div className="church-tithe-wp-payment-field-container top-jar-wp-alternate-actions">
					{ this.render_payment_mode_toggler_field() }
					<span className="top-jar-wp-alternate-actions-separator">|</span>
					{ this.render_manage_payments_button() }
				</div>

				</form>
			)
		} else {

			return(
				<form onSubmit={this.handleCreditCardSubmit}>

				{ this.render_payment_field( 'amount', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'recurring', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'email', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'name', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'all_in_one_credit_card', [ 'credit_card' ] ) }

				{ this.render_payment_field( 'privacy_policy', [ 'credit_card', 'payment_request' ] ) }

				{ this.render_payment_field( 'complete_payment_button', [ 'credit_card' ] ) }

				<div className="church-tithe-wp-payment-field-container top-jar-wp-alternate-actions">
					{ this.render_manage_payments_button() }
				</div>

				</form>
			)
		}
	}

	render() {

		if ( ! this.state.payment_mode ) {
			return(
				<Church_Tithe_WP_Spinner color_mode="church-tithe-wp-spinner-dark" />
			);
		} else {
			return (
				this.render_card_form()
			);
		}
	}
}
const CardForm = injectStripe(Church_Tithe_WP_Card_Form);
