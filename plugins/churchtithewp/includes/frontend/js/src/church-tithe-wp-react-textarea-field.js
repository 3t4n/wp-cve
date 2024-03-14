window.Church_Tithe_WP_TextArea_Field = class Church_Tithe_WP_TextArea_Field extends React.Component {

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
			this.setState( {
				input_value: this.props.value
			} );
		}

    get_current_instruction_key() {

        // If the form containing this field has not yet been submitted
        if ( ! this.state.input_value && this.props.is_validated ) {
            return 'initial';
        }
				if ( this.props.value == this.state.input_value && ! this.props.is_validated ) {
						return 'initial';
				}
        if ( this.state.input_value  && 'typing' == this.props.is_validated ) {
            return 'initial';
        }
        if ( this.state.input_value  && 'saving' == this.props.is_validated ) {
            return 'saving';
        }
        if ( this.state.input_value  && this.props.is_validated ) {
            return 'success';
        }
        if ( this.state.input_value  && ! this.props.is_validated ) {
            return 'error';
        }

        return 'initial';
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
                    <textarea { ...inputProps } />
                </label>

            </div>
        )
    }

};
export default Church_Tithe_WP_TextArea_Field;
