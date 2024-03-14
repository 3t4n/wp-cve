window.Church_Tithe_WP_WP_Mail_Health_Check = class Church_Tithe_WP_WP_Mail_Health_Check extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			email_value: '',
			email_send_status: false,
			sendgrid_install_status: false
		}

		this.email_field = React.createRef();

	};

	componentDidMount() {

		// If this component was told we are fixing_it_again, reset the variables on the server just in case we are re-testing the email, and it was previously successful.
		if( this.props.fixing_it_again ) {
			this.handle_fixing_it_again();
		}

		var force_to_step = this.props.data[this.props.health_check_key].unhealthy.component_data.force_to_step;

		// If we should force to a step
		if ( force_to_step ) {
			if ( 'test_email_successfuly_sent' == force_to_step ) {
				this.setState( {
					email_send_status: 'sent'
				} );
			}
		}

		this.setState( {
			email_value: this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.email_address_to_send
		} );
	}

	componentDidUpdate() {

		// If this component was told we are fixing_it_again, reset the variables on the server just in case we are re-testing the email, and it was previously successful.
		if( this.props.fixing_it_again ) {
			this.handle_fixing_it_again();
		}

	}

	wait_for_email_confirmation() {

		// Check the server every 3 seconds to see if they confirmed on a phone, or different device.
		var refreshing_server = setInterval( () => {
			if ( 'sent' == this.state.email_send_status ) {

				// If we are still waiting (still unhealthy)
				if ( ! this.props.data[this.props.health_check_key].is_healthy ) {
					// Check the server again
					this.props.main_component.refresh_mpwpadmin();
				}
				// If the wp_mail check is suddenly healthy, stop checking for updates
				else {

					this.setState( {
						email_send_status: 'succeeded'
					} );

					clearInterval( refreshing_server );
				}
			}
			// If the status of the test email is no longer "sent", stop checking the server for updates.
			else {
				clearInterval( refreshing_server );
			}
		}, 4000 );

		// Only do this waiting/refreshing for 2 minutes. This results in a maximum of 30 checks over 2 minutes.
		setTimeout( () => {
			clearInterval( refreshing_server );
		}, 120000 );

	}

	get_connect_button_text() {
		return this.props.data[this.props.health_check_key].unhealthy.component_data.strings.stripe_connect_button_text;
	}

	send_email( email_address ) {

		this.setState( {
			email_send_status: 'sending'
		});

		var this_component = this;

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_email', email_address );
		postData.append('church_tithe_wp_lightbox_suffix', this.props.slug_suffix );
		postData.append('church_tithe_wp_send_test_email_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.send_test_email_nonce );

		// Send a test email
		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.server_api_endpoint_sent_test_email, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						email_send_status: 'unable_to_attempt_send',
					});

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							this_component.setState( {
								email_send_status: 'sent',
							}, () => {

								this_component.wait_for_email_confirmation();

							});

						} else {

							this_component.setState( {
								email_send_status: 'attempted_and_failed',
							});

						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							email_send_status: 'attempted_but_server_response_incorrect',
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					email_send_status: 'unable_to_attempt',
				});

				console.log('Fetch Error :-S', err);
			}
		);
	}

	install_sendgrid_plugin() {

		this.setState( {
			sendgrid_install_status: 'installing'
		});

		var this_component = this;

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_install_sendgrid_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.install_sendgrid_nonce );

		// Send a test email
		fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.server_api_endpoint_install_sendgrid, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.setState( {
						sendgrid_install_status: 'failed',
					});

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							this_component.setState( {
								email_send_status: 'done',
								sendgrid_install_status: 'succeeded',
							});

						} else {

							this_component.setState( {
								sendgrid_install_status: 'attempted_and_failed',
							});

						}
					}
				).catch(
					function( err ) {

						this_component.setState( {
							sendgrid_install_status: 'attempted_but_server_response_incorrect',
						});

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				this_component.setState( {
					sendgrid_install_status: 'unable_to_attempt',
				});

				console.log('Fetch Error :-S', err);
			}
		);
	}

	handle_fixing_it_again() {

		console.log( 'sdgsgsgsdg' );

		this.reset_wp_mail_flag_on_server().then( () => {

			// Reset all of the local state variables to their beginning statuses
			this.setState( {
				email_value: this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.email_address_to_send,
				email_send_status: false,
				sendgrid_install_status: false
			} );

		} );
	}

	handle_did_not_get_email() {

		this.setState( {
			email_send_status: 'attempted_and_failed'
		});

		this.reset_wp_mail_flag_on_server();
	}

	reset_wp_mail_flag_on_server() {

		return new Promise( (resolve, reject) => {

			// Format the data that we'll send to the server
			var postData = new FormData();
			postData.append('church_tithe_wp_reset_wp_mail_health_check_nonce', this.props.data[this.props.health_check_key].unhealthy.component_data.steps.test_email_successfuly_sent.reset_wp_mail_health_check_nonce );

			this.setState( {
				loading: true
			} );

			// Set the test email as being not received
			fetch( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.test_email_successfuly_sent.server_api_endpoint_reset_wp_mail_health_check, {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				headers: {},
				body: postData
			} ).then( () => {
				this.props.main_component.refresh_mpwpadmin().then( () => {

					this.setState( {
						loading: false
					} );

					resolve();
				} );
			} );

		} );
	}

	handle_email_change( event ) {
		this.setState( {
			email_value: event.target.value
		} );
	}

	click_to_send_button( email_address ) {

		var button_text = this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.send_test_email;

		// If we were unable to attempt a send (possibly no internet connection)
		if ( 'unable_to_attempt' == this.state.email_send_status ) {
			button_text = this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.unable_to_attempt_email;
		}

		// There's an error happening on the server
		if ( 'attempted_but_server_response_incorrect' == this.state.email_send_status ) {
			button_text = this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.attempted_but_server_response_incorrect;
		}

		// If wp_mail failed
		if ( 'attempted_and_failed' == this.state.email_send_status ) {
			button_text = this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.attempted_and_failed;
		}

		// If wp_mail sent the email
		if ( 'sent' == this.state.email_send_status ) {
			button_text = this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.email_sent;
		}

		return( <button onClick={ this.send_email.bind( this, this.state.email_value ) } className="button">{ button_text }</button> );

	}

	handle_server_response_from_sendgrid_api_save( data ) {
		return new Promise( (resolve, reject) => {

			// Wait for 1 second then refresh
			setTimeout( () => {
				if ( data.success ) {
					this.props.main_component.refresh_mpwpadmin();

					// Restart the email checking process
					this.setState( {
						email_send_status: 'restart',
						sendgrid_install_status: false
					} );

					resolve();
					return;
				}
			}, 300 );

		} )
	}

	handle_use_my_own_email_plugin() {

		// Restart the email checking process
		this.setState( {
			email_send_status: 'custom_email_plugin',
			sendgrid_install_status: false
		} );

	}

	render_modal_header() {

		return (
			<React.Fragment>
				<div className={ 'mpwpadmin-lightbox-icon-container' }>
					<div className="mpwpadmin-lightbox-icon">
						<img src={ this.props.data[this.props.health_check_key].icon } />
					</div>
				</div>
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.title }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.data[this.props.health_check_key].unhealthy.component_data.strings.description }
				</div>
			</React.Fragment>
		);
	}

	render_spinner() {
		if ( 'sending' == this.state.email_send_status || 'installing' == this.state.sendgrid_install_status ) {
			return ( <MP_WP_Admin_Spinner /> );
		}
	}

	render_step_1() {
		// If we should show step 1
		if (
			! this.state.email_send_status ||
			'restart' ==  this.state.email_send_status ||
			'custom_email_plugin' == this.state.email_send_status ||
			'unable_to_attempt' == this.state.email_send_status ||
			'attempted_but_server_response_incorrect' == this.state.email_send_status
		) {
			return(
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ ( () => {
						if ( 'restart' == this.state.email_send_status ) {
							return( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.restart_title );
						} else if( 'custom_email_plugin' == this.state.email_send_status ) {
							return( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.custom_email_plugin_title );
						}else {
							return( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.title );
						}
					} ) () }
					</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.step1.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<div className={ 'mpwpadmin-copy-text-container' }>
							<input
							type="text"
							ref={(input) => this.email_field = input}
							value={ this.state.email_value }
							onChange={ this.handle_email_change.bind( this ) }
							/>
							{ this.click_to_send_button( this.email_field ) }
						</div>
					</div>
				</div>
			)
		}
	}

	render_attempted_and_failed() {
		// If we should show step 2
		if ( 'attempted_and_failed' == this.state.email_send_status && 'installing' !== this.state.sendgrid_install_status ) {
			return(
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<button className='button' onClick={ this.install_sendgrid_plugin.bind( this ) }>
							{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.install_sendgrid_text }
						</button>
					</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<button className='button' onClick={ this.handle_use_my_own_email_plugin.bind( this ) }>
							{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.use_my_own }
						</button>
					</div>
				</div>
			)
		}
	}

	render_enter_sendgrid_api_key() {

		// Only show this section if we are done trying to test the email
		if ( 'done' !== this.state.email_send_status ) {
			return '';
		}

		if ( 'succeeded' !== this.state.sendgrid_install_status ) {
			<div>{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.attempted_and_failed.sendgrid_install_failed }</div>
		}

		return(
			<React.Fragment>
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.create_sendgrid_account.title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.create_sendgrid_account.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<a className={ 'button' } target="_blank" href={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.create_sendgrid_account.sendgrid_url }>
							{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.create_sendgrid_account.register_with_sendgrid_button_text }
						</a>
					</div>
				</div>
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.already_have_sendgrid_account.title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.already_have_sendgrid_account.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<a className={ 'button' } target="_blank" href={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.already_have_sendgrid_account.grab_api_key_url }>
							{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.already_have_sendgrid_account.grab_your_api_key_button_text }
						</a>
					</div>
				</div>
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
					{ ( () => {
							var DynamicReactComponent = eval( this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.input_field.react_component );
							return (
								<DynamicReactComponent
									main_component={ this.props.main_component }
									id={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.input_field.id }
									slug={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.input_field.id }
									props={ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.enter_sendgrid_api_key.input_field }
									update_context={ this.handle_server_response_from_sendgrid_api_save.bind( this ) }
								/>
							);
						} )()
					}
					</div>
				</div>
			</React.Fragment>
		)

	}

	render_test_email_successfuly_sent() {
		// If we should show step 2
		if ( 'sent' == this.state.email_send_status ) {
			return(
				<div className={ 'mpwpadmin-lightbox-input-step' }>
					<div className="mpwpadmin-lightbox-input-step-title">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.test_email_successfuly_sent.title }</div>
					<div className="mpwpadmin-lightbox-input-step-description">{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.test_email_successfuly_sent.description }</div>
					<div className={ 'mpwpadmin-lightbox-action-area' }>
						<button className='button' onClick={ this.handle_did_not_get_email.bind( this ) }>
							{ this.props.data[this.props.health_check_key].unhealthy.component_data.steps.test_email_successfuly_sent.did_not_get_email_button_text }
						</button>
					</div>
				</div>
			)
		}
	}

	render_unhealthy_output() {

		return(
			<div>
			{ this.render_modal_header() }
			<div className={ 'mpwpadmin-lightbox-input-steps' }>
				{ this.render_spinner() }
				{ this.render_step_1() }
				{ this.render_test_email_successfuly_sent() }
				{ this.render_attempted_and_failed() }
				{ this.render_enter_sendgrid_api_key() }
			</div>
			</div>
		)
}

render_healthy_output() {
	return(
		<div>
			<div className={ 'mpwpadmin-lightbox-icon-container' }>
			<div className={ 'mpwpadmin-lightbox-icon' }>
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

	if ( ! this.props.data[this.props.health_check_key].is_healthy ) {
		return( this.render_unhealthy_output() );
	}

	if ( this.props.data[this.props.health_check_key].is_healthy ) {
		return( this.render_healthy_output() );
	}

}

render(){

	if ( this.state.loading ) {
		return( <MP_WP_Admin_Spinner /> );
	}

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
