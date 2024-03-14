window.Church_Tithe_WP_Terms_Field = class Church_Tithe_WP_Terms_Field extends React.Component {

    constructor( props ) {
        super(props);

        this.state= {
            terms_checked: null,
            terms_are_visible: false,
        };

        this.get_input_field_class = this.get_input_field_class.bind( this );
        this.get_input_instruction_class = this.get_input_instruction_class.bind( this );
        this.get_input_instruction_message = this.get_input_instruction_message.bind( this );
        this.get_terms_visibility = this.get_terms_visibility.bind( this );
    };

    componentDidMount() {

        // If this checkbox was previously set as validated in the containing form, set the default to be checked
        if ( this.props.is_validated && this.props.form_validation_attempted ) {
            this.setState( {
                terms_checked: true
            } );
        }
    }

    dangerously_set_terms_body() {
        // The terms are not user input, and thus they do not pose a security risk. They do contain HTML from WordPress though, which is why we do this
        return { __html: this.props.terms_body };
    }

    get_current_instruction_key() {

        // Handle the instruction differently when the form containing this field has been submitted
        if ( this.props.form_validation_attempted ) {

            if ( this.props.is_validated ) {
                return 'checked';
            } else {
                return 'unchecked';
            }

        } else {

            // If the form containing this field has not yet been submitted
            if ( null == this.state.terms_checked ) {
                return 'initial';
            }
            if ( this.state.terms_checked ) {
                return 'checked';
            }
            if ( ! this.state.terms_checked || ! this.props.is_validated) {
                return 'unchecked';
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

    get_terms_visibility() {

        if( this.state.terms_are_visible ) {
            return '';
        } else {
            return 'hidden';
        }
    }

    toggle_full_terms() {

        if ( ! this.state.terms_are_visible ) {
            this.setState( {
                terms_are_visible: true
            } );
        } else {
            this.setState( {
                terms_are_visible: false
            } );
        }
    }

    handle_terms_change( event ) {

        var terms_checked;

        // This will toggle the privacy policy state
        if ( this.state.terms_checked ) {
            terms_checked = false;
        } else {
            terms_checked = true;
        }

        // Pass the validation status back to the parent.
        this.props.set_validation_and_value_of_field(
            this.props.state_validation_variable_name,
            terms_checked,
        );

        if ( this.props.form_validation_attempted ) {
            var this_component = this;
            // Wait for the state to be set in the parent
            setTimeout( function() {
                this_component.props.validate_form( true );
            }, 10 );
        }

        this.setState( {
            terms_checked: terms_checked
        } );

    };

    render(){

        var inputProps = {};

        // Set the initial checked state of this checkbox
        if ( null == this.state.terms_checked ) {
            // If this checkbox was previously set as validated in the containing form, set the default to be checked
            if ( this.props.is_validated && this.props.form_validation_attempted ) {
                inputProps['defaultChecked'] = 'checked';
            }
        }

        inputProps['onChange'] = this.handle_terms_change.bind( this );

        if ( this.props.class_name ) {
            inputProps['className'] = this.props.class_name + this.get_input_field_class();
        } else {
            inputProps['className'] = this.get_input_field_class();
        }

        if ( this.props.name ) {
            inputProps['name'] = this.props.name;
        }

        if ( this.props.placeholder ) {
            inputProps['placeholder'] = this.props.placeholder;
        }

        if ( this.props.defaultValue ) {
            inputProps['defaultValue'] = this.props.defaultValue;
        }

        if ( this.props.disabled ) {
            inputProps['disabled'] = this.props.disabled;
        }

        return(
            <div>

                <div hidden={ this.get_terms_visibility() } className={ 'church-tithe-wp-expandable-terms' }>
                    <div className={ 'church-tithe-wp-terms-title' }>{ this.props.terms_title }</div>
                    <div className={ 'church-tithe-wp-terms-body' } dangerouslySetInnerHTML={ this.dangerously_set_terms_body() } />
                </div>

                <label>
                    <span className={ 'church-tithe-wp-input-instruction ' + this.get_input_instruction_class() }>
                        <input type="checkbox" { ...inputProps } />{ this.get_input_instruction_message() + ' '  }
                    </span>
                </label>

                <a className="church-tithe-wp-view-terms-button" onClick={ this.toggle_full_terms.bind( this ) }>{ '(' + this.props.terms_show_text + ')' }</a>

            </div>
        )
    }

};
export default Church_Tithe_WP_Terms_Field;
