window.MP_WP_Admin_Input_Field = class MP_WP_Admin_Input_Field extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			props_loaded: false,
			input_value: '',
			saved_status: 'saved',
			in_initial_state: true,
			validated: window[this.props.props.client_validation_callback_function]( this.props.props.default_value ),
			lightbox_open: false,
			error_code: null
		};

		this.input_delay = null;

		this.get_input_field_class = this.get_input_field_class.bind( this );
		this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
		this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
		this.set_state = this.set_state.bind( this );
	};

	componentDidMount() {

		if ( this.props.props.saved_value ) {
			this.setState( {
				props_loaded: true,
				input_value: this.props.props.saved_value
			} );
		} else {
			this.setState( {
				props_loaded: true,
				input_value: this.props.props.default_value
			} );
		}

	}

	set_state( state_key, state_value ) {

		this.setState( {
			[state_key]: state_value
		} );
	}

	save_value_to_server( value ) {

		this.setState( {
			saved_status: 'saving',
		} );

		// We'll auto save the entered tithe note into the database's transaction field via ajax every time the person stops typing for 1 second.
		var postData = new FormData();
		postData.append('mpwpadmin_relation_id', this.props.relation_id );
		postData.append('mpwpadmin_setting_id', this.props.id);
		postData.append('mpwpadmin_setting_value', value);
		postData.append('mpwpadmin_validation_callback', this.props.props.server_validation_callback_function);
		postData.append('mpwpadmin_nonce', this.props.props.nonce);

		var this_component = this;

		fetch( this.props.props.server_api_endpoint_url, {
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

							// The value was successfully saved.
							this_component.setState( {
								saved_status: 'saved',
								validated: true,
								error_code: null,
							} );


							// Pass the response up the chain to the parent component, where it will handle the data as it needs it.
							if ( this_component.props.update_context ) {
								this_component.props.update_context( data );
							}

						} else {
							console.log( data );

							// The value was note successfully saved.
							this_component.setState( {
								saved_status: 'unsaved',
								validated: false,
								error_code: data.error_code,
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

	get_current_instruction_key() {

		// If the field is empty and we are in an untouched, initial state
		if ( ! this.state.input_value && this.state.in_initial_state ) {
			return 'empty_initial';
		}

		if ( ! this.state.input_value && ! this.state.in_initial_state ) {
			return 'empty_not_initial';
		}

		// If the field is not empty, and its been validated
		if ( this.state.input_value && this.state.validated ) {
			return 'success';
		}

		// If the field is not empty, but it isn't valid
		if ( this.state.input_value && ! this.state.validated ) {
			return this.state.error_code;
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

	handle_input_change( event ) {

		// Set the validation of this field
		if ( ! window[this.props.props.client_validation_callback_function]( event.target.value ) ) {
			var is_validated = false;
		} else {
			var is_validated = true;
		}

		var old_input_value = this.state.input_value;
		var new_input_value = event.target.value;

		if ( new_input_value ) {
			new_input_value = new_input_value;
		} else {
			new_input_value = this.props.props.default_value;
		}

		this.setState( {
			in_initial_state: false,
			input_value: event.target.value,
			saved_status: 'typing',
			validated: is_validated
		} );

		// If nothing has changed since the state was last set
		if ( old_input_value == new_input_value ) {

			// Do nothing
			return false;

		} else {

			// Set up a delay which waits to save the tithe until .5 seconds after they stop typing.
			if( this.input_delay ) {
				// Clear the keypress delay if the user just typed
				clearTimeout( this.input_delay );
				this.input_delay = null;
			}

			var this_component = this;

			// (Re)-Set up the save to fire in 500ms
			this.input_delay = setTimeout( function() {
				clearTimeout( this.input_delay );
				this_component.save_value_to_server( new_input_value );
			}, 500);

		}

	};

	render_field() {

		if ( ! this.state.props_loaded ) {
			return '';
		}

		if ( this.props.props.replace_input_field_with ) {
			return this.props.props.replace_input_field_with;
		} else {

			var inputProps = {};

			if ( this.props.props.type ) {
				inputProps['type'] = this.props.props.type;
			}

			if ( this.props.class_name ) {
				inputProps['className'] = this.props.class_name + this.get_input_field_class();
			} else {
				inputProps['className'] = this.get_input_field_class();
			}

			if ( this.props.props.name ) {
				inputProps['name'] = this.props.props.name;
			}

			inputProps['onChange'] = this.handle_input_change.bind( this );

			inputProps['value'] = this.state.input_value;

			if ( this.props.props.placeholder ) {
				inputProps['placeholder'] = this.props.props.placeholder;
			}

			if ( this.props.props.step ) {
				inputProps['step'] = this.props.props.step;
			}

			if ( this.props.props.min ) {
				inputProps['min'] = this.props.props.min;
			}

			if ( this.props.props.max ) {
				inputProps['max'] = this.props.props.step;
			}

			if ( this.props.props.disabled ) {
				inputProps['disabled'] = this.props.props.disabled;
			}

			return (
				<React.Fragment>
					<input { ...inputProps } />
					<div className={ 'mpwpadmin-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
					<span className={ 'mpwpadmin-input-top-right-area' }>
						{ this.render_help_button() }
						<span className={ 'mpwpadmin-input-saved-status ' + this.state.saved_status }>{ this.state.saved_status }</span>
					</span>
				</React.Fragment>
			)
		}
	}

	render_help_button() {

		if ( ! this.props.props.help_text ) {
			return '';
		}

		return(
			<React.Fragment>
				<span className={ 'mpwpadmin-input-help-link' } onClick={ this.toggle_help_lightbox.bind( this ) }>help!</span>
				<span className={ 'mpwpadmin-input-separator' }> | </span>
			</React.Fragment>
		);

	}

	toggle_help_lightbox() {

		this.props.main_component.set_all_current_visual_states( false, {
			[this.props.slug]: {}
		} );

	}

	render(){
		if ( ! this.props.props.help_text ) {
			var help_lightbox_output = null;
		} else if ( this.props.props.help_text.react_component ) {

			var DynamicReactComponent = eval( this.props.props.help_text.react_component );
			var dynamic_react_component = <DynamicReactComponent
				main_component={ this.props.main_component }
				data={ this.props.props.help_text.component_data }
			/>

			var help_lightbox_output = <MP_WP_Admin_Lightbox
				main_component={ this.props.main_component }
				slug={ this.props.slug }
				title={ this.props.props.help_text.title }
				body={ this.props.props.help_text.body }
				mode={ dynamic_react_component ? 'custom_react_component' : 'default' }
				custom_react_component={ dynamic_react_component }
			/>

		} else {
			var help_lightbox_output = 	<MP_WP_Admin_Lightbox
					main_component={ this.props.main_component }
					slug={ this.props.slug }
					title={ this.props.props.help_text.title }
					body={ this.props.props.help_text.body }
				/>;
		}

		return(
			<div className={ 'mpwpadmin-input-component-container' }>
			<label>
			{ this.render_field() }
			</label>

			{ help_lightbox_output }

			</div>
		)
	}

};
