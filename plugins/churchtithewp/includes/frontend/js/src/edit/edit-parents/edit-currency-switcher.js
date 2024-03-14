window.ChurchTitheWPEditDefaultAmountAndCurrency = class ChurchTitheWPEditDefaultAmountAndCurrency extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			currency_input_value: '',
			amount_input_value: ''
		};

		this.textInput = React.createRef();
	}

	componentDidUpdate() {
		if ( this.state.is_focused !== this.props.payment_box.state.edit_currency_is_focused ) {
			this.props.payment_box.setState( {
				edit_currency_is_focused: this.state.is_focused
			} );
		}
	}

	handleAmountChange( event ) {

		// Pass the value to the parent component's handler.
		this.props.payment_box.handleAmountChange( event ).then( () => {

			// If we are focused (or in "Editing mode" for this element), pass the value to the block where it is saved to the form.
			if ( this.state.is_focused ) {
				church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.amount_editing_key, this.props.payment_box.state.tithe_amount, true );
			}

		} );
	}

	set_focus( should_be_focused, context, event ) {
		event.preventDefault();
		this.setState( {
			is_focused: should_be_focused
		});
	}

	handleBlur( event ) {
		this.setState( {
			is_focused: false
		});
	}

	render_edit_and_done_buttons() {
		return(
			<div className="church-tithe-wp-edit-button-container">
				{ (() => {
						if ( ! this.state.is_focused ) {
							return(
								<button
									className="button church-tithe-wp-edit-button"
									onClick={ this.set_focus.bind( this, true, 'edit' ) }
								>
								{
									church_tithe_wp_editing_strings.edit
								}
								</button>
							)
						}
					})()
				}
				{ (() => {
						if ( this.state.is_focused ) {
							return (
								<button
									className="button church-tithe-wp-view-button"
									onClick={ this.set_focus.bind( this, false, 'view' ) }
								>
								{
									church_tithe_wp_editing_strings.view
								}
								</button>
							)
						}
					})()
				}
			</div>
		);
	}

	render_amount_and_currency_fields() {

		return (
			<React.Fragment>
				<Church_Tithe_WP_Input_Field_Instruction
					main_component={ this.props.main_component }
					current_instruction={ this.props.payment_box.state.input_fields_tithe_amount_current_instruction }
					instruction_codes={ this.props.main_component.state.unique_settings.strings.input_field_instructions.tithe_amount }
					editing_key={ 'strings/input_field_instructions/tithe_amount/' + this.props.payment_box.state.input_fields_tithe_amount_current_instruction + '/instruction_message' }
					is_edit_child={ true }
					is_focused={ this.state.is_focused }
				/>
				<div className={ 'church-tithe-wp-amount-container' +  ( () => {
					if ( this.props.payment_box.state.currency_search_visible ) {
						return ' currency-search-visible';
					} else {
						return '';
					}
				})()}>
					<div className={ 'church-tithe-wp-tithe-currency-symbol' }>{ this.props.payment_box.state.verified_currency_symbol }</div>
					<div className={ 'church-tithe-wp-tithe-amount-input-container' }>
						<label>
							<input
								disabled={ this.props.payment_box.get_disabled_status( [ 'credit_card', 'payment_request', 'free_file_download' ] ) }
								type="number"
								min={ 1 }
								step={ this.props.payment_box.get_amount_field_step_format() }
								className={ 'church-tithe-wp-tithe-amount-input' }
								placeholder={ this.props.main_component.state.unique_settings.strings.input_field_instructions.tithe_amount.placeholder_text }
								name="tithe-amount"
								onChange={ this.handleAmountChange.bind( this ) }
								value={ this.props.payment_box.get_visual_amount_for_input_field() }
								lang={ this.props.main_component.state.dynamic_settings.locale.replace("_", "-") }
							/>
						</label>
					</div>
					<div className={ 'church-tithe-wp-currency-switcher' }>
						{ this.props.payment_box.render_currency_switcher() }
					</div>
				</div>
			</React.Fragment>
		)

	}

	get_in_focus_class() {
		if ( this.state.is_focused ) {
			return ' church-tithe-wp-edit-area-in-focus';
		} else {
			return ' church-tithe-wp-edit-area-not-in-focus';
		}
	}

	render() {

		// If we are in editing mode...
		if ( this.props.main_component.state.editing_mode ) {

			return (
				<div className={ 'church-tithe-wp-edit-container' + this.get_in_focus_class() }>
					{ this.render_edit_and_done_buttons() }
					{ this.render_amount_and_currency_fields() }
				</div>
			);

			// If we are not in editing mode, show nothing here.
		} else {
			return (
				this.render_amount_and_currency_fields()
			);
		}
	}

}
export default ChurchTitheWPEditDefaultAmountAndCurrency;
