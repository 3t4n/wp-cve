window.Church_Tithe_WP_Stripe_Connect_Field = class Church_Tithe_WP_Stripe_Connect_Field extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			props_loaded: false,
			saved_status: 'saved',
			in_initial_state: true,
			lightbox_open: false,
			error_code: null,
			stripe_is_connected: null,
			pending: false
		};

		this.input_delay = null;

		this.get_input_field_class = this.get_input_field_class.bind( this );
		this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
		this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
		this.set_state = this.set_state.bind( this );
	};

	componentDidMount() {

		this.setState( {
			props_loaded: true,
			stripe_is_connected: this.props.props.stripe_is_connected,
		} );

	}

	set_state( state_key, state_value ) {

		this.setState( {
			[state_key]: state_value
		} );
	}

	get_current_instruction_key() {

		if ( this.state.stripe_is_connected ) {
			return 'stripe_connected';
		}

		if ( ! this.state.stripe_is_connected ) {
			return 'connect_stripe';
		}

		return 'error';
	}

	get_input_instruction_class() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.props.instruction_codes[current_instruction] ) {
			if ( 'initial' == this.props.props.instruction_codes[current_instruction].instruction_type ) {
				return ' mpwpadmin-instruction-error';
			}
			if ( 'error' == this.props.props.instruction_codes[current_instruction].instruction_type ) {
				return ' mpwpadmin-instruction-error';
			}
		}

		return '';

	};

	get_input_field_class() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.props.instruction_codes[current_instruction] ) {
			if ( 'success' == this.props.props.instruction_codes[current_instruction].instruction_type ) {
				return ' mpwpadmin-input-success';
			}
			if ( 'error' == this.props.props.instruction_codes[current_instruction].instruction_type ) {
				return ' mpwpadmin-input-error';
			}
			if ( 'initial' == this.props.props.instruction_codes[current_instruction].instruction_type ) {
				return ' mpwpadmin-input-initial';
			}
		}

		return ' mpwpadmin-input-initial';

	};

	get_input_instruction_message() {

		// Get the current instruction for this field
		var current_instruction = this.get_current_instruction_key();

		if ( this.props.props.instruction_codes[current_instruction] ) {
			return this.props.props.instruction_codes[current_instruction].instruction_message;
		}
	};

	get_connect_button_text() {

		if ( this.state.stripe_is_connected ) {
			return this.props.props.button_strings.connected_text;
		}
		if ( ! this.state.stripe_is_connected ) {
			return this.props.props.button_strings.connect_text;
		}

	}

	disconnect_stripe() {

		this.setState( {
			pending: true,
		} );

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('mpwpadmin_relation_id', this.props.relation_id );
		postData.append('mpwpadmin_stripe_disconnect', this.props.id);
		postData.append('mpwpadmin_stripe_disconnect_mode', this.props.props.mode);
		postData.append('mpwpadmin_nonce_id', this.props.props.stripe_disconnect_nonce_id);
		postData.append('mpwpadmin_nonce', this.props.props.stripe_disconnect_nonce);

		var this_component = this;

		fetch( this.props.props.stripe_disconnect_url, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
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

							// The user was successfully disconnected from Stripe connect
							this_component.setState( {
								stripe_is_connected: false,
								pending: false,
							} );


							// Pass the response up the chain to the parent component, where it will handle the data as it needs it.
							if ( this_component.props.update_context ) {
								this_component.props.update_context( data ).then( function( result ) {
									console.log( result );
								} );
							}

						} else {

							// The user was not successfully disconnected from Stripe connect, but most likely they accidentally disconnected twice somehow. For now we'll assume the disconnection was successful anyway.
							this_component.setState( {
								stripe_is_connected: false,
								pending: false,
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

	render_field() {

		if ( ! this.state.props_loaded ) {
			return '';
		}

		if ( this.props.props.replace_input_field_with ) {
			return this.props.props.replace_input_field_with;
		} else {

			return (
				<React.Fragment>

				<div className={ 'mpwpwpadmin-link-container' }>

				{ (() => {

					if ( ! this.state.pending ) {
						return (
							<a className='churchtithewp-stripe-connect' href={ this.props.props.stripe_connect_url }>
							<span>{ this.get_connect_button_text() }</span>
							</a>
						);
					}

				})() }

				{ (() => {

					if ( this.state.stripe_is_connected && ! this.state.pending ) {
						return (
							<button className='churchtithewp-stripe-connect' onClick={ this.disconnect_stripe.bind( this ) }>
							<span>{ this.props.props.button_strings.disconnect_text }</span>
							</button>
						);
					}

				})() }

				{ (() => {

					if ( this.state.pending ) {
						return (
							<MP_WP_Admin_Spinner />
						);
					}

				})() }

				{(() => {
					if ( this.state.stripe_is_connected && ! this.state.pending && this.props.props.stripe_account_name ) {
						return (
							<React.Fragment>
								<div className="church-tithe-wp-stripe-account-name-container">
									<span className="church-tithe-wp-stripe-account-label">{ this.props.props.stripe_account_label }</span>
									<span> </span>
									<span className="church-tithe-wp-stripe-account-name">{ this.props.props.stripe_account_name }</span>
								</div>
							</React.Fragment>
						);
					}
				})()}

				</div>

				<div className={ 'mpwpadmin-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
				<span className={ 'mpwpadmin-input-top-right-area' }>
				<span className={ 'mpwpadmin-input-help-link' } onClick={ this.toggle_help_lightbox.bind( this ) }>help!</span>
				</span>
				</React.Fragment>
			)
		}
	}

	toggle_help_lightbox() {

		this.props.main_component.set_all_current_visual_states( false, {
			[this.props.slug]: {}
		} );

	}

	render(){

		if ( this.props.props.help_text.react_component ) {
			var DynamicReactComponent = eval( this.props.props.help_text.react_component );
			var dynamic_react_component = <DynamicReactComponent
				main_component={ this.props.main_component }
				data={ this.props.props.help_text.component_data }
			/>
		} else {
			var dynamic_react_component = null;
		}

		return(
			<div className={ 'mpwpadmin-input-component-container' }>
			{ this.render_field() }

			<MP_WP_Admin_Lightbox
				main_component={ this.props.main_component }
				slug={ this.props.slug }
				title={ this.props.props.help_text.title }
				body={ this.props.props.help_text.body }
				mode={ dynamic_react_component ? 'custom_react_component' : 'default' }
				custom_react_component={ dynamic_react_component }
			/>

			</div>
		)
	}

};
