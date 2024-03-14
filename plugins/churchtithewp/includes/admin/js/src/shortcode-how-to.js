window.Tithe_Jar_Shortcode_How_To = class Tithe_Jar_Shortcode_How_To extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			form_mode: this.props.section_info.component_data.form_mode.default,
			open_style: this.props.section_info.component_data.open_style.default,
			link_text: this.props.section_info.component_data.link_text.default // "Leave a tithe"
		}

		this.copy_text_field = React.createRef();

	};

	copy_text( ref_to_copy ) {
		ref_to_copy.select();
		document.execCommand('copy');
	}

	render_copy_text_element( text_field_data, ref_to_use ) {

		return(
			<React.Fragment>
				<div className={ 'mpwpadmin-input-component-container' } onClick={ this.copy_text.bind( this, this[ref_to_use] ) }>
					<div className="mpwpadmin-input-instruction">{ text_field_data.title }</div>
					<input
						type="text"
						readOnly
						ref={(input) => this[ref_to_use] = input}
						value={ this.get_shortcode_text() }
					/>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<button onClick={ this.copy_text.bind( this, this[ref_to_use] ) } className="button">{ text_field_data.button_text }</button>
					</div>
				</div>
			</React.Fragment>
		)
	}

	handle_radio_change( name, event ) {

		if ( 'form_mode' == name ) {
			this.handle_form_mode_change( event );
		}

		if ( 'open_style' == name ) {
			this.handle_open_style_change( event );
		}
	}

	handle_form_mode_change( event ) {
		this.setState( {
			form_mode: event.target.value
		} );
	}

	handle_open_style_change( event ) {
		this.setState( {
			open_style: event.target.value
		} );
	}

	handle_link_text_change( event ) {
		this.setState( {
			link_text: event.target.value
		} );
	}

	get_shortcode_text() {

		var shortcode_text = this.props.section_info.component_data.strings.default_shortcode_text;

		if ( 'form' == this.state.form_mode ) {
			shortcode_text = '[churchtithewp]';
		}

		if ( 'button' == this.state.form_mode ) {
			shortcode_text = '[churchtithewp mode="button" link_text="' + this.state.link_text + '" open_style="' + this.state.open_style + '"]';
		}

		if ( 'text_link' == this.state.form_mode ) {
			shortcode_text = '[churchtithewp mode="text_link" link_text="' + this.state.link_text + '" open_style="' + this.state.open_style + '"]';
		}

		return shortcode_text;
	}

	render_radio_buttons( radio_buttons, name, current_value ) {

		var mapper = [];

		// This lets us loop through the object
		for (var key in radio_buttons) {

			mapper.push(
				<div key={ key } className={ "church-tithe-wp-radio-button-container" + ( key == this.state.form_mode ? ' church-tithe-wp-radio-current' : '' ) }>
					<label>
						<div className="church-tithe-wp-radio-button-outer">
							<div className="church-tithe-wp-radio-button-inner">
								<input
									type={ 'radio' }
									value={ key }
									name={ name }
									checked={ key == current_value ? true : false }
									onChange={ this.handle_radio_change.bind( this, name ) }
								/>
								<div className="church-tithe-wp-radio-button-after">
									{ radio_buttons[key].after_output }
								</div>
							</div>
						</div>
					</label>
				</div>
			)

		}

		// This lets us output the inputs one by one
		return (
			<div className="church-tithe-wp-radio-buttons-container">
			{
				mapper.map((radio_buttons, index) => {
					return radio_buttons;
				})
			}
			</div>
		)

	}

	render_mode_field() {
		return(
			<div className={ 'mpwpadmin-lightbox-action-area' }>
				<div className="mpwpadmin-input-component-container mpwpwadmin-radio-container">
					<div className="mpwpadmin-input-instruction">{ this.props.section_info.component_data.form_mode.title }</div>
					{ this.render_radio_buttons( this.props.section_info.component_data.form_mode.radio_options, 'form_mode', this.state.form_mode ) }
				</div>
			</div>
		);
	}

	render_link_text_setting() {

		if (
			'button' == this.state.form_mode ||
			'text_link' == this.state.form_mode
		) {

			return(
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<div className="mpwpadmin-input-component-container">
						<div className="mpwpadmin-input-instruction">{ this.props.section_info.component_data.link_text.title }</div>
						<input type="text" value={ this.state.link_text } onChange={ this.handle_link_text_change.bind( this ) } />
					</div>
				</div>
			);
		}
	}

	render_open_style_field() {
		if (
			'button' == this.state.form_mode ||
			'text_link' == this.state.form_mode
		) {

			return(
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<div className="mpwpadmin-input-component-container mpwpwadmin-radio-container">
						<div className="mpwpadmin-input-instruction">{ this.props.section_info.component_data.open_style.title }</div>
						{ this.render_radio_buttons( this.props.section_info.component_data.open_style.radio_options, 'open_style', this.state.open_style ) }
					</div>
				</div>
			);
		}
	}

	render_lighbox_content() {

		return(
			<div>
			<div className={ 'mpwpadmin-lightbox-icon-container' }>
			<div className="mpwpadmin-lightbox-icon">
				<img src={ this.props.section_info.icon } />
			</div>
			</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.section_info.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.section_info.component_data.strings.description }
				</div>

				{ this.render_mode_field() }

				{ this.render_open_style_field() }

				{ this.render_link_text_setting() }

				<div className={ 'mpwpadmin-lightbox-action-area' }>
					{ this.render_copy_text_element( this.props.section_info.component_data.copy_shortcode, 'copy_text_field' ) }
				</div>
			</div>
		);
	}

	render(){

		return (
			<React.Fragment>

				<button className="button" onClick={ this.props.main_component.set_all_current_visual_states.bind( this, false, { church_tithe_wp_shortcode_how_to: {} } ) }>How to show a tithe form</button>
				<MP_WP_Admin_Lightbox
					main_component={ this.props.main_component }
					slug={ 'church_tithe_wp_shortcode_how_to' }
					mode={ 'custom_react_component' }
					custom_react_component={ this.render_lighbox_content() }
				/>

			</React.Fragment>
		);

	}

};
