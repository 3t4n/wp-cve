var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Manage_Payments = class Church_Tithe_WP_Manage_Payments extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			map_of_visual_states: {
				manage_payments: {
					variable: {}
				}
			},
			current_visual_state: 'none',
		};

		this.check_if_user_is_logged_in = this.check_if_user_is_logged_in.bind( this );
	}

	componentDidMount() {

		this.check_if_user_is_logged_in();

		church_tithe_wp_set_visual_state_of_component( {
			component: this,
			default_visual_states: {
				parent_in_view: 'arrangements',
				parent_not_in_view: 'none'
			},
			name_of_visual_state_variable: 'current_visual_state'
		} );

	}

	componentDidUpdate() {

		church_tithe_wp_set_visual_state_of_component( {
			component: this,
			default_visual_states: {
				parent_in_view: 'arrangements',
				parent_not_in_view: 'none'
			},
			name_of_visual_state_variable: 'current_visual_state'
		} );

	}

	check_if_user_is_logged_in() {

		var this_component = this;

		var postData = new FormData();
		postData.append( 'action', 'church_tithe_wp_check_if_user_logged_in' );
		postData.append( 'church_tithe_wp_check_if_user_logged_in', true );

		// Check if this user is logged in, and set the state of "Manage Payments" accordingly
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_check_if_user_logged_in', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					this_component.props.main_component.setState( {
						user_logged_in: null
					} );

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							this_component.props.main_component.setState( {
								user_logged_in: data.user_logged_in,
								frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
							} );

						} else {

							// Remove the user ID from the main state and set the state to be login
							this_component.props.main_component.setState( {
								user_logged_in: null,
								frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
							} );
						}
					}
				).catch(
					function( err ) {

						// Remove the user ID from the main state and set the state to be login
						this_component.props.main_component.setState( {
							user_logged_in: null
						} );

						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {

				// Remove the user ID from the main state and set the state to be login
				this_component.props.main_component.setState( {
					user_logged_in: null
				} );

				console.log('Fetch Error :-S', err);
			}
		);

	}

	get_current_title_string() {

		// If the current visual state is login
		if( ! this.props.main_component.state.user_logged_in ) {

			return this.props.main_component.state.unique_settings.strings.login;

		}

		// If the current visual state is transactions
		if( 'transactions' == this.state.current_visual_state ) {

			return this.props.main_component.state.unique_settings.strings.transactions_title;

		}

		// If the current visual state is transactions
		if( 'transaction' == this.state.current_visual_state ) {

			return this.props.main_component.state.unique_settings.strings.transaction_title;

		}

		// If the current visual state is arrangements
		if( 'arrangements' == this.state.current_visual_state ) {

			return this.props.main_component.state.unique_settings.strings.arrangements_title;

		}

		// If the current visual state is arrangement
		if( 'arrangement' == this.state.current_visual_state ) {

			return this.props.main_component.state.unique_settings.strings.arrangement_title;

		}
	}

	maybe_render_close_button() {

		if ( ! this.props.show_close_button ) {
			return '';
		}

		return (
			<div className="church-tithe-wp-close-btn" aria-label="Close" onClick={ this.props.main_component.handle_visual_state_change_click_event.bind( this, {}, {} ) }><img src={ this.props.main_component.state.dynamic_settings.close_button_url } /></div>
		);
	}

	render() {

		// If the user is not logged in, show the login form
		if ( ! this.props.main_component.state.user_logged_in ) {

			return(
				<div className="church-tithe-wp-manage-payments">

					<div className="church-tithe-wp-component-box">

						<header className="church-tithe-wp-header" role="banner">
							<h1 className="church-tithe-wp-header-title">{ this.get_current_title_string() }</h1>
							{ this.maybe_render_close_button() }
						</header>

						<div className={ 'church-tithe-wp-payment-box-view church-tithe-wp-manage-payments-view' }>
							<div className={ 'church-tithe-wp-login-view' }>
								<Church_Tithe_WP_Login
									main_component={ this.props.main_component }
									check_if_user_is_logged_in={ this.props.check_if_user_is_logged_in }
								/>
							</div>
						</div>
					</div>
				</div>
			);

		}

		return (
			<div className="church-tithe-wp-manage-payments">

				<div className="church-tithe-wp-component-box">

					<header className="church-tithe-wp-header" role="banner">
						<h1 className="church-tithe-wp-header-title">{ this.get_current_title_string() }</h1>
						{ this.maybe_render_close_button() }
					</header>

					<div className={ 'church-tithe-wp-payment-box-view church-tithe-wp-manage-payments-view' }>

						{ ( () => {

							// If the current visual state is "none"
							if( 'none' == this.state.current_visual_state ) {

								return (
									''
								);

							} else {
								return (
									<React.Fragment>
										<div className={ 'church-tithe-wp-manage-payments-view ' + church_tithe_wp_get_current_view_class( this, ['transactions', 'arrangements'] ) }>
											<Church_Tithe_WP_Manage_Payments_Nav
												main_component={ this.props.main_component }
												current_visual_state={ this.state.current_visual_state }
											/>
										</div>
										<div className={ 'church-tithe-wp-manage-payments-view ' + church_tithe_wp_get_current_view_class( this, ['transactions'] ) }>
											<Church_Tithe_WP_Transactions
												main_component={ this.props.main_component }
												check_if_user_is_logged_in={ this.check_if_user_is_logged_in.bind( this ) }
											/>
										</div>
										<div className={ 'church-tithe-wp-manage-payments-view ' + church_tithe_wp_get_current_view_class( this, ['transaction'] ) }>
											{ ( () => {
												// Re-render this component from scratch so that no data is left-over from old loads
												if ( 'transaction' == this.state.current_visual_state ) {
													return(
														<Church_Tithe_WP_Payment_Confirmation
															main_component={ this.props.main_component }
															do_after_payment_actions={ false }
														/>
													);
												}
											} )() }
										</div>
										<div className={ 'church-tithe-wp-manage-payments-view ' + church_tithe_wp_get_current_view_class( this, ['arrangements'] ) }>
											<Church_Tithe_WP_Arrangements
												main_component={ this.props.main_component }
												check_if_user_is_logged_in={ this.check_if_user_is_logged_in.bind( this ) }
											/>
										</div>
										<div className={ 'church-tithe-wp-manage-payments-view ' + church_tithe_wp_get_current_view_class( this, ['arrangement'] ) }>
											<Church_Tithe_WP_Arrangement
												main_component={ this.props.main_component }
												check_if_user_is_logged_in={ this.check_if_user_is_logged_in.bind( this ) }
											/>
										</div>
									</React.Fragment>
								);
							}
						} )() }

					</div>

				</div>

			</div>
		);
	}
}
export default Church_Tithe_WP_Manage_Payments;
