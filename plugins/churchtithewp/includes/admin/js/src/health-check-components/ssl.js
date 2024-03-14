window.Church_Tithe_WP_SSL_Health_Check = class Church_Tithe_WP_SSL_Health_Check extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			url_update_status: false
		}
	};

	update_wordpress_url_to_https() {

		var this_component = this;

		this_component.setState( {
			url_update_status: 'attempting_update',
		});

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_update_wordpress_url_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.church_tithe_wp_update_wordpress_url_nonce );

		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.server_api_endpoint_update_wordpress_url, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						url_update_status: 'unable_to_update',
					});

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Refresh this page but over https
							window.location.href = data.https_url;

						} else {

							this_component.setState( {
								url_update_status: 'unable_to_update',
							});

						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							url_update_status: 'unable_to_update',
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					url_update_status: 'unable_to_update',
				});

				console.log('Fetch Error :-S', err);
			}
		);

	}

	render_unhealthy_action() {

		// If no certificate exists
		if ( 'no_certificate_exists' == this.props.data[this.props.health_check_key].unhealthy.mode ) {

			return (
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.required_action_title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.required_action_description }</div>
				</div>
			);
		}

		// If a certificate does exist, but we just aren't running over https
		if ( 'certificate_exists' == this.props.data[this.props.health_check_key].unhealthy.mode ) {

			if ( ! this.state.url_update_status ) {
				return (
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<button className="button" onClick={ this.update_wordpress_url_to_https.bind( this ) }>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.button_text }</button>
					</div>
				);
			}

			if ( 'attempting_update' == this.state.url_update_status  ) {
				return (
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<MP_WP_Admin_Spinner />
					</div>
				);
			}

			if ( 'unable_to_update' == this.state.url_update_status  ) {
				return (
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.update_failed_title }</div>
						<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.update_failed_description }</div>
						<button className="button" onClick={ this.update_wordpress_url_to_https.bind( this ) }>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.button_text }</button>
					</div>
				);
			}
		}

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
				{ this.render_unhealthy_action() }
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
