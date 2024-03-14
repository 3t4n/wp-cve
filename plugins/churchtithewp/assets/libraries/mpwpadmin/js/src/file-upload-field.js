window.MP_WP_Admin_File_Upload_Field = class MP_WP_Admin_File_Upload_Field extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			props_loaded: false,
			input_value: '',
			saved_status: 'saved',
			in_initial_state: true,
			validated: window[this.props.props.client_validation_callback_function]( this.props.props.default_value ),
			lightbox_open: false,
			error_code: null,
			file_preview: false,
			use_wp_media_dialog: true
		};

		this.input_delay = null;

		this.get_input_field_class = this.get_input_field_class.bind( this );
		this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
		this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
		this.set_state = this.set_state.bind( this );

		this.fileInput = React.createRef();
	};

	componentDidMount() {

		if ( this.props.props.saved_value ) {
			this.setState( {
				props_loaded: true,
				file_preview: this.props.props.saved_value,
				input_value: this.props.props.saved_value,
			} );
		} else {
			this.setState( {
				props_loaded: true,
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

		// Format the data that we'll send to the server
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
								unique_instruction_message: null
							} );


							// Pass the response up the chain to the parent component, where it will handle the data as it needs it.
							if ( this_component.props.update_context ) {
								this_component.props.update_context( data ).then( function( result ) {
									//console.log( result );
								} );
							}

						} else {
							console.log( data );

							if ( data.unique_instruction_message ) {
								// The value was not successfully saved.
								this_component.setState( {
									saved_status: 'unsaved',
									validated: false,
									error_code: data.error_code,
									unique_instruction_message: data.details
								} );
							} else {
								// The value was not successfully saved.
								this_component.setState( {
									saved_status: 'unsaved',
									validated: data.error_code,
									error_code: 'error',
								} );
							}
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

		if ( this.state.unique_instruction_message ) {
			return this.state.unique_instruction_message;
		}

		if ( this.props.props.instruction_codes[current_instruction] ) {
			return this.props.props.instruction_codes[current_instruction].instruction_message;
		}
	};

	remove_file( event ) {
		this.setState( {
			validated: true,
			input_value: '',
			error_code: null,
			file_preview: false
		} );

		this.save_value_to_server( '' );
	}

	handle_input_change( event ) {

		const file_to_upload = this.fileInput.current.files[0];

		// If they hit "cancel" in the file upload dialog
		if ( ! file_to_upload ) {
			return false;
		}

		// Set the validation of this field
		if ( ! window[this.props.props.client_validation_callback_function]( file_to_upload ) ) {
			this.setState( {
				validated: false,
				input_value: file_to_upload,
				saved_status: 'unsaved',
				error_code: 'not_an_image',
				file_preview: false
			} );

			return false;
		} else {
			var is_validated = true;
		}

		// If a file has been selected, try to create a preview of it
		if ( file_to_upload ) {
			var file_preview = URL.createObjectURL( file_to_upload );
		} else {
			var file_preview = '';
		}

		this.setState( {
			in_initial_state: false,
			input_value: file_to_upload,
			saved_status: 'typing',
			validated: is_validated,
			file_preview: file_preview
		} );

		// Set up a delay which waits to save the file until the user is done typing (if they happen to be typing)
		if( this.input_delay ) {
			// Clear the keypress delay if the user just typed
			clearTimeout( this.input_delay );
			this.input_delay = null;
		}

		// (Re)-Set up the save to fire in 500ms
		this.input_delay = setTimeout( () => {

			clearTimeout( this.input_delay );

			this.save_value_to_server( file_to_upload );

		}, 500);

	};

	render_field() {

		if ( ! this.state.props_loaded ) {
			return '';
		}

		if ( this.props.props.replace_input_field_with ) {
			return this.props.props.replace_input_field_with;
		} else {

			var inputProps = {};

			if ( this.state.use_wp_media_dialog ) {
				var wp_media_dialog_class = 'mpwpadmin-wp-media-dialog-input';
			} else {
				var wp_media_dialog_class = '';
			}

			if ( this.props.class_name ) {
				inputProps['className'] = this.props.class_name + this.get_input_field_class() + ' ' + wp_media_dialog_class;
			} else {
				inputProps['className'] = this.get_input_field_class();
			}

			if ( this.props.props.name ) {
				inputProps['name'] = this.props.props.name;
			}

			inputProps['onChange'] = this.handle_input_change.bind( this );

			if ( this.state.use_wp_media_dialog ) {
				inputProps['type'] = 'text';
				inputProps['value'] = this.state.input_value;
			} else {
				inputProps['type'] = 'file';
			}

			inputProps['ref'] = this.fileInput;

			inputProps['title'] = " ";

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
					{ (() => {
						if ( this.state.file_preview ) {
							return ( <div className="mpwpadmin-file-preview-container" style={ {
								backgroundImage: 'url(' + this.state.file_preview + ')',
							} } ></div> );
						}
						})()
					}
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

	wp_open_media_dialog() {

		// create and open new file frame
		var mp_core_file_frame = wp.media({
			//Title of media manager frame
			title: 'Select an item',
			button: {
				//Button text
				text: 'Use Item'
			},
			//Do not allow multithele files, if you want multithele, set true
			multithele: false,
		});

		var this_component = this;

		//callback for selected image
		mp_core_file_frame.on('select', function() {

			var selection = mp_core_file_frame.state().get('selection');

			selection.map(function(attachment) {

				attachment = attachment.toJSON();

				//if this is an image, display the thumbnail above the upload button
				var ext = attachment.url.split('.').pop();

				// Set the validation of this field
				if ( ! window[this_component.props.props.client_validation_callback_function]( attachment.url ) ) {

					this_component.setState( {
						validated: false,
						input_value: attachment.url,
						saved_status: 'unsaved',
						error_code: 'invalid_image',
						file_preview: false
					} );

					return false;

				} else {

					this_component.setState( {
						validated: true,
						input_value: attachment.url,
						error_code: null,
						file_preview: attachment.url
					}, () => {


						this_component.save_value_to_server( attachment.url );
					} );
				}

			});

		});

		// open file frame
		mp_core_file_frame.open();
	}

	toggle_help_lightbox() {

		this.props.main_component.set_all_current_visual_states( false, {
			[this.props.slug]: {}
		} );

	}

	render_file_upload_control_buttons() {

		return (
			<div className="mpwpadmin-file-upload-control-button-container">
				{ (() => {
					if ( this.state.use_wp_media_dialog ) {
						return(
							<button className="mpwpadmin-file-upload-upload-button button" onClick={ this.wp_open_media_dialog.bind( this ) } >{ this.props.props.upload_file_text }</button>
						);
					}
				})() }

				{ (() => {
					if ( this.state.file_preview ) {
						return ( <button className="mpwpadmin-file-upload-remove-button button" onClick={ this.remove_file.bind( this ) }>{ this.props.props.remove_file_text }</button> );
					}
				})() }
			</div>
		);
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
			<div className={ 'mpwpadmin-input-component-container mpwpadmin-file-upload-component-container' + (() => {
					if ( this.state.file_preview ) {
						return ( ' mpwpadmin-has-file-preview' );
					} else {
						return '';
					}
				})()
			}>

			<div className={ 'mpwpadmin-input-label-container'}>
			<label>
			{ this.render_field() }
			</label>
			</div>

			{ this.render_file_upload_control_buttons() }

			{ help_lightbox_output }

	</div>
	)
	}

};
