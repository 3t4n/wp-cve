var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Login = class Church_Tithe_WP_Login extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			current_visual_state: 'login_form',
			login_error_message: null,

			email_value: null,
			email_validated:false,

			login_code_digit_1: '',
			login_code_digit_2: '',
			login_code_digit_3: '',
			login_code_digit_4: '',
			login_code_digit_5: '',
			login_code_digit_6: '',

		};

		this.handle_login_form_submit = this.handle_login_form_submit.bind( this );

		// Set up refs for the input fields
		this.login_code_digit_1 = React.createRef();
		this.login_code_digit_2 = React.createRef();
		this.login_code_digit_3 = React.createRef();
		this.login_code_digit_4 = React.createRef();
		this.login_code_digit_5 = React.createRef();
		this.login_code_digit_6 = React.createRef();
	}

	componentDidMount( ) {
		this.setState( {
			prior_all_current_visual_states: this.props.main_component.state.all_current_visual_states
		} );
 	}

	request_login_email() {

		this.setState( {
			current_visual_state: 'loading',
			login_error_message: null
		});

		var this_component = this;

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_email_login' );
		postData.append('church_tithe_wp_email', this_component.state.email_value);
		postData.append('church_tithe_wp_email_login_nonce', this.props.main_component.state.frontend_nonces.email_login_nonce);

		// Request a login email
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_email_login', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						current_visual_state: 'login_form',
						login_error_message: 'Looks like there was a problem. Status Code: ' + response.status
					});

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Change the view to accept an code, with a 6 digit input
							this_component.setState( {
								current_visual_state: 'login_code_input',
								login_error_message: null,
							});

						} else {

							this_component.setState( {
								current_visual_state: 'login_form',
								login_error_message: data.details,
							}, () => {
								this_component.login_code_digit_1.focus();
							} );
						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							current_visual_state: 'login_form',
							login_error_message: this_component.props.main_component.strings.general_server_error
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					current_visual_state: 'login_form',
					login_error_message: this_component.props.main_component.strings.general_server_error
				});

				console.log('Fetch Error :-S', err);
			}
		);
	}

	attempt_to_login() {

		this.setState( {
			current_visual_state: 'loading',
			login_error_message: null
		});

		var this_component = this;

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_attempt_user_login' );
		postData.append('church_tithe_wp_email', this_component.state.email_value);
		postData.append('church_tithe_wp_login_code', this_component.state.login_code);
		postData.append('church_tithe_wp_login_nonce', this.props.main_component.state.frontend_nonces.login_nonce);

		// Handle a login over email
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_attempt_user_login', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						current_visual_state: 'login_code_input',
						login_error_message: 'Looks like there was a problem. Status Code: ' + response.status
					});

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Show the checkmark that the login was successful
							this_component.setState( {
								current_visual_state: 'login_attempt_succeeded',
								login_error_message: null,
							});

							// After .3 seconds of showing the checkmark, refresh the page
							setTimeout( function() {
								location.reload();
							}, 300 );

						} else {

							this_component.setState( {
								current_visual_state: 'login_code_input',
								login_error_message: data.details,
							}, () => {
								this_component.login_code_digit_6.focus();
							} );
						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							current_visual_state: 'login_code_input',
							login_error_message: this_component.props.main_component.strings.general_server_error
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					current_visual_state: 'login_code_input',
					login_error_message: this_component.props.main_component.strings.general_server_error
				});

				console.log('Fetch Error :-S', err);
			}
		);
	}

	set_validation_and_value_of_field( state_validation_variable, is_validated, state_value_variable = null, state_value = null ) {

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
	}

	validate_form( modify_state = true ) {

		var all_fields_validate = true;

		// Email field
		if ( ! this.state.email_validated ) {
			all_fields_validate = false;
		}

		if ( modify_state ) {

			this.setState( {
				form_validation_attempted: true
			} );

			if ( ! all_fields_validate ) {

				this.setState( {
					form_has_any_error: true
				});
			} else {
				this.setState( {
					form_has_any_error: false
				});
			}
		}

		return all_fields_validate;

	}

	handle_login_form_submit( event ){

		event.preventDefault();

		// Validate the fields
		var allow_form_to_be_submitted = this.validate_form( true );

		// Prevent the form submission if a field didn't validate
		if ( ! allow_form_to_be_submitted ) {
			return false;
		}

		this.request_login_email();

	}

	render_login_field( payment_field_to_render ) {

		if ( typeof this['render_' + payment_field_to_render + '_field' ] === "function" ) {

			var field_content = this['render_' + payment_field_to_render + '_field' ]();

			// If there's something to show
			if ( field_content ) {
				return (
					<div className={ 'church-tithe-wp-payment-field-container church-tithe-wp-payment-field-' + payment_field_to_render }>
					<div className={ 'church-tithe-wp-payment-field' }>
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

	render_login_code_field( digit ) {
		return(
			<input
				type="text"
				value={ this.state['login_code_digit_' + digit] }
				ref={ (input) => { this['login_code_digit_' + digit] = input; } }
				onChange={ this.handle_login_form_digit_change.bind( this, digit ) }
				onKeyUp={ this.handle_login_form_digit_backspace.bind( this, digit ) }
				onFocus={ this.handle_login_form_digit_focus.bind( this, digit ) }
			/>
		)
	}

	render_login_code_fields() {

		return (
			<React.Fragment>
				<div className="church-tithe-wp-input-instruction">{ this.props.main_component.state.unique_settings.strings.input_field_instructions.login_code.initial.instruction_message }</div>
				<div className="church-tithe-wp-login-code-fields">
					{ this.render_login_code_field( 1 ) }
					{ this.render_login_code_field( 2 ) }
					{ this.render_login_code_field( 3 ) }
					<div> - </div>
					{ this.render_login_code_field( 4 ) }
					{ this.render_login_code_field( 5 ) }
					{ this.render_login_code_field( 6 ) }
				</div>
			</React.Fragment>
		)
	}

	handle_login_form_digit_change( digit, event ) {

		var all_characters;
		var value;

		// Only allow numbers (not using the HTML5 "number" field because of the "step" arrows.
		if ( ! Number.isInteger( parseInt( event.target.value ) ) ) {
			// Only return if there is a value. Blank values (which are "not numbers") are allowed.
			if ( event.target.value ) {
				return;
			}
		}

		// If more than 1 digit was entered
		if ( event.target.value.length > 1 ) {

			value = event.target.value;

			console.log( value );

			// Spread the numbers out over all input fields. This allows for pasting of the whole number.
			all_characters = value.split("");

			this.setState( {
				login_code_digit_1: all_characters[0] ? all_characters[0] : '',
				login_code_digit_2: all_characters[1] ? all_characters[1] : '',
				login_code_digit_3: all_characters[2] ? all_characters[2] : '',
				login_code_digit_4: all_characters[3] ? all_characters[3] : '',
				login_code_digit_5: all_characters[4] ? all_characters[4] : '',
				login_code_digit_6: all_characters[5] ? all_characters[5] : '',
			}, () => {
				this.after_login_code_digit_set( digit, true );
			} );
		} else {

			this.setState( {
				['login_code_digit_' + digit]: event.target.value
			}, () => {
				this.after_login_code_digit_set( digit, false );
			} );
		}

	}

	after_login_code_digit_set( digit, multithele_characters_entered ){

		var login_code;
		var next_digit = digit + 1;

		// If there is another field to switch to, switch to it.
		if ( ! multithele_characters_entered && this.state['login_code_digit_' + digit] && this['login_code_digit_' + next_digit] ) {
			this['login_code_digit_' + next_digit].focus();

			// If there's not another field to switch to, we're at the end, and we can check the server if the code matches.
		} else {

			// Combine all of the digit values into one
			login_code = (
				this.state.login_code_digit_1 +
				this.state.login_code_digit_2 +
				this.state.login_code_digit_3 +
				this.state.login_code_digit_4 +
				this.state.login_code_digit_5 +
				this.state.login_code_digit_6
			);

			this.setState( {
				login_code: login_code
			}, () => {

				if ( 6 === login_code.length ) {
					this.attempt_to_login();
				}
			} );
		}

	}

	handle_login_form_digit_backspace( digit, event ) {

		var select_all_pressed = false;

		if ( event.keyCode !== 8 ) {
			return;
		}

		// If cntrl+a has been pushed, select and remove all
		if ( select_all_pressed ) {
			// Clear all entered values in the login code inputs
			this.setState( {
				login_code: '',
				login_code_digit_1: '',
				login_code_digit_2: '',
				login_code_digit_3: '',
				login_code_digit_4: '',
				login_code_digit_5: '',
				login_code_digit_6: '',
			}, () => {

				// Refocus on the first digit
				this.login_code_digit_1.focus();

			} );
		} else {

			// If there's still a value in this field, don't switch fields.
			if ( this.state['login_code_digit_' + digit] ) {
				return;
			}

			var previous_digit = digit - 1;

			// If there's a previous digit, focus on it
			if ( this['login_code_digit_' + previous_digit] ) {
				this['login_code_digit_' + previous_digit].focus();
			}
		}
	}

	handle_login_form_digit_focus( digit, event ) {

		// If the form is in a state of error, don't do anything upon focus.
		if ( this.state.login_error_message && document.activeElement === this.login_code_digit_6 ) {
			return;
		}

		this.setState( {
			login_error_message: null
		} );

		this.setState( {
			['login_code_digit_' + digit]: ''
		} );

	}

	render_email_field() {

		return(
			<Church_Tithe_WP_Email_Field
				main_component={ this.props.main_component }
				state_validation_variable_name={ 'email_validated' }
				state_value_variable_name={ 'email_value' }
				set_validation_and_value_of_field={ this.set_validation_and_value_of_field.bind( this ) }
				form_validation_attempted={ this.state.form_validation_attempted }
				is_validated={ this.state.email_validated }
				validate_form={ this.validate_form.bind( this ) }
				instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.email_for_login_code }
				initial_value={ this.props.main_component.state.current_transaction_info ? this.props.main_component.state.current_transaction_info.email : ''  }

				type="email"
				class_name={ 'church-tithe-wp-email' }
				placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.email_for_login_code.placeholder_text }
				name="email"
				editing_key={ 'strings/input_field_instructions/email/[current_key_here]/instruction_message' }
			/>
		)
	}

	render_login_error_field() {

		// Handle pre-attempt form errors
		if ( this.state.form_has_any_error ) {
			return(
				<div className={ 'church-tithe-wp-payment-error-message'}>{ this.props.main_component.state.unique_settings.strings.login_form_has_an_error }</div>
			)
		} else if ( this.state.login_error_message ) {
			return(
				<div className={ 'church-tithe-wp-payment-error-message'}>{ this.state.login_error_message }</div>
			)
		} else {
			return( '' )
		}
	}

	render_login_submit_button_field() {

		var button_class;
		var button_text;

		if ( 'login_form' == this.state.current_visual_state ) {
			button_class = '';
			button_text = this.props.main_component.state.unique_settings.strings.login_button_text;
		}
		if ( 'loading' == this.state.current_visual_state ) {
			button_class = ' church-tithe-wp-btn-attempting-payment';
			button_text = <Church_Tithe_WP_Spinner />;
		}
		if ( 'login_attempt_succeeded' == this.state.current_visual_state ) {
			button_class = ' church-tithe-wp-btn-success';
			button_text = <span><Church_Tithe_WP_Checkmark /></span>;
		}

		return (
			<div>
			<button type="submit" className={ 'church-tithe-wp-pay-button' + button_class }>{ button_text }</button>
			</div>
		)
	}

	get_current_view_class( views_in_question ) {

		var currently_in_view_class_name = 'church-tithe-wp-current-view';
		var hidden_class_name = 'church-tithe-wp-hidden-view';
		var current_visual_state = this.state.current_visual_state;

		// If the current visual state matches one of the view states we are getting the class for
		if( views_in_question.indexOf( current_visual_state ) != -1 ) {

			return ' ' + currently_in_view_class_name;

		} else {

			return ' ' + hidden_class_name;

		}

	}

	render() {

		return (
			<React.Fragment>
				<div className={ 'church-tithe-wp-login-form-loading' + this.get_current_view_class( [ 'loading' ] ) }>
					<Church_Tithe_WP_Spinner color_mode="church-tithe-wp-spinner-dark" />
				</div>
				<div className={ 'church-tithe-wp-login-form' + this.get_current_view_class( [ 'login_form' ] ) }>
					<form onSubmit={this.handle_login_form_submit}>
						{ this.render_login_field( 'email' ) }
						{ this.render_login_field( 'login_error' ) }
						{ this.render_login_field( 'login_submit_button') }
					</form>
				</div>
				<div className={ 'church-tithe-wp-login-code-input' + this.get_current_view_class( [ 'login_code_input' ] ) }>
						{ this.render_login_code_fields() }
						{ this.render_login_field( 'login_error' ) }
				</div>
				<div className={ 'church-tithe-wp-login-success' + this.get_current_view_class( [ 'login_attempt_succeeded' ] ) }>
					<Church_Tithe_WP_Checkmark />
				</div>
			</React.Fragment>
		);
	}
}
export default Church_Tithe_WP_Manage_Payments;
