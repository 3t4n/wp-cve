import React from 'react';
import {CardElement} from 'react-stripe-elements';
import {CardNumberElement} from 'react-stripe-elements';
import {CardExpiryElement} from 'react-stripe-elements';
import {CardCvcElement} from 'react-stripe-elements';
import {PostalCodeElement} from 'react-stripe-elements';

window.Church_Tithe_WP_Stripe_All_In_One_Field = class Church_Tithe_WP_Stripe_All_In_One_Field extends React.Component {

	constructor( props ) {
		super(props);

		this.state= {
			stripe_card_error_code: null
		};

		this.get_input_field_class = this.get_input_field_class.bind( this );
		this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
		this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
		this.handle_cc_validation = this.handle_cc_validation.bind( this );
	};

	componentDidUpdate(){
		if ( this.props.stripe_card_error_code !== this.state.stripe_card_error_code ) {
			this.setState( {
				stripe_card_error_code: this.props.stripe_card_error_code
			} );
		}
	}

	get_current_instruction_key() {

		// Handle the instruction only if the form containing this field has been submitted
		if ( this.props.form_validation_attempted ) {

			if ( ! this.state.stripe_card_error_code || 'none' == this.state.stripe_card_error_code ) {
				return 'success';
			}
			if ( this.state.stripe_card_error_code ) {
				return this.state.stripe_card_error_code;
			}

		} else {

			if ( ! this.state.stripe_card_error_code || 'none' == this.state.stripe_card_error_code ) {
				return 'success';
			}
			if ( this.state.stripe_card_error_code ) {
				return 'initial';
			}
		}
	}

	get_input_instruction_class() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.instruction_codes[current_instruction] ) {
			if ( 'error' == this.props.instruction_codes[current_instruction].instruction_type ) {
				return ' church-tithe-wp-instruction-error';
			}
		}

		return '';

	};

	get_input_field_class() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.instruction_codes[current_instruction] ) {
			if ( 'success' == this.props.instruction_codes[current_instruction].instruction_type ) {
				return ' church-tithe-wp-input-success';
			}
			if ( 'error' == this.props.instruction_codes[current_instruction].instruction_type ) {
				return ' church-tithe-wp-input-error';
			}
			if ( 'initial' == this.props.instruction_codes[current_instruction].instruction_type ) {
				return '';
			}
		}

		return '';

	};

	get_input_instruction_message() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.instruction_codes[current_instruction] ) {
			return this.props.instruction_codes[current_instruction].instruction_message;
		} else {
			return 'Please check the credit card fields';
		}
	};

	handle_cc_validation() {

		// Pass the postal code up to the parent component.
		this.props.card_form.setState( {
			postal_code: this.state.card_zip_code,
			stripe_elements_fields_complete:  this.state.stripe_element ? this.state.stripe_element.complete : false
		}, () => {

			// If the card form has been submitted, that means errors are probably showing. So we should try updating those errors on every keystroke now.
			if ( this.props.form_validation_attempted ) {

				// Check with Stripe to see if the data entered is a valid credit card.
				this.props.create_stripe_source().then( () => {

					// If it is a valid credit card, set the instructions above the field to be in their original state.
					this.setState( {
						stripe_card_error_code: 'success'
					} );
				} ).catch( ( error ) => {

					// But if it is not a valid credit card, set the instructions to be the error code which we were given by the create_stripe_source function.
					this.setState( {
						stripe_card_error_code: error
					} );

				} );
			}

		});
	}

	handle_postal_code_change( event ) {

		this.setState( {
			card_zip_code: event.target.value
		}, () => {
			this.handle_cc_validation();
		} );

	}

	handle_input_change( element, all_in_one_mode ) {

		var credit_card_was_fully_filled_out = false;

		this.setState( {
			stripe_element: element,
		}, () => {
			// If we are pulling the zip code from the all-in-one field
			if ( all_in_one_mode ) {
				this.setState( {
					card_zip_code: element.value.postalCode
				}, () => {
					this.handle_cc_validation();
				} );
			} else {
				this.handle_cc_validation();
			}

		} );

	};

	maybe_render_all_in_one_field() {

		const createOptions = (fontSize) => {
			return {
				style: {
					base: {
						fontSize,
						color: '#424770',
						letterSpacing: '0.025em',
						fontFamily: 'Source Code Pro, Menlo, monospace',
						'::placeholder': {
							color: '#aab7c4',
						},
					},
					invalid: {
						color: '#9e2146',
					},
				},
			};
		};

		if ( ! this.props.disabled ) {
			return(
				<CardElement
				{...createOptions(this.props.fontSize) }
				onChange={ (element) => this.handle_input_change(element, true) }
				/>
			)
		} else {
			return '';
		}
	}

	maybe_render_all_in_one_field_mobile() {

		if ( ! this.props.disabled ) {
			return(
				<CardElement
				style={{base: {fontSize: '19px'}}}
				onChange={ (element) => this.handle_input_change(element, true) }
				/>
			)
		} else {
			return '';
		}
	}

	maybe_render_multi_field_cc_form() {

		if ( ! this.props.disabled ) {
			return(
				<div className='church-tithe-wp-cc-multi-field'>
					<div>
						<div className="church-tithe-wp-cc-multi-field-input-area">
							<label>
								<div className="church-tithe-wp-cc-form-icon church-tithe-wp-credit-card-icon">
									<svg role="img" className="Icon" fill="#2b2b2b" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
										<title>Credit Card</title>
										<path fillRule="evenodd" transform="translate(8, 10)" d="M2.00585866,0 C0.898053512,0 0,0.900176167 0,1.99201702 L0,9.00798298 C0,10.1081436 0.897060126,11 2.00585866,11 L11.9941413,11 C13.1019465,11 14,10.0998238 14,9.00798298 L14,1.99201702 C14,0.891856397 13.1029399,0 11.9941413,0 L2.00585866,0 Z M2.00247329,1 C1.44882258,1 1,1.4463114 1,1.99754465 L1,9.00245535 C1,9.55338405 1.45576096,10 2.00247329,10 L11.9975267,10 C12.5511774,10 13,9.5536886 13,9.00245535 L13,1.99754465 C13,1.44661595 12.544239,1 11.9975267,1 L2.00247329,1 Z M1,3 L1,5 L13,5 L13,3 L1,3 Z M11,8 L11,9 L12,9 L12,8 L11,8 Z M9,8 L9,9 L10,9 L10,8 L9,8 Z M9,8"></path>
									</svg>
								</div>
								<CardNumberElement
									style={{base: {fontSize: '19px'}}}
									onChange={ (element) => this.handle_input_change(element, false) }
								/>
							</label>
						</div>

						<div className="church-tithe-wp-cc-multi-field-input-area">
							<label>
								<div className="church-tithe-wp-cc-form-icon church-tithe-wp-calendar-icon">
									<svg role="img" className="Icon" fill="#2b2b2b" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
										<title>Calendar</title>
										<path fillRule="evenodd" transform="translate(8, 9)" d="M2.0085302,1 C0.899249601,1 0,1.90017617 0,2.99201702 L0,10.007983 C0,11.1081436 0.901950359,12 2.0085302,12 L9.9914698,12 C11.1007504,12 12,11.0998238 12,10.007983 L12,2.99201702 C12,1.8918564 11.0980496,1 9.9914698,1 L2.0085302,1 Z M1.99539757,4 C1.44565467,4 1,4.43788135 1,5.00292933 L1,9.99707067 C1,10.5509732 1.4556644,11 1.99539757,11 L10.0046024,11 C10.5543453,11 11,10.5621186 11,9.99707067 L11,5.00292933 C11,4.44902676 10.5443356,4 10.0046024,4 L1.99539757,4 Z M3,1 L3,2 L4,2 L4,1 L3,1 Z M8,1 L8,2 L9,2 L9,1 L8,1 Z M3,0 L3,1 L4,1 L4,0 L3,0 Z M8,0 L8,1 L9,1 L9,0 L8,0 Z M8,0"></path>
									</svg>
								</div>
								<CardExpiryElement
								style={{base: {fontSize: '19px'}}}
								onChange={ (element) => this.handle_input_change(element, false) }
								/>
							</label>
						</div>

						<div className="church-tithe-wp-cc-multi-field-input-area">
							<label>
								<div className="church-tithe-wp-cc-form-icon church-tithe-wp-lock-icon">
									<svg role="img" className="Icon" fill="#2b2b2b" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
										<title>Lock</title>
										<path fillRule="evenodd" transform="translate(9, 9)" d="M8.8,4 C8.8,1.79086089 7.76640339,4.18628304e-07 5.5,0 C3.23359661,-4.1480896e-07 2.2,1.79086089 2.2,4 L3.2,4 C3.2,2.34314567 3.81102123,0.999999681 5.5,1 C7.18897877,1.00000032 7.80000001,2.34314567 7.80000001,4 L8.8,4 Z M1.99201702,4 C0.891856397,4 0,4.88670635 0,5.99810135 L0,10.0018986 C0,11.1054196 0.900176167,12 1.99201702,12 L9.00798298,12 C10.1081436,12 11,11.1132936 11,10.0018986 L11,5.99810135 C11,4.89458045 10.0998238,4 9.00798298,4 L1.99201702,4 Z M1.99754465,5 C1.44661595,5 1,5.45097518 1,5.99077797 L1,10.009222 C1,10.5564136 1.4463114,11 1.99754465,11 L9.00245535,11 C9.55338405,11 10,10.5490248 10,10.009222 L10,5.99077797 C10,5.44358641 9.5536886,5 9.00245535,5 L1.99754465,5 Z M1.99754465,5"></path>
									</svg>
								</div>
								<CardCvcElement
									style={{base: {fontSize: '19px'}}}
									onChange={ (element) => this.handle_input_change(element, false) }
								/>
							</label>
						</div>

						<div className={ 'church-tithe-wp-cc-multi-field-input-area' }>
							<label>
								<div className="church-tithe-wp-cc-form-icon church-tithe-wp-zipcode-icon">
									<svg role="img" className="Icon" fill="#2b2b2b" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
										<title>Push-pin</title>
										<path fillRule="evenodd" transform="translate(8, 7)" d="M6.96835335,14.4141594 C7.14378184,14.2130951 7.33880908,13.9850182 7.54859821,13.7339676 C8.14789969,13.0167952 8.74728299,12.2571079 9.30845088,11.4862878 C10.9985122,9.16482011 12,7.25762947 12,5.80510753 C12,2.58994421 9.3050091,0 6,0 C2.6949909,0 0,2.58994421 0,5.80510753 C0,7.25762947 1.00148783,9.16482011 2.69154912,11.4862878 C3.25271701,12.2571079 3.85210031,13.0167952 4.45140179,13.7339676 C4.66119092,13.9850182 4.85621816,14.2130951 5.03164665,14.4141594 C5.13795558,14.5360035 5.2148819,14.6226158 5.25757295,14.6699269 L6,15.4927001 L6.74242705,14.6699269 C6.7851181,14.6226158 6.86204442,14.5360035 6.96835335,14.4141594 Z M5.25757295,13.3300731 C5.27604949,13.309597 5.30380421,13.278504 5.34014057,13.2373842 C3.98193439,11.6258984 1,7.854524 1,5.80510753 C1,3.15131979 3.23857611,1 6,1 C8.76142389,1 11,3.15131979 11,5.80510753 C11,7.854524 8.01806561,11.6258984 6.65985943,13.2373842 C6.69619579,13.278504 6.72395051,13.309597 6.74242705,13.3300731 L6.58151981,13.3300731 C6.22583758,13.7497221 6,14 6,14 C6,14 5.77416242,13.7497221 5.41848019,13.3300731 L5.25757295,13.3300731 Z M6,8 C7.10456955,8 8,7.10456955 8,6 C8,4.89543045 7.10456955,4 6,4 C4.89543045,4 4,4.89543045 4,6 C4,7.10456955 4.89543045,8 6,8 Z M6,7 C6.55228478,7 7,6.55228478 7,6 C7,5.44771522 6.55228478,5 6,5 C5.44771522,5 5,5.44771522 5,6 C5,6.55228478 5.44771522,7 6,7 Z M6,7"></path>
									</svg>
								</div>
								<input
									className={ 'church-tithe-wp-cc-multi-field-input-zip-code ' + this.get_postal_code_success_class() }
									type="text"
									onChange={ this.handle_postal_code_change.bind(this) }
									placeholder={ this.props.zip_code_placeholder }
								/>
							</label>
						</div>
					</div>
				</div>
			)
		} else {
			return '';
		}
	}

	get_postal_code_success_class() {

		if ( this.state.card_zip_code ) {
			return ' church-tithe-wp-input-success';
		} else if( this.props.form_validation_attempted ) {
			return ' church-tithe-wp-input-error';
		} else {
			return '';
		}
	}

	render(){

		if ( ! this.props.mobile_mode ) {

			return(
				<div className={ 'church-tithe-wp-cc-form church-tithe-wp-non-mobile' }>
					<label>
						<div className={ 'church-tithe-wp-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
						{ this.maybe_render_all_in_one_field() }
					</label>
				</div>
			)

		} else {

			return(
				<div className={ 'church-tithe-wp-cc-form church-tithe-wp-mobile' }>
					<div className={ 'church-tithe-wp-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
					{ this.maybe_render_multi_field_cc_form() }
				</div>
			)
		}
	}

};

export default Church_Tithe_WP_Stripe_All_In_One_Field;
