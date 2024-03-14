window.Church_Tithe_WP_Setting_Wizard = class Church_Tithe_WP_Setting_Wizard extends React.Component {

	constructor( props ) {
		super(props);
	};

	render_unhealthy_output() {

		var DynamicReactComponent = eval( this.props.data[this.props.health_check_key].unhealthy.component_data.input_field.react_component );

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
					<div className={ 'mpwpadmin-setting-container ' + 'mpwpadmin-setting-' + this.props.health_check_key + '-container' }>
						<DynamicReactComponent
							main_component={ this.props.main_component }
							id={ this.props.health_check_key }
							slug={ this.props.health_check_key }
							props={ this.props.data[this.props.health_check_key].unhealthy.component_data.input_field }
							class_name={ 'mpwpadmin-setting mpwpadmin-setting-' + this.props.health_check_key }
							context_id={ null }
						/>
					</div>
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
