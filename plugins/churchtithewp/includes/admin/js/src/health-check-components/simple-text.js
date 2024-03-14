window.Church_Tithe_WP_Simple_Text_Health_Check = class Church_Tithe_WP_Simple_Text_Health_Check extends React.Component {

	constructor( props ) {
		super(props);
	};

	render_unhealthy_output() {

		return(
			<div>
				<div className="mpwpadmin-lightbox-icon">
					<img src={ this.props.data[this.props.health_check_key].icon } />
				</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.description }
				</div>
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.required_action_title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.required_action_description }</div>
				</div>
			</div>
		);
	}

	render_healthy_output() {
		return(
			<div>
				<div className={ 'mpwpadmin-lightbox-icon-container' }>
					<div className="mpwpadmin-lightbox-icon">
						<img src={ this.props.data[this.props.health_check_key].icon } />
					</div>
				</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.data[this.props.health_check_key].healthy.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.data[this.props.health_check_key].healthy.component_data.strings.description }
				</div>
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					{ ( () => {
						if ( this.props.main_component.state.data.general_config.doing_wizard && this.props.next_lightbox_slug ) {
							return(
								<button className="button" onClick={ this.props.main_component.set_all_current_visual_states.bind( this, false, { [this.props.next_lightbox_slug]: {} } ) }>
									{ this.props.data[this.props.health_check_key].healthy.component_data.strings.next_wizard_step_button_text }
								</button>
							);
						}
					} )() }
				</div>
			</div>
		);
	}

	render_based_on_health() {

		if ( this.props.data[this.props.health_check_key].is_healthy ) {
			return( this.render_healthy_output() );
		}

		if ( ! this.props.data[this.props.health_check_key].is_healthy ) {
			return( this.render_unhealthy_output() );
		}

	}

	render(){

		if ( ! this.props.data[this.props.health_check_key] ) {
			return( '' );
		}

		return (
			<React.Fragment>
				{ this.render_based_on_health() }
			</React.Fragment>
		);

	}

};
