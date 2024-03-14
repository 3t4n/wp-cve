window.Church_Tithe_WP_Input_Field = class Church_Tithe_WP_Input_Field extends React.Component {

    constructor( props ) {
        super(props);

        this.state= {
            input_value: '',
        };

        this.get_input_field_class = this.get_input_field_class.bind( this );
        this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
        this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
    };

		componentDidMount() {

			if ( this.props.initial_value ) {
				this.setState( {
					initial_input_value: this.props.initial_value,
					input_value: this.props.initial_value
				}, () => {
					// Validate that the field has a value
					if ( ! this.state.input_value ) {
						var is_validated = false;
					} else {
						var is_validated = true;
					}

					// Pass the validation status back to the parent.
					this.props.set_validation_and_value_of_field(
						this.props.state_validation_variable_name,
						is_validated,
						this.props.state_value_variable_name,
						this.state.input_value
					);
				} );
			}
		}

		componentDidUpdate() {

			// If the initial input value has changed
			if ( this.props.initial_value && this.props.initial_value !== this.state.initial_input_value ) {
				this.setState( {
					initial_input_value: this.props.initial_value,
					input_value: this.props.initial_value
				}, () => {
					// Validate that the field has a value
					if ( ! this.state.input_value ) {
						var is_validated = false;
					} else {
						var is_validated = true;
					}

					// Pass the validation status back to the parent.
					this.props.set_validation_and_value_of_field(
						this.props.state_validation_variable_name,
						is_validated,
						this.props.state_value_variable_name,
						this.state.input_value
					);

				} );
			}
		}

    get_current_instruction_key() {

        // Handle the instruction only if the form containing this field has been submitted
        if ( this.props.form_validation_attempted ) {

            // If the form containing this field has been submitted
            if ( ! this.state.input_value ) {
                return 'empty';
            }
            if ( this.state.input_value ) {
                return 'success';
            }

        } else {
            // If the form containing this field has not yet been submitted
            if ( ! this.state.input_value ) {
                return 'initial';
            }
            if ( this.state.input_value ) {
                return 'success';
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
        }
    };

    handle_input_change( event ) {

        // Set the validation of this field
        if ( ! event.target.value ) {
            var is_validated = false;
        } else {
            var is_validated = true;
        }

        // Pass the validation status back to the parent.
        this.props.set_validation_and_value_of_field(
            this.props.state_validation_variable_name,
            is_validated,
            this.props.state_value_variable_name,
            event.target.value
        );

        if ( this.props.form_validation_attempted ) {
            var this_component = this;
            // Wait for the state to be set in the parent
            setTimeout( function() {
                this_component.props.validate_form( true );
            }, 10 );
        }

        this.setState( {
            input_value: event.target.value
        } );

    };

    render(){

        var inputProps = {};

        if ( this.props.type ) {
            inputProps['type'] = this.props.type;
        }

        if ( this.props.class_name ) {
            inputProps['className'] = this.props.class_name + this.get_input_field_class();
        } else {
            inputProps['className'] = this.get_input_field_class();
        }

        if ( this.props.name ) {
            inputProps['name'] = this.props.name;
        }

        inputProps['onChange'] = this.handle_input_change.bind( this );

        if ( this.props.placeholder ) {
            inputProps['placeholder'] = this.props.placeholder;
        }

        inputProps['value'] = this.state.input_value;

        if ( this.props.step ) {
            inputProps['step'] = this.props.step;
        }

        if ( this.props.disabled ) {
            inputProps['disabled'] = this.props.disabled;
        }

        return(
            <div>
                <label>
                    <div className={ 'church-tithe-wp-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
                    { this.props.output_before_field }<input { ...inputProps } />{ this.props.after_before_field }
                </label>

            </div>
        )
    }

};

window.Church_Tithe_WP_Input_Field_Instruction = class Church_Tithe_WP_Input_Field_Instruction extends React.Component {

    constructor( props ) {
        super(props);

        this.get_input_field_class = this.get_input_field_class.bind( this );
        this.set_current_input_field_instruction = this.set_current_input_field_instruction.bind( this );
        this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
        this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
    };

    set_current_input_field_instruction( field_name, instruction_name ) {

        this.state.input_fields[field_name].current_instruction = value;

        this.setState( {
            input_fields: this.state.input_fields
        } );
    };

    get_input_instruction_class() {

        // Get the current instruction for this field
        var current_instruction = this.props.current_instruction;

        if ( this.props.instruction_codes[current_instruction] ) {
            if ( 'error' == this.props.instruction_codes[current_instruction].instruction_type ) {
                return ' church-tithe-wp-instruction-error';
            }
        }

        return '';

    };

    get_input_field_class() {

        // Get the current instruction for this field
        var current_instruction = this.props.current_instruction;

        if ( this.props.instruction_codes[current_instruction] ) {
            if ( 'error' == this.props.instruction_codes[current_instruction].instruction_type ) {
                return ' church-tithe-wp-input-error';
            }
        }

        return '';

    };

    get_input_instruction_message() {

        // Get the current instruction for this field
        var current_instruction = this.props.current_instruction;

        if ( this.props.instruction_codes[current_instruction] ) {
            return this.props.instruction_codes[current_instruction].instruction_message;
        }
    };

    render(){
        return(
            <div className={ 'church-tithe-wp-input-instruction' + this.get_input_instruction_class() }>{ this.get_input_instruction_message() }</div>
        )
    }

};
export default Church_Tithe_WP_Input_Field;
