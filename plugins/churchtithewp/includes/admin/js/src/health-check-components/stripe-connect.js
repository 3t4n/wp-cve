window.Church_Tithe_WP_Stripe_Connect_Health_Check = class Church_Tithe_WP_Stripe_Connect_Health_Check extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			lightbox_open: false
		}
	};

	componentDidMount() {
		this.handle_open_status_based_on_url().then( () => {
			// If lightbox containing this component is open in the lightbox views, set the redirect URL on the server so it opens this lightbox
			this.set_stripe_success_redirect_on_server( this.state.lightbox_open );
		} );
	}

	componentDidUpdate() {

		this.handle_open_status_based_on_url().then( () => {

			this.props.main_component.refresh_mpwpadmin();

			// If lightbox containing this component is open in the lightbox views, set the redirect URL on the server so it opens this lightbox
			this.set_stripe_success_redirect_on_server( this.state.lightbox_open );
		} );

	}

	handle_open_status_based_on_url() {

		return new Promise( (resolve, reject) => {

			// If a lightbox is open based on the setting in the main component
			if ( this.props.main_component && this.props.main_component.state.lightbox_visual_state ) {
				// Check if that lightbox is us!
				if ( this.props.this_lightbox_slug == Object.keys(this.props.main_component.state.lightbox_visual_state)[0] ) {

					// The lightbox holding this component is showing. Therefore, set that status in this component as well.
					if ( ! this.state.lightbox_open ) {
						this.setState( {
							lightbox_open:  true
						}, () => {
							resolve( this.state );
							return;
						} );
					}
				}
				// If the current lightbox in the URL is not us, close this one.
				else {
					if ( this.state.lightbox_open ) {
						this.setState( {
							lightbox_open:  false
						}, () => {
							resolve( this.state );
							return;
						} );
					}
				}
			}

		} );

	}

	set_stripe_success_redirect_on_server( redirect_to_this_lightbox ) {

		if ( ! redirect_to_this_lightbox ) {
			var ctwp_scsr_mode = '';
		} else {
			var ctwp_scsr_mode = this.props.health_check_key;
		}

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_set_ctwp_scsr', ctwp_scsr_mode);
		postData.append('church_tithe_wp_lightbox_suffix', this.props.slug_suffix);
		postData.append('church_tithe_wp_set_ctwp_scsr_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.church_tithe_wp_set_ctwp_scsr_nonce);

		var this_component = this;

		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.server_api_endpoint_set_stripe_connect_success_url, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} );

	}

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
				{ ( () => {
					if ( this.props.data[this.props.health_check_key].unhealthy.component_data.strings.stripe_connect_button_text ) {
						return(
							<div className={ 'mpwpadmin-lightbox-action-area' }>
								<a className='churchtithewp-stripe-connect' href={ this.props.data[this.props.health_check_key].unhealthy.component_data.stripe_connect_url }>
									<span>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.stripe_connect_button_text }</span>
								</a>
							</div>
						);
					}
				} ) () }
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
