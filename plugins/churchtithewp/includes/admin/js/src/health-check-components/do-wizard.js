window.Church_Tithe_WP_Do_Wizard_Health_Check = class Church_Tithe_WP_Do_Wizard_Health_Check extends React.Component {

	constructor( props ) {
		super(props);
	};

	componentDidUpdate() {
		// If this component was told we are fixing_it_again, reset the variable on the server which controls the onboarding wizard's status.
		if( this.props.fixing_it_again ) {
			this.start_wizard();
		}
	}

	start_wizard() {

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append( 'church_tithe_wp_start_wizard_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.start_wizard_nonce );

		var this_component = this;

		// Set the onboard wizard to be in progress
		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.server_api_endpoint_church_tithe_wp_start_wizard, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log( data);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Refresh mpwpadmin
							this_component.props.main_component.refresh_mpwpadmin();

						} else {
							console.log( data );
							return;
						}
					}
				).catch(
					function( err ) {
						console.log('Fetch Error: ', err);
						return;
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
				return;
			}
		);

	}

	set_wizard_to_later() {

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append( 'church_tithe_wp_start_wizard_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.wizard_later_nonce );

		var this_component = this;

		// Set the onboard wizard to be in progress
		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.server_api_endpoint_church_tithe_wp_wizard_later, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log( data);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Refresh mpwpadmin
							this_component.props.main_component.refresh_mpwpadmin();

						} else {
							console.log( data );
							return;
						}
					}
				).catch(
					function( err ) {
						console.log('Fetch Error: ', err);
						return;
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
				return;
			}
		);

	}

	handle_do_wizard_click() {

		this.start_wizard();

		this.props.main_component.set_all_current_visual_states( false, { [this.props.next_lightbox_slug]: {} } );

	}

	handle_later_click() {

		// Set the status of the wizard to "later" on the server.
		this.set_wizard_to_later();

		// Close the lightbox
		this.props.main_component.set_all_current_visual_states( false, {} );

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
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<button className="button" onClick={ this.handle_later_click.bind( this ) }>
						{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.do_later_button_text }
					</button>
					<button className="button" onClick={ this.handle_do_wizard_click.bind( this ) }>
						{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.next_wizard_step_button_text }
					</button>
				</div>
			</div>
		);
	}

	render_based_on_health() {

		// The healthy and unhealthy output are exactly the same for the Do Wizard health check.
		if ( this.props.data[this.props.health_check_key].is_healthy ) {
			return( this.render_unhealthy_output() );
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
