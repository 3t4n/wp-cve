import Church_Tithe_WP_List_View from './church-tithe-wp-list-view.js'
var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Transactions = class Church_Tithe_WP_Transactions extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			current_visual_state: 'none',
			total_items: 0,
			current_transaction_info: null
		};

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

		// If the user is logged in, show the transactions screen
		else {

			// Prevent an infinite loop
			if ( 'transactions' != this.state.current_visual_state && 'fetching_data' != this.state.current_visual_state ) {
				this.setState( {
					current_visual_state: 'transactions'
				} );
			}

			// If no transactions have been loaded from the server, load some
			if ( 'transactions' == this.state.current_visual_state && ! this.state.rows ) {
				this.get_transactions( 1, 10, '' );
			}

		}

		// If the 3rd level in the URL exists, (this could likely be more robust) set the current item to that
		if (
			Object.keys(this.props.main_component.state.all_current_visual_states)[0] &&
			this.props.main_component.state.all_current_visual_states['manage_payments'] &&
			Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'])[0]
		) {
			var second_slug = Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'])[0];

			if ( 'transaction' == second_slug ) {
				if( Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'][second_slug])[0] ) {
					var third_slug = Object.keys(this.props.main_component.state.all_current_visual_states['manage_payments'][second_slug])[0];

					// If the current single item ID does not match the item ID in the URL
					if ( ! this.props.main_component.state.current_transaction_info ) {
						this.get_transaction( third_slug );
					}

					if ( this.props.main_component.state.current_transaction_info && third_slug !== this.props.main_component.state.current_transaction_info.transaction_id ) {
						this.get_transaction( third_slug );
					}

				}
			}
		}

	}

	get_transactions( current_page, items_per_page, search_term ) {

		var this_component = this;

		this.setState( {
			current_visual_state: 'fetching_data'
		});

		var postData = new FormData();
		postData.append('action', 'church_tithe_wp_get_transactions' );
		postData.append('church_tithe_wp_current_page', current_page);
		postData.append('church_tithe_wp_items_per_page', items_per_page);
		postData.append('church_tithe_wp_search_term', search_term);
		postData.append('church_tithe_wp_get_transactions_nonce', this.props.main_component.state.frontend_nonces.get_transactions_nonce);

		// Get the transactions defined by the paramaters in the state
		fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_transactions', {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {

					// Remove transactions since none were found
					this_component.setState( {
						columns: null,
						rows: null,
						total_items: 0
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
									current_visual_state: 'transactions',
									columns: data.columns,
									rows: data.rows,
									total_items: data.total_items
								} );
							} );

						} else {

							// Remove transactions since none were found
							this_component.setState( {
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

	get_transaction( transaction_id ) {

		return new Promise( (resolve, reject) => {

			var this_component = this;

			var postData = new FormData();
			postData.append('action', 'church_tithe_wp_get_transaction' );
			postData.append('church_tithe_wp_transaction_id', transaction_id);
			postData.append('church_tithe_wp_get_transaction_nonce', this.props.main_component.state.frontend_nonces.get_transaction_nonce);

			// Get the transactions defined by the paramaters in the state
			fetch( church_tithe_wp_js_vars.ajaxurl + '?church_tithe_wp_get_transaction', {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				body: postData
			} ).then(
				( response ) => {
					if ( response.status !== 200 ) {

						this_component.props.main_component.setState( {
							user_logged_in: null,
							current_transaction_info: null
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
									user_logged_in: data.user_logged_in,
									current_transaction_info: data.transaction_info,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
								}, () => {
									resolve();
								} );

							} else {

								// Remove the user ID from the main state and set the state to be login
								this_component.props.main_component.setState( {
									user_logged_in: null,
									current_transaction_info: null,
									frontend_nonces: data.frontend_nonces ? data.frontend_nonces : this_component.props.main_component.state.frontend_nonces
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

		this.get_transaction( row.id.value ).then( () => {
			this.props.main_component.set_all_current_visual_states( {
				manage_payments: {
					transaction: {
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
					<div className={ 'church-tithe-wp-manage-transactions-view ' + church_tithe_wp_get_current_view_class( this, ['transactions','fetching_data'] ) }>
							<Church_Tithe_WP_List_View
								main_component={ this.props.main_component }
								current_visual_state={ this.state.current_visual_state }
								rows={ this.state.rows }
								columns={ this.state.columns }
								total_items={ this.state.total_items }
								get_rows_and_columns={ this.get_transactions.bind( this ) }
								current_visual_state={ this.state.current_visual_state }
								on_row_click={ this.on_row_click.bind( this ) }
							/>
					</div>
				</React.Fragment>
			);
		}
	}
}
export default Church_Tithe_WP_Transactions;
