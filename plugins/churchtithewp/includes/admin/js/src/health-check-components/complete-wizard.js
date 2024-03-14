window.Church_Tithe_WP_Complete_Wizard = class Church_Tithe_WP_Complete_Wizard extends React.Component {

	constructor( props ) {
		super(props);
	};

	complete_wizard() {

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append( 'church_tithe_wp_complete_wizard_nonce', this.props.data[this.props.health_check_key].healthy.component_data.complete_wizard_nonce );

		var this_component = this;

		// Set the onboard wizard to be complete
		fetch( this.props.data[this.props.health_check_key].healthy.component_data.server_api_endpoint_complete_wizard, {
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
							this_component.props.main_component.refresh_mpwpadmin().then( () => {
								// Close the lightbox
								this_component.props.main_component.set_all_current_visual_states( false, {} );
							} );

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

	render_healthy_output() {

		return(
			<div>
				<div className="mpwpadmin-lightbox-icon">
					<img src={ this.props.data[this.props.health_check_key].icon } />
				</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.data[this.props.health_check_key].healthy.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.data[this.props.health_check_key].healthy.component_data.strings.description }
				</div>
				<div className={ 'mpwpadmin-lightbox-action-area' }>
					<button className="button" onClick={ this.complete_wizard.bind( this ) }>
						{ this.props.data[this.props.health_check_key].healthy.component_data.strings.complete_wizard_button_text }
					</button>
				</div>
			</div>
		);
	}

	render_based_on_health() {

		// The healthy and unhealthy output are exactly the same for the Complete Wizard health check.
		return( this.render_healthy_output() );

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
