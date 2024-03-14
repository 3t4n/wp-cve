window.Church_Tithe_WP_Stripe_Webhook_Health_Check = class Church_Tithe_WP_Stripe_Webhook_Health_Check extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			webhook_copied: false
		}

		this.copy_text_field = React.createRef();
		this.set_input_field_as_ref = this.set_input_field_as_ref.bind( this );
	};

	get_connect_button_text() {
		return this.props.data[this.props.health_check_key].unhealthy.component_data.strings.stripe_connect_button_text;
	}

	copy_text( ref_to_copy ) {
		if ( ! ref_to_copy ) {
			return;
		}
		ref_to_copy.select();
    document.execCommand('copy');
    this.setState({ webhook_copied: true });
	}

	set_input_field_as_ref( element ) {
		this.copy_text_field = element;
	}

	click_to_copy_button() {

		if ( this.state.webhook_copied ) {
			return( <button onClick={ this.copy_text.bind( this, this.copy_text_field  ) } className="button">{ 'Copied!' }</button> );
		} else {
			return( <button onClick={ this.copy_text.bind( this, this.copy_text_field  ) } className="button">{ 'Click to copy' }</button> );
		}

	}

	handle_server_response( data ) {
		return new Promise( (resolve, reject) => {

			// Wait for 1 second then refresh
			setTimeout( () => {
				if ( data.success ) {
					this.props.main_component.refresh_mpwpadmin();
					resolve();
					return;
				}
			}, 300 );

		} )
	}

	render_unhealthy_actions() {

		if ( 'localhost' == this.props.data[this.props.health_check_key].unhealthy.mode ) {
			return (
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
			);
		}

		if ( 'live_site' == this.props.data[this.props.health_check_key].unhealthy.mode ) {

			return(
				<div className={ 'mpwpadmin-lightbox-input-steps' }>
					<div className={ 'mpwpadmin-lightbox-input-step' }>
						<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.title }</div>
						<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.description }</div>
						<div className={ 'mpwpadmin-lightbox-action-area' }>
							<div className={ 'mpwpadmin-copy-text-container' }>
								<input
									type="text"
									readOnly
									ref={ this.set_input_field_as_ref }
									value={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.url_to_copy }
								/>
								{ this.click_to_copy_button() }
							</div>
						</div>
					</div>
					<div className={ 'mpwpadmin-lightbox-input-step' }>
						<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.title }</div>
						<div className="mpwpadmin-lightbox-input-step-description">
							<ul>
								<li>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.description.line_1 }</li>
								<li>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.description.line_2 }</li>
								<li>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.description.line_3 }</li>
								<li>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.description.line_4 }</li>
							</ul>
						</div>
						<div className={ 'mpwpadmin-lightbox-action-area' }>
							<a className='churchtithewp-stripe-connect' target="_blank" href={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.stripe_connect_url }>
								<span>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step2.stripe_connect_button_text }</span>
							</a>
						</div>
					</div>
					<div className={ 'mpwpadmin-lightbox-input-step' }>
						<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.title }</div>
						<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.description }</div>
						<div className={ 'mpwpadmin-lightbox-action-area' }>
							{ ( () => {
									var DynamicReactComponent = eval( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.input_field.react_component );
									return (
										<DynamicReactComponent
											main_component={ this.props.main_component }
											id={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.input_field.id }
											slug={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.input_field.id }
											props={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step3.input_field }
											update_context={ this.handle_server_response.bind( this ) }
										/>
									);
								} )()
							}
						</div>
					</div>
				</div>
			)
		}
	}

	render_unhealthy_output() {

		return(
			<div>
			<div className={ 'mpwpadmin-lightbox-icon-container' }>
				<div className="mpwpadmin-lightbox-icon">
					<img src={ this.props.data[this.props.health_check_key].icon } />
				</div>
			</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.description }
				</div>
				{ this.render_unhealthy_actions() }
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
