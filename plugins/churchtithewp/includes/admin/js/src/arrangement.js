window.Church_Tithe_WP_Admin_Single_Arrangement = class Church_Tithe_WP_Admin_Single_Arrangement extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			cancellation_status: 'initial',
		};

	};

	cancel_arrangement() {

		// Do the double check for "are you sure you want to refund"
		if ( 'initial' == this.state.cancellation_status ) {

			this.setState( {
				cancellation_status: 'are_you_sure'
			} );

			return false;
		}

		this.setState( {
			cancellation_status: 'cancelling'
		} );

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_arrangement_being_cancelled', this.props.current_single_item.id.value);
		postData.append('church_tithe_wp_nonce_cancel_arrangement', this.props.view_info.nonce_cancel_arrangement);

		var this_component = this;

		fetch( this.props.view_info.server_api_endpoint_url_cancel_arrangement, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log('Looks like there was a problem. Status Code: ' +
					response.status);

					this_component.setState( {
						cancellation_status: 'failed'
					} );

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							if ( data.pending ) {
								this_component.setState( {
									cancellation_status: 'pending'
								} );
							} else {
								this_component.setState( {
									cancellation_status: 'cancellation_succeeded'
								}, () => {

									// Wait for 1 second while the "succeess is shown"
									setTimeout( () => {

										this_component.setState( {
											cancellation_status: 'cancelled'
										} );

										// Get the updated arrangement now that it's been cancelled (depending on whether Stripe's webhook has arrived yet)
										this_component.props.get_single_item_from_server( this_component.props.current_single_item.id.value );
									}, 1000 );

								} );
							}

						} else {
							console.log( data );

							this_component.setState( {
								cancellation_status: 'failed'
							} );

						}
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);

				this_component.setState( {
					cancellation_status: 'failed'
				} );

			}
		);

	}

	render_cancel_button() {

		// If this arrangement was already cancelled, don't show a refund button
		if (
			'cancelled' === this.state.cancellation_status ||
			'cancelled' === this.props.current_single_item.status.value.toLowerCase() ||
			// If the webhook failed, we can't cancel it anyway, because Stripe never told us the sub ID.
			this.props.current_single_item.webhook_notice
		) {
			return ( '' );
		}

		var button_string = this.props.view_info.strings.cancel_arrangement;
		var on_click = this.cancel_arrangement.bind( this );

		if ( 'are_you_sure' == this.state.cancellation_status ) {
			button_string = this.props.view_info.strings.cancel_arrangement_are_you_sure;
		}

		if ( 'cancelling' == this.state.cancellation_status ) {
			button_string = this.props.view_info.strings.cancel_arrangement_cancelling;
			on_click = null;
		}

		if ( 'pending' == this.state.cancellation_status ) {
			button_string = this.props.view_info.strings.cancel_arrangement_pending;
		}

		if ( 'failed' == this.state.cancellation_status ) {
			button_string = this.props.view_info.strings.cancel_arrangement_failed;
		}

		if ( 'cancellation_succeeded' == this.state.cancellation_status ) {
			button_string = this.props.view_info.strings.cancel_arrangement_succeeded;
			on_click = null;
		}

		return( <button className="button" onClick={ this.cancel_arrangement.bind( this ) }>{ button_string }</button> );

	}

	handle_mpwpadmin_button_click( new_state, lightbox_state, event ) {
		this.props.main_component.set_all_current_visual_states( new_state, lightbox_state );
	}

	render(){

		var single_item = this.props.current_single_item;

		return (
			<React.Fragment>
				<div className={ 'mpwpwadmin-list-view-single-data-item-controls' }>
					{ this.render_cancel_button() }
				</div>
				<div className="mpwpadmin-single-data">
					{ (() => {

						var mapper = [];

						// Loop through all of the items to show about this item
						for (var key in single_item) {
							mapper.push( <React.Fragment key={ key }>{

								// Render the output for this data value about the current single item (which was selected from the list)
								(() => {

									// If we should show this peice of data in the list view
									if ( single_item[key]['show_in_single_data_view'] ) {
										return (
											<div className={ 'mpwpadmin-list-view-single-data-item' }>
												<div className={ 'mpwpadmin-list-view-single-data-item-title' }>
													{ single_item[key]['title'] }
												</div>
												<div className={ 'mpwpadmin-list-view-single-data-item-value' }>
													{ ( () => {

														// Set the visual value
														if ( single_item[key]['value_format_function'] ) {
															value = eval( single_item[key]['value_format_function'] )( single_item[key] )
														} else {
															value = single_item[key]['value']
														}

														// If an mpwpadmin state should be fired when the is clicked
														if ( single_item[key]['mpwpadmin_visual_state_onclick'] || single_item[key]['mpwpadmin_lightbox_state_onclick'] ) {

															var visual_state = single_item[key]['mpwpadmin_visual_state_onclick'] ? single_item[key]['mpwpadmin_visual_state_onclick'] : false;
															var lightbox_state = single_item[key]['mpwpadmin_lightbox_state_onclick'] ? single_item[key]['mpwpadmin_lightbox_state_onclick'] : false;

															return(
																<React.Fragment>
																<a onClick={ this.handle_mpwpadmin_button_click.bind( this, visual_state, lightbox_state ) }>{ value }</a>
																</React.Fragment>
															)
														}

														// If a link should be directed to upon click on this item
														else if ( single_item[key]['link_url'] ) {

															var target = single_item[key]['link_target'] ? single_item[key]['link_url'] : null;

															return(
																<React.Fragment>
																<a target={ target } href={ single_item[key]['link_url'] }>{ value }</a>
																</React.Fragment>
															)
														}

														else {
															return value
														}

													})() }
												</div>
											</div>
										);
									}

									return ( '' );

								})()

							}</React.Fragment> );
						}

						return mapper;
					})() }
				</div>
				<ChurchTitheWPTransactionsInArrangement
					main_component={ this.props.main_component }
					view_slug={ 'transactions' }
					view_info={ this.props.view_info.transactions_in_arrangement }
					arrangement_id={ this.props.current_single_item.id.value }
				/>

			</React.Fragment>
		)
	}

};
