window.ChurchTitheWPEditOpenStyle = class ChurchTitheWPEditOpenStyle extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			form_mode: '',
			button_text: '',
			open_style: ''
		};

		this.textInput = React.createRef();
	}

	componentDidMount() {
		this.setState( {
			form_mode: this.props.main_component.state.unique_settings.mode,
			button_text: this.props.main_component.state.unique_settings.strings.link_text,
			open_style: this.props.main_component.state.unique_settings.open_style,
		} );
	}

	handle_form_mode_change( event ) {

		this.setState( {
			form_mode: event.target.value
		}, () => {
			church_tithe_wp_pass_value_to_block( this.props.main_component, 'mode', this.state.form_mode, false );
		} );
	}

	handle_open_style_change( event ) {

		this.setState( {
			open_style: event.target.value
		}, () => {
			church_tithe_wp_pass_value_to_block( this.props.main_component, 'open_style', this.state.open_style, false );
		} );
	}

	handle_button_text_change( event ) {

		this.setState( {
			button_text: event.target.value
		}, () => {
			church_tithe_wp_pass_value_to_block( this.props.main_component, 'strings/link_text', this.state.button_text, true );
		} );
	}

	render_area_header() {

		return (
			<div className="church-tithe-wp-edit-container-admin-only-header">
				<span className="church-tithe-wp-edit-container-admin-only-title">{ church_tithe_wp_editing_strings.tithe_forms_display_style }</span>
			</div>
		);
	}

	render_open_style_option() {
		return(
			<div className="church-tithe-wp-edit-container-admin-only-setting">
				<div className="church-tithe-wp-edit-container-admin-only-setting-description">
					{ church_tithe_wp_editing_strings.how_should_the_tithe_form_display }
				</div>
				<div className="church-tithe-wp-edit-container-admin-only-setting-value">
				<select value={ this.state.form_mode } onChange={ this.handle_form_mode_change.bind( this ) }>
					<option name="form_mode" value="form">{ church_tithe_wp_editing_strings.embed_in_place }</option>
					<option name="form_mode" value="button">{ church_tithe_wp_editing_strings.start_as_a_button }</option>
					<option name="form_mode" value="text_link">{ church_tithe_wp_editing_strings.start_as_a_text_link }</option>
				</select>
				{ (() => {
					if ( 'button' === this.state.form_mode || 'text_link' === this.state.form_mode ) {
						return(
							<React.Fragment>
								<span> </span>{ church_tithe_wp_editing_strings.with_the_text }<span> </span>
								<input
									name="button_text"
									value={ this.state.button_text }
									onChange={ this.handle_button_text_change.bind( this ) }
									type="text"
								/>
								<span> </span>{ church_tithe_wp_editing_strings.which }<span> </span>
								<select value={ this.state.open_style } onChange={ this.handle_open_style_change.bind( this ) }>
									<option name="open_style" value="in_place">{ church_tithe_wp_editing_strings.opens_in_place }</option>
									<option name="open_style" value="in_modal">{ church_tithe_wp_editing_strings.opens_in_modal }</option>
								</select>
								<span> </span>{ church_tithe_wp_editing_strings.when_clicked }
							</React.Fragment>
						)
					}
				})() }
				</div>
			</div>
		);
	}

	render_body() {
		return (
			<React.Fragment>
				{ this.render_open_style_option() }
			</React.Fragment>
		)
	}

	render() {
			return (
				<div>
					<div className="church-tithe-wp-edit-container-admin-only">
						{ this.render_area_header() }
						<div className="church-tithe-wp-edit-container-admin-only-body">
							{ this.render_body() }
						</div>
					</div>
				</div>
			)
	}

}
export default ChurchTitheWPEditOpenStyle;
