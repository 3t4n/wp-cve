import Church_Tithe_WP_List_View from './church-tithe-wp-list-view.js'
var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Arrangements = class Church_Tithe_WP_Arrangements extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			current_visual_state: 'none',
			total_items: 0,
			current_arrangement_info: null,
			current_arrangement_payment_method: null,
			fetching_arrangements: false,
			fetching_arrangement: false,
		};

	}

	componentDidMount() {
		this.props.check_if_user_is_logged_in();
	}

	componentDidUpdate() {

		// If the user is not logged in, show the login form
		if ( ! this.props.main_component.state.user_logged_in ) {

			if ( 'login' != this.state.current_visual_state ) {
				this.setState( {
					current_visual_state: 'login'
				} );
			}

		}

		// If the user is logged in, show the arrangements screen
		else {

			// Prevent an infinite loop
			if ( 'arrangements' !== this.state.current_visual_state && 'fetching_data' != this.state.current_visual_state ) {
				this.setState( {
					current_visual_state: 'arrangements'
				} );
			}

			// If no arrangements have been loaded from the server, load some
			if ( 'arrangements' == this.state.current_visual_state && ! this.state.rows ) {
				this.get_arrangements( 1, 10, '' );
			}

		}

		// If the main component is telling us to reload the arrangements
		if ( this.props.main_component.state.reload_arrangements ) {
			this.props.main_component.setState( {
				reload_arrangements: false
			}, () => {
				this.get_arrangements( 1, 10, '' );
			} );
		}

		// If the 3rd level in the URL exists, (this could likely be more robust) set the current item to that
		if (
			Object.keys(this.props.main_component.state.all_current_visual_states)[0] &&
			this.props.main_component.state.all_current_visual_states['manage_payments'] &&
			Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'])[0]
		) {
			var second_slug = Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'])[0];

			if ( 'arrangement' == second_slug ) {
				if( Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'][second_slug])[0] ) {
					var third_slug = Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'][second_slug])[0];

					// If the current single item ID does not match the item ID in the URL
					if ( ! this.props.main_component.state.current_arrangement_info ) {
						this.get_arrangement( third_slug );
					}

					else if ( this.props.main_component.state.current_arrangement_info && third_slug !== this.props.main_component.state.current_arrangement_info.id ) {
						//this.get_arrangement( third_slug );
					}

				}
			}
		}

	}

	get_arrangements( current_page, items_per_page, search_term ) {

		// If we are already fetching arrangements, don't fetch more again.
		if ( this.state.fetching_arrangements ) {
			return false;
		}

		var this_component = this;

		this.setState( {
			current_visual_state: 'fetching_data',
			fetching_arrangements: true,
		});

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_get_arrangements' );
		postData.append('church_tithe_wp_current_page', current_page);
		postData.append('church_tithe_wp_items_per_page', items_per_page);
		postData.append('church_tithe_wp_search_term', search_term);
		postData.append('church_tithe_wp_get_arrangements_nonce', this.props.main_component.state.frontend_nonces.get_arrangements_nonce);

		// Get the arrangements defined by the paramaters in the state
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_arrangements', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					// Remove arrangements since none were found
					this_component.setState( {
						columns: null,
						rows: null,
						total_items: 0,
						fetching_arrangements: false,
					} );

					console.log('Looks like there was a problem. Status Code: ' + response.status);

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							// Reset the check user nonce
							this_component.props.main_component.setState( {
								user_logged_in: data.user_logged_in,
								frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
							}, () => {
								this_component.setState( {
									fetching_arrangements: false,
									current_visual_state: 'arrangements',
									columns: data.columns,
									rows: data.rows,
									total_items: data.total_items
								} );
							} );

						} else {

							// Remove arrangements since none were found
							this_component.setState( {
								fetching_arrangements: false,
								columns: null,
								rows: null,
								total_items: 0
							} );

							// Remove the user ID from the main state and set the state to be login
							this_component.props.main_component.setState( {
								user_logged_in: null,
								frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
							} );

						}
					}
				).catch(
					function( err ) {
						console.log('Fetch Error: ', err);
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
			}
		);

	}

	get_arrangement( arrangement_id ) {

		// If we are already fetching arrangements, don't fetch more again.
		if ( this.state.fetching_arrangement ) {
			return false;
		}

		return new Promise( (resolve, reject) => {

			this.setState( {
				fetching_arrangement: true,
			} );

			var this_component = this;

			var postData = new FormData();
			postData.append('action', 'church_tithe_wp_get_arrangement' );
			postData.append('church_tithe_wp_arrangement_id', arrangement_id);
			postData.append('church_tithe_wp_get_arrangement_nonce', this.props.main_component.state.frontend_nonces.get_arrangement_nonce);

			// Get the arrangements defined by the paramaters in the state
			fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_arrangement', {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				body: postData
			} ).then(
				( response ) => {
					if ( response.status !== 200 ) {

						this_component.props.main_component.setState( {
							current_arrangement_info: null,
							current_arrangement_payment_method: null,
						}, () => {
							this.setState( {
								fetching_arrangement: false,
							} );
						} );

						console.log('Looks like there was a problem. Status Code: ' + response.status);

						reject();
						return;
					}

					// Examine the text in the response
					response.json().then(
						( data ) => {
							if ( data.success ) {

								this_component.props.main_component.setState( {
									user_logged_in: data.user_logged_in,
									current_arrangement_info: data.arrangement_info,
									current_arrangement_payment_method: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
								}, () => {
									this.setState( {
										fetching_arrangement: false,
									}, () => {
										this.get_arrangement_payment_method( arrangement_id )
										resolve();
									} );
								} );

							} else {

								// Remove the user ID from the main state and set the state to be login
								this_component.props.main_component.setState( {
									user_logged_in: null,
									current_arrangement_info: null,
									current_arrangement_payment_method: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
								}, () => {
									this.setState( {
										fetching_arrangement: false,
									} );
									reject();
								} );

							}
						}
					).catch(
						( err ) => {
							this.setState( {
								fetching_arrangement: false,
							} );
							console.log('Fetch Error: ', err);
							reject();
							return;
						}
					);
				}
			).catch(
				( err ) => {
					this.setState( {
						fetching_arrangement: false,
					} );
					console.log('Fetch Error :-S', err);
					reject();
					return;
				}
			);

		});

	}

	get_arrangement_payment_method( arrangement_id ) {

		return new Promise( (resolve, reject) => {

			var this_component = this;

			var postData = new FormData();
			postData.append('action', 'church_tithe_wp_get_arrangement_payment_method_endpoint' );
			postData.append('church_tithe_wp_arrangement_id', arrangement_id);
			postData.append('church_tithe_wp_get_arrangement_payment_method_nonce', this.props.main_component.state.frontend_nonces.get_arrangement_payment_method_nonce);

			// Get the arrangements defined by the paramaters in the state
			fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_arrangement_payment_method_endpoint', {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				body: postData
			} ).then(
				( response ) => {
					if ( response.status !== 200 ) {

						this_component.props.main_component.setState( {
							current_arrangement_payment_method: null
						} );

						console.log('Looks like there was a problem. Status Code: ' + response.status);

						reject();
						return;
					}

					// Examine the text in the response
					response.json().then(
						function( data ) {
							if ( data.success ) {

								this_component.props.main_component.setState( {
									current_arrangement_payment_method: data.payment_method_data
								}, () => {
									resolve();
								} );

							} else {

								// Remove the user ID from the main state and set the state to be login
								this_component.props.main_component.setState( {
									current_arrangement_payment_method: null
								}, () => {
									reject();
								} );

							}
						}
					).catch(
						function( err ) {
							console.log('Fetch Error: ', err);
							reject();
							return;
						}
					);
				}
			).catch(
				function( err ) {
					console.log('Fetch Error :-S', err);
					reject();
					return;
				}
			);

		});

	}

	on_row_click( row, event ) {

		this.get_arrangement( row.id.value ).then( () => {
			this.props.main_component.set_all_current_visual_states( {
				manage_payments: {
					arrangement: {
						[row.id.value]: {}
					}
				}
			} );
		} );

	}

	render() {

		if ( 'none' == this.state.current_visual_state ) {
			return ( '' );
		} else {
			return(
				<React.Fragment>
					<div className={ 'church-tithe-wp-login-view ' + church_tithe_wp_get_current_view_class( this, ['login'] ) }>
						<Church_Tithe_WP_Login
							main_component={ this.props.main_component }
							check_if_user_is_logged_in={ this.props.check_if_user_is_logged_in }
						/>
					</div>
					<div className={ 'church-tithe-wp-manage-arrangements-view ' + church_tithe_wp_get_current_view_class( this, ['arrangements','fetching_data'] ) }>
							<Church_Tithe_WP_List_View
								main_component={ this.props.main_component }
								current_visual_state={ this.state.current_visual_state }
								rows={ this.state.rows }
								columns={ this.state.columns }
								total_items={ this.state.total_items }
								get_rows_and_columns={ this.get_arrangements.bind( this ) }
								current_visual_state={ this.state.current_visual_state }
								on_row_click={ this.on_row_click.bind( this ) }
							/>
					</div>
				</React.Fragment>
			);
		}
	}
}
export default Church_Tithe_WP_Arrangements;
