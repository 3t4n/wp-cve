window.ChurchTitheWPEditButton = class ChurchTitheWPEditButton extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
		};

	}

	maybe_render_editing_lightbox() {

		var EditingComponent = eval( this.props.component );

		return(
			<Church_Tithe_WP_Modal
				main_component={ this.props.main_component }
				slug={ this.props.editing_key }
				modal_contents={
					<EditingComponent
						main_component={ this.props.main_component }
						editing_key={ this.props.editing_key }
					/>
				}
			/>
		);
	}

	maybe_render_edit_button() {
		if ( this.props.main_component.state.editing_mode ) {
			return(
				<React.Fragment>
					<div className="church-tithe-wp-edit-button-container">
						<button
							className="button church-tithe-wp-edit-button"
							onClick={ this.props.main_component.handle_visual_state_change_click_event.bind( null, false, {
								[this.props.editing_key]: {}
							} ) }
						>
						{
							church_tithe_wp_editing_strings.edit
						}
						</button>
					</div>
					{ this.maybe_render_editing_lightbox() }
				</React.Fragment>
			)
		} else {
			return '';
		}
	}

	render() {
		return (
			this.maybe_render_edit_button()
		)
	}

}
export default ChurchTitheWPEditButton;
